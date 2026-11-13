<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../middleware/authMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

setJsonResponse();
handleCors();
logRequest();
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

$d = json_decode(file_get_contents('php://input'), true);
$id = trim($d['id'] ?? '');
$action = trim($d['action'] ?? '');

$map = [
    'Pickup' => [
        'from' => 'Confirmed',
        'to' => 'Active',
        'field' => 'pickedUpAt',
        'message' => 'Vehicle marked as picked up. Rental is now active.'
    ],
    'Return' => [
        'from' => 'Active',
        'to' => 'Returned',
        'field' => 'returnedAt',
        'message' => 'Vehicle marked as returned.'
    ],
    'Complete' => [
        'from' => 'Returned',
        'to' => 'Completed',
        'field' => 'completedAt',
        'message' => 'Rental completed successfully.'
    ]
];

if ($id === '' || !isset($map[$action])) {
    sendResponse(false, 'Invalid lifecycle request.', null, 400);
}

try {
    $db = getDatabase();
    $m = $map[$action];
    $bid = (preg_match('/^[a-f0-9]{24}$/i', $id)) ? new ObjectId($id) : $id;
    $b = $db->bookings->findOne(['_id' => $bid, 'status' => $m['from']]);

    if (!$b) {
        sendResponse(false, 'Booking is not in the correct stage for this action.', null, 409);
    }

    if ($action === 'Pickup' && ($b['paymentStatus'] ?? '') !== 'Paid (Demo)') {
        sendResponse(false, 'Pickup is blocked because payment is not verified for this booking.', null, 409);
    }

    $db->bookings->updateOne(
        ['_id' => $b['_id']],
        ['$set' => [
            'status' => $m['to'],
            $m['field'] => new UTCDateTime(),
            'updatedAt' => new UTCDateTime()
        ]]
    );

    if (isset($b['vehicleId'])) {
        $vehicleStatus = $action === 'Pickup' ? 'On Rent' : ($action === 'Complete' ? 'Available' : 'Booked');
        $db->vehicles->updateOne(['_id' => $b['vehicleId']], ['$set' => ['status' => $vehicleStatus]]);
    }

    sendResponse(true, $m['message']);
} catch (Throwable $e) {
    error_log('Lifecycle error: ' . $e->getMessage());
    sendResponse(false, 'Unable to update rental lifecycle.', null, 500);
}
