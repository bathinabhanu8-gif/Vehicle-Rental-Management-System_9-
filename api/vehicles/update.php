<?php

require_once '../../middleware/jsonMiddleware.php';
require_once '../../middleware/requestLogger.php';
require_once '../../middleware/corsMiddleware.php';
require_once '../../middleware/authMiddleware.php';
require_once '../../utils/response.php';
require_once '../../config/database.php';

use MongoDB\BSON\ObjectId;

setJsonResponse();
handleCors();
logRequest();
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(
        false,
        'Method not allowed. Use PUT.',
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

$id = trim($data['id'] ?? '');
$name = trim($data['name'] ?? '');
$type = trim($data['type'] ?? '');
$status = trim($data['status'] ?? '');
$pricePerDay = $data['pricePerDay'] ?? null;

if (
    $id === '' ||
    $name === '' ||
    $type === '' ||
    $status === '' ||
    $pricePerDay === null
) {
    sendResponse(
        false,
        'ID, name, type, pricePerDay and status are required.',
        null,
        400
    );
}

if (!preg_match('/^[a-f0-9]{24}$/i', $id)) {
    sendResponse(
        false,
        'Invalid vehicle ID.',
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

    $result = $collection->updateOne(
        [
            '_id' => new ObjectId($id)
        ],
        [
            '$set' => [
                'name' => $name,
                'type' => $type,
                'pricePerDay' => (float) $pricePerDay,
                'status' => $status
            ]
        ]
    );

    if ($result->getMatchedCount() === 0) {
        sendResponse(
            false,
            'Vehicle not found.',
            null,
            404
        );
    }

    sendResponse(
        true,
        'Vehicle updated successfully'
    );

} catch (Exception $e) {

    error_log(
        'Vehicle update error: ' . $e->getMessage()
    );

    sendResponse(
        false,
        'Failed to update vehicle.',
        null,
        500
    );
}