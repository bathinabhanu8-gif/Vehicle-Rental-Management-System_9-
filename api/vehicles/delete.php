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
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    sendResponse(
        false,
        'Method not allowed. Use DELETE.',
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

if ($id === '') {
    sendResponse(
        false,
        'Vehicle ID is required.',
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

try {

    $database = getDatabase();
    $collection = $database->vehicles;

    $result = $collection->deleteOne([
        '_id' => new ObjectId($id)
    ]);

    if ($result->getDeletedCount() === 0) {
        sendResponse(
            false,
            'Vehicle not found.',
            null,
            404
        );
    }

    sendResponse(
        true,
        'Vehicle deleted successfully'
    );

} catch (Exception $e) {

    error_log(
        'Vehicle deletion error: ' . $e->getMessage()
    );

    sendResponse(
        false,
        'Failed to delete vehicle.',
        null,
        500
    );
}