<?php
require_once __DIR__ . '/../vendor/autoload.php';
use MongoDB\Client;
function getDatabase()
{
    $config = require __DIR__ . '/database.local.php';
    try {
        $driverOptions = [];
        $caFile = 'C:/xampp/apache/bin/curl-ca-bundle.crt';
        if (is_file($caFile)) $driverOptions['tlsCAFile'] = $caFile;
        $client = new Client($config['uri'], [], $driverOptions);
        return $client->selectDatabase($config['database']);
    } catch (Throwable $e) {
        error_log('MongoDB connection error: '.$e->getMessage());
        throw new Exception('Database connection failed.');
    }
}
