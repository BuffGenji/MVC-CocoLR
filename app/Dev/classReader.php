<?php

declare(strict_types=1);
require_once '../../vendor/autoload.php';

/**
 * This file is to facilitate the extraction of specifics from the class-profiles.json
 * file. It will be used to fill the suggestions in the routes-schhema.json which can then be used in the routes.json
 */


const PATH_TO_CLASS_PROFILES = __DIR__ . '\\..\\class-profiles.json';
$CLASS_PROFILES = (json_decode(file_get_contents(PATH_TO_CLASS_PROFILES), true));


/**
 * This function returns the different classes ( that we can later filter for controllers )
 * in the class-profiles.json file
 * 
 * NEEDS REFACTORING
 */
function namespacePaths()
{
    global $CLASS_PROFILES;
    $full_paths = [];
    foreach ($CLASS_PROFILES as $className => $lol) { // $lol is just a placeholder, we don't need it, but it changes the structure without it, big yikes	
        if (strpos($className, 'Controllers') !== false)
            $full_paths[] = $className;
    }
    return $full_paths;
}
// print_r(namespacePaths());

/**
 * This function will return the list of method names for a given class based on the class-profiles.json file
 */

function getMethodsForClass(string $className)
{
    global $CLASS_PROFILES;
    $arr_class_methods = [];
    foreach ($CLASS_PROFILES[$className]['methods'] as $method) {
        if (strpos($method['name'], '__') !== 0) { // to filter out the magic methods
            $arr_class_methods[] = $method['name'];
        }
    }
    $result = [$className => $arr_class_methods];
    return $result;
}

/**
 * Now we need a function that will create in the schema the enums for the if then clauses
 */

function generateIfThenFields($controllers)
{
    $ifThenFields = [];
    foreach ($controllers as $controller) {
        if (strpos($controller, 'Controllers') === false) {
            continue;
        }
        $ifThenFields[] = [
            "if" => [
                "properties" => [
                    "handler" => [
                        "const" => $controller
                    ]
                ]
            ],
            "then" => [
                "properties" => [
                    "action" => [
                        "enum" => getMethodsForClass($controller)[$controller]
                    ]
                ]
            ]
        ];
    }
    return $ifThenFields;
};
