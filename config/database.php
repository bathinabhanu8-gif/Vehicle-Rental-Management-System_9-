<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

function getDatabase()
{
    $config = require __DIR__ . '/database.local.php';

    try {

        $client = new Client($config['uri']);

        return $client->selectDatabase(
            $config['database']
        );

    } catch (Exception $e) {

        error_log(
            'MongoDB connection error: ' . $e->getMessage()
        );

        throw new Exception(
            'Database connection failed.'
        );
    }
}