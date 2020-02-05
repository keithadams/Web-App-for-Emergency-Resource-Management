<?php

$config = [
    'settings' => [
        // Slim Settings
        'displayErrorDetails' => true,

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => APP_ROOT . '/logs/app.log',
        ],

        // Renderer settings
        'renderer' => [
            'template_path' => APP_ROOT . '/templates/',
        ],
    ],
];

$config['settings']['db']['host']    = "localhost";
$config['settings']['db']['user']    = "root";
$config['settings']['db']['pass']    = "";
$config['settings']['db']['port']    = "3306";
$config['settings']['db']['charset'] = "utf8mb4";
$config['settings']['db']['dbname']  = "team072_cs6400";

return $config;