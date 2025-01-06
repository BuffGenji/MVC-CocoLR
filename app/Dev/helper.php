<?php

declare(strict_types=1);


use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\App;
use Slim\Views\TwigMiddleware;
use Slim\Views\Twig;


/**
 * This file contains helper functions for generating class profiles which will be use to later on feed the intelliSense
 * in the IDE when working with the routes in the Slim application, for which therre will e  aconfiguration file
 * to make the routes more readable and easier to maintain
 */


/**
 * Gets the description from the doc comment of a class or interface, finds the text after the @description tag
 * @param string $doc the doc comment of the class or interface
 */
function getDescription($doc)
{
    $description = '';
    if (preg_match('/@description\s+([^\n]+)/', (string)$doc, $matches)) {
        $description = trim($matches[1]);
    }
    return $description;
}

/**
 * Been modified from the original function to return the class constants that are not in the interfaces
 * also adds inherited constants from the interfaces ( subject to change)
 * @param ReflectionClass $ref
 * @return array an array of the native class constants
 */
function getClassConstants($ref)
{
    // IS INTERFACE
    if ($ref->isInterface()) {
        return $ref->getConstants();
    }
    // IS CLASS : meaning it implements interfaces and has its own constants 
    $interfaceConstants = array_reduce($ref->getInterfaces(), function ($prev, $interface) {
        return array_merge($prev, [trim(basename($interface->getFileName()), '.php') => $interface->getConstants()]);
    }, []);

    $ClassConstants = array_diff_key($ref->getConstants(), ...array_values($interfaceConstants));

    return ['native' => $ClassConstants, 'inherited' => $interfaceConstants];
}

/**
 * Gets the list of interfaces that the class implements, and turns it into a string
 * instead of a list for better readability, like when you implement in a class 
 * @param ReflectionClass $ref
 * @return string ex : implements ApplesInterface, BananasInterface
 */
function getInterfaceList($ref)
{
    $interfaces = $ref->getInterfaces();
    $interfaceNames = array_map(function ($interface) {
        return basename($interface->getName());
    }, $interfaces);
    $interfaceList = implode(', ', $interfaceNames);

    return wordwrap($interfaceList, 80, "\n", true);
}


/**
 * This function generates class profiles for all classes and interfaces in the app directory; and subsequent subdirectries
 * and writes them to a JSON file | FILE = class-profiles.json
 * @param string $outputFile the path to the output file
 */

function generateClassProfiles(string $outputFile)
{
    $allItems = array_merge([
        ...get_declared_classes(),
        ...get_declared_interfaces()
        // trats can be added after .........
    ]);

    $userDefinedElements = array_filter($allItems, function ($item) {
        $ref = new ReflectionClass($item);
        if ($ref->isInterface()) {
            return $ref->isUserDefined();
        } else {
            $filePath = $ref->getFileName();
            return $filePath && strpos($filePath, 'app' . DIRECTORY_SEPARATOR) !== false;
        }
    });

    // creates the profiles array, which will be written to the output file
    $profiles = [];
    foreach ($userDefinedElements as $name) {
        $ref = new ReflectionClass($name);

        $profiles[$name] = [
            'description' => getDescription($ref->getDocComment()),
            'type' => $ref->isInterface() ? 'interface' : 'class',
            'namespace' => $ref->getNamespaceName(),
            'implements' => $ref->isInterface() ? [] : getInterfaceList($ref),
            'constants' => getClassConstants($ref),
            'methods' => array_map(function ($ref) {
                return [
                    'name' => $ref->getName(),
                    'is_static' => $ref->isStatic(),
                    'visibility' => $ref->isPublic()
                        ? 'public'
                        : ($ref->isProtected() ? 'protected' : 'private'),
                    'parameters' => array_map(
                        function ($ref) {
                            return [
                                'name' => $ref->getName(),
                                'type' => $ref->hasType() ? $ref->getType()->getName() : 'mixed',
                                'is_optional' => $ref->isOptional()
                            ];
                        },
                        $ref->getParameters()
                    )
                ];
            }, $ref->getMethods())
        ];
    }
    // creates file if neccessary and writes the profiles to it in JSON
    !(file_exists($outputFile)) ?: (touch($outputFile) && chmod($outputFile, 0777));
    file_put_contents($outputFile, json_encode($profiles, JSON_PRETTY_PRINT));
}

/**
 * Includes all PHP files in a directory recursively from your app, 
 * for clarity the inital root directory is the root directory of the app
 * and the next iterations are the subdirectories of the app
 * 
 * In the event that there is no argument, it starts from the current root directory
 * 
 * @param string $dir the directory to include files from
 * @param array $excludedFiles is an array of files to exclude
 */
function includeAllFilesFrom(string $root_directory = __DIR__, array $excludedFiles = [])
{
    // extra exclusions, need to be placed after to avoid being overwritten
    $excludedFiles = array_merge($excludedFiles, ['.', '..', 'Dev', 'Config', 'Views']);

    if (!is_dir($root_directory)) {
        trigger_error("Directory $root_directory does not exist", E_USER_ERROR);
    }
    $files = scandir($root_directory);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $path = $root_directory . '\\' . $file;
        if (is_dir($path) && !in_array($file, $excludedFiles)) {
            includeAllFilesFrom($path, $excludedFiles);
        } else if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php' && !in_array($file, $excludedFiles)) {
            include_once $path;
        }
    }
}

/**
 * This function is used to load routes from a JSON configuration file
 * and attach them to the Slim app instance
 * 
 * @param App $app the Slim app instance
 * @param string $configFile the path to the JSON configuration file
 * @param Container $container the DI container to get the Twig enviroment 
 */
function loadRoutes($applicationInstance, string $configFile, Container $DIcontainer): void
{
    $config = json_decode(file_get_contents($configFile), true); // Load JSON config
    $twig = $DIcontainer->get(Twig::class);

    foreach ($config['routes'] as $route) {
        $method = strtolower($route['method']); // to comply with Slim method names
        $path = $route['path']; 
        $handler = $route['handler'];
        $action = $route['action'];
        $name = $route['name'];

        // route creation
        $applicationInstance->$method($path, function (Request $request, Response $response, $args) use ($handler, $action, $twig) {
            $controller = new $handler($twig);
            return $controller->$action($request, $response, $args);
        })->setName($name);
    }
}

/**
 * This function creates a DI container with a Twig envroment to render templates
 * 
 * @param string $templateDir the directory where the Twig templates are stored
 * @param string|null $setCache the directory where the Twig cache would be stored, TODO LATER
 * @return Container the DI container
 */


// TODO: Implement cache logic
function makeContainerFrom(string $templateDir, ?string $setCache = null)
{

    //cache logic done later when integratign a cache system, for now just false

    // Create Container
    $container = new Container();

    // Set view in Container
    $container->set(Twig::class, function ($templateDir) {
        return Twig::create($templateDir, ['cache' => false]);
    });

    return $container;
}

/**
 * This function sets up the Slim app instance
 * 
 * @param Container $container the DI container
 * @return App the Slim app instance
 */
function setUp(Container $DIcontainer)
{
    $app = AppFactory::createFromContainer($DIcontainer);
    $app->add(TwigMiddleware::create($app, $DIcontainer->get(Twig::class)));

    // config for middleware, not currently used, will be in a different function later on
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, true, true);
    return $app;
}


