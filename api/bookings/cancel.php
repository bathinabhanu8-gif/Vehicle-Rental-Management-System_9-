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
requireCustomer();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

$d = json_decode(file_get_contents('php://input'), true);
$id = trim($d['id'] ?? '');

if ($id === '') {
    sendResponse(false, 'Invalid booking ID.', null, 400);
}

try {
    $db = getDatabase();
    $bid = (preg_match('/^[a-f0-9]{24}$/i', $id)) ? new ObjectId($id) : $id;
    $uid = (preg_match('/^[a-f0-9]{24}$/i', $_SESSION['user_id'])) ? new ObjectId($_SESSION['user_id']) : $_SESSION['user_id'];

    $r = $db->bookings->updateOne(
        [
            '_id' => $bid,
            'userId' => $uid,
            'status' => ['$in' => ['Pending', 'Approved']]
        ],
        ['$set' => [
            'status' => 'Cancelled',
            'paymentStatus' => 'Cancelled',
            'updatedAt' => new UTCDateTime()
        ]]
    );

    if (!$r->getMatchedCount()) {
        sendResponse(false, 'Only pending or approved bookings can be cancelled.', null, 409);
    }

    sendResponse(true, 'Booking cancelled successfully.');
} catch (Throwable $e) {
    error_log('Cancel error: ' . $e->getMessage());
    sendResponse(false, 'Unable to cancel booking.', null, 500);
}
