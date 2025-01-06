<?php


/**
 * This is the main entry point for the application, and will: 
 * - create the routes
 * - starts the application
 * - sets up the middleware (for later)
 * 
 */

declare(strict_types=1);
require_once __DIR__ . '\\..\\vendor\\autoload.php';

use App\Services\Authentication;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use DI\Container;


// updates the class profiles
$updateClassProfiles = (function () {
    // require_once __DIR__ . '\\Dev\\check.php';
})();

// loads the routes from the routes.json file, NEEDED => (NOT IN NAMESPACE)
$loadRoutes = (function () {
    require_once __DIR__ . '\\Dev\\helper.php';
})();


// setting handles big-ticket items such as the app and the container
$settings = (function () {

    // getting all of the templates from the folder and setting up Twig env
    $container = new Container();
    $container->set(Twig::class, function () {
        return Twig::create(__DIR__ . '\\Templates', ['cache' => false]);
    });

    // creating the app from the container
    $app = AppFactory::createFromContainer($container);
    return (object) [
        'app' => $app,
        'container' => $container
    ];
})();

// loads the routes from the routes.json file
loadRoutes(
    applicationInstance: $settings->app,
    configFile: __DIR__ . '\\Config\\routes.json',
    DIcontainer: $settings->container
);

// setting up the middleware for the app
$middleWareSettings = (function ()  use ($settings) {
    $app = $settings->app;
    $container = $settings->container;
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
    $app->add(new Authentication());

    // later when grouping things together, we can add more middleware here
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, true, true); // to see errors
})();

$settings->app->run();
