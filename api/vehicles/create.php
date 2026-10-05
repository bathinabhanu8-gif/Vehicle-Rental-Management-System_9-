<?php

require_once '../../middleware/jsonMiddleware.php';
require_once '../../middleware/requestLogger.php';
require_once '../../middleware/corsMiddleware.php';
require_once '../../middleware/authMiddleware.php';
require_once '../../utils/response.php';
require_once '../../config/database.php';

setJsonResponse();
handleCors();
logRequest();
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(
        false,
        'Method not allowed. Use POST.',
        null,
        405
    );
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    sendResponse(
        false,
        'Invalid JSON data.',
        null,
        400
    );
}

$name = trim($data['name'] ?? '');
$type = trim($data['type'] ?? '');
$status = trim($data['status'] ?? '');
$pricePerDay = $data['pricePerDay'] ?? null;

if (
    $name === '' ||
    $type === '' ||
    $status === '' ||
    $pricePerDay === null
) {
    sendResponse(
        false,
        'Name, type, pricePerDay and status are required.',
        null,
        400
    );
}

if (!is_numeric($pricePerDay) || $pricePerDay <= 0) {
    sendResponse(
        false,
        'pricePerDay must be a positive number.',
        null,
        400
    );
}

$allowedTypes = ['Car', 'Bike'];
$allowedStatuses = ['Available', 'Unavailable'];

if (!in_array($type, $allowedTypes, true)) {
    sendResponse(
        false,
        'Vehicle type must be Car or Bike.',
        null,
        400
    );
}

if (!in_array($status, $allowedStatuses, true)) {
    sendResponse(
        false,
        'Status must be Available or Unavailable.',
        null,
        400
    );
}

try {

    $database = getDatabase();
    $collection = $database->vehicles;

    $result = $collection->insertOne([
        'name' => $name,
        'type' => $type,
        'pricePerDay' => (float) $pricePerDay,
        'status' => $status
    ]);

    sendResponse(
        true,
        'Vehicle created successfully',
        [
            'id' => (string) $result->getInsertedId()
        ],
        201
    );

} catch (Exception $e) {

    error_log(
        'Vehicle creation error: ' . $e->getMessage()
    );

    sendResponse(
        false,
        'Failed to create vehicle.',
        null,
        500
    );
}