<?php

function handleCors()
{
    $allowedOrigins = [
        'http://localhost',
        'http://localhost:5173'
    ];

    if (isset($_SERVER['HTTP_ORIGIN'])) {

        $origin = $_SERVER['HTTP_ORIGIN'];

        if (in_array($origin, $allowedOrigins, true)) {
            header("Access-Control-Allow-Origin: $origin");
            header('Access-Control-Allow-Credentials: true');
        }
    }

    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}