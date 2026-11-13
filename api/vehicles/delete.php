<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../middleware/authMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';

use MongoDB\BSON\ObjectId;

setJsonResponse();
handleCors();
logRequest();
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

$d = json_decode(file_get_contents('php://input'), true);
$id = trim($d['id'] ?? '');

if ($id === '') {
    sendResponse(false, 'Vehicle ID is required.', null, 400);
}

try {
    $db = getDatabase();
    $vid = (preg_match('/^[a-f0-9]{24}$/i', $id)) ? new ObjectId($id) : $id;

    // Check if vehicle has active bookings
    $active = $db->bookings->findOne([
        'vehicleId' => $vid,
        'status' => ['$in' => ['Pending', 'Approved', 'Confirmed', 'Active']]
    ]);

    if ($active) {
        sendResponse(false, 'Cannot delete vehicle because it has pending or active bookings.', null, 409);
    }

    $db->vehicles->deleteOne(['_id' => $vid]);
    sendResponse(true, 'Vehicle deleted successfully.');
} catch (Throwable $e) {
    error_log('Vehicle delete error: ' . $e->getMessage());
    sendResponse(false, 'Failed to delete vehicle.', null, 500);
}
