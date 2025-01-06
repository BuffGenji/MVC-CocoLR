<?php

declare(strict_types=1);
include_once 'classReader.php';

function generateRoutesSchema()
{
    $schema = [
        "title" => "Routes Configuration",
        "description" => "Schema for routes configuration file",
        "type" => "object",
        "properties" => [
            "routes" => [
                "type" => "array",
                "items" => [
                    "type" => "object",
                    "properties" => [
                        "method" => [
                            "type" => "string",
                            "enum" => ["get", "post", "put", "delete", "patch"],
                            "description" => "HTTP method for the route"
                        ],
                        "path" => [
                            "type" => "string",
                            "description" => "Path for the route"
                        ],
                        "handler" => [
                            "type" => "string",
                            "enum" =>
                            $definedControllers = namespacePaths(),
                            "description" => "Handler class for the route"
                        ],
                        "action" => [
                            "type" => "string",
                            "description" => "Action method in the handler class",
                            "allOf" => generateIfThenFields($definedControllers)
                        ]
                    ],
                    "required" => ["method", "path", "handler", "action"]
                ]
            ]
        ],
        "required" => ["routes"]
    ];

    return json_encode($schema, JSON_PRETTY_PRINT);
}

// Generate the schema and save it to a file
$schemaJson = generateRoutesSchema();
file_put_contents(__DIR__ . '/../Config/routes-schema2.json', $schemaJson);
