<?php

require_once '../../middleware/jsonMiddleware.php';
require_once '../../middleware/requestLogger.php';
require_once '../../middleware/corsMiddleware.php';
require_once '../../utils/response.php';
require_once '../../config/database.php';

setJsonResponse();
handleCors();
logRequest();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(
        false,
        'Method not allowed. Use GET.',
        null,
        405
    );
}

try {

    $database = getDatabase();
    $collection = $database->vehicles;

    $vehicles = $collection->find()->toArray();

    $vehicleList = [];

    foreach ($vehicles as $vehicle) {

        $vehicleList[] = [
            'id' => (string) $vehicle['_id'],
            'name' => $vehicle['name'] ?? '',
            'type' => $vehicle['type'] ?? '',
            'pricePerDay' => $vehicle['pricePerDay'] ?? 0,
            'status' => $vehicle['status'] ?? ''
        ];
    }

    sendResponse(
        true,
        'Vehicles retrieved successfully from MongoDB',
        $vehicleList
    );

} catch (Exception $e) {

    error_log(
        'Vehicle retrieval error: ' . $e->getMessage()
    );

    sendResponse(
        false,
        'Failed to retrieve vehicles from database.',
        null,
        500
    );
}