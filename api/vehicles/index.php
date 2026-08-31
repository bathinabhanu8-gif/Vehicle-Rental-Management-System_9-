<?php

require_once '../../middleware/jsonMiddleware.php';
require_once '../../middleware/requestLogger.php';
require_once '../../utils/response.php';

setJsonResponse();
logRequest();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {

    $vehicles = [
        [
            'id' => 1,
            'name' => 'Hyundai Creta',
            'type' => 'Car',
            'pricePerDay' => 2500,
            'status' => 'Available'
        ],
        [
            'id' => 2,
            'name' => 'Honda City',
            'type' => 'Car',
            'pricePerDay' => 2200,
            'status' => 'Available'
        ],
        [
            'id' => 3,
            'name' => 'Royal Enfield Classic 350',
            'type' => 'Bike',
            'pricePerDay' => 1200,
            'status' => 'Available'
        ]
    ];

    sendResponse(
        true,
        'Vehicles retrieved successfully',
        $vehicles
    );

} else {

    sendResponse(
        false,
        'Method not allowed',
        null,
        405
    );
}
