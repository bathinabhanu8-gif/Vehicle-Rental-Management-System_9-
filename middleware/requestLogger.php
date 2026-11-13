<?php

function logRequest()
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = $_SERVER['REQUEST_URI'] ?? 'CLI';
    $time = date('Y-m-d H:i:s');

    $logMessage = "[$time] $method $uri" . PHP_EOL;

    $logDirectory = __DIR__ . '/../logs';

    if (!is_dir($logDirectory)) {
        @mkdir($logDirectory, 0777, true);
    }

    @file_put_contents(
        $logDirectory . '/requests.log',
        $logMessage,
        FILE_APPEND | LOCK_EX
    );
}
