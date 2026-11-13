<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/LocalDatabase.php';

use MongoDB\Client;
use ResilientDB\LocalDatabase;

function getDatabase()
{
    static $dbInstance = null;
    if ($dbInstance !== null) {
        return $dbInstance;
    }

    $localConfigFile = __DIR__ . '/database.local.php';
    $uri = '';
    $databaseName = '';

    // Local XAMPP uses database.local.php when present.
    if (is_file($localConfigFile)) {
        $config = require $localConfigFile;
        $uri = (string)($config['uri'] ?? '');
        $databaseName = (string)($config['database'] ?? 'vehicle_rental_system');
    } else {
        // Deployment (Render / Docker): environment variables
        $uri = (string)(getenv('MONGODB_URI') ?: '');
        $databaseName = (string)(getenv('MONGODB_DATABASE') ?: 'vehicle_rental_system');
    }

    // Attempt Cloud MongoDB Atlas connection if URI is present
    if ($uri !== '') {
        try {
            $driverOptions = [];
            $xamppCaFile = 'C:/xampp/apache/bin/curl-ca-bundle.crt';
            if (is_file($xamppCaFile)) {
                $driverOptions['tlsCAFile'] = $xamppCaFile;
            }

            // Set short server selection timeout to avoid long hangs in offline environments
            $client = new Client($uri, [
                'serverSelectionTimeoutMS' => 2500,
                'connectTimeoutMS' => 2500
            ], $driverOptions);

            // Ping the database to verify connectivity
            $database = $client->selectDatabase($databaseName);
            $database->command(['ping' => 1]);

            $dbInstance = $database;
            return $dbInstance;
        } catch (Throwable $e) {
            error_log('[Database Resilience] Cloud MongoDB Atlas unreachable (' . $e->getMessage() . '). Switching to Resilient Local Engine.');
        }
    }

    // Graceful fallback to Resilient Local Engine
    $localStorageFile = __DIR__ . '/../database/data_store.json';
    $dbInstance = new LocalDatabase($localStorageFile);
    return $dbInstance;
}
