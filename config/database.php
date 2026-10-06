<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

function getDatabase()
{
    $localConfigFile = __DIR__ . '/database.local.php';
    $uri = '';
    $databaseName = '';

    // Local XAMPP must use the local configuration when it exists. This avoids
    // stale machine environment variables silently pointing the app at another DB.
    if (is_file($localConfigFile)) {
        $config = require $localConfigFile;
        $uri = (string)($config['uri'] ?? '');
        $databaseName = (string)($config['database'] ?? '');
    } else {
        // Deployment (Render): the private local file is intentionally absent.
        $uri = (string)(getenv('MONGODB_URI') ?: '');
        $databaseName = (string)(getenv('MONGODB_DATABASE') ?: '');
    }

    if ($uri === '' || $databaseName === '') {
        throw new Exception('Database configuration is incomplete.');
    }

    try {
        $driverOptions = [];
        $xamppCaFile = 'C:/xampp/apache/bin/curl-ca-bundle.crt';
        if (is_file($xamppCaFile)) {
            $driverOptions['tlsCAFile'] = $xamppCaFile;
        }

        $client = new Client($uri, [], $driverOptions);
        return $client->selectDatabase($databaseName);
    } catch (Throwable $e) {
        error_log('MongoDB connection error: ' . $e->getMessage());
        throw new Exception('Database connection failed.');
    }
}
