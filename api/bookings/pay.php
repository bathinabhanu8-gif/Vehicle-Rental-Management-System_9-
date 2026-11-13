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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

$d = json_decode(file_get_contents('php://input'), true);
$id = trim($d['id'] ?? '');
$method = trim($d['method'] ?? 'UPI');

if ($id === '' || !in_array($method, ['UPI', 'Card'], true)) {
    sendResponse(false, 'Invalid payment request.', null, 400);
}

try {
    $db = getDatabase();
    $bid = (preg_match('/^[a-f0-9]{24}$/i', $id)) ? new ObjectId($id) : $id;
    $uid = (preg_match('/^[a-f0-9]{24}$/i', $_SESSION['user_id'])) ? new ObjectId($_SESSION['user_id']) : $_SESSION['user_id'];

    $b = $db->bookings->findOne(['_id' => $bid, 'userId' => $uid]);
    if (!$b) {
        sendResponse(false, 'Booking not found.', null, 404);
    }

    if (($b['status'] ?? '') !== 'Approved') {
        sendResponse(false, 'Payment is available only after admin approval.', null, 409);
    }

    $ref = 'VST' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));

    $db->bookings->updateOne(
        ['_id' => $b['_id']],
        ['$set' => [
            'status' => 'Confirmed',
            'paymentStatus' => 'Paid (Demo)',
            'paymentMethod' => $method,
            'paymentReference' => $ref,
            'paidAt' => new UTCDateTime(),
            'updatedAt' => new UTCDateTime()
        ]]
    );

    if (isset($b['vehicleId'])) {
        $db->vehicles->updateOne(['_id' => $b['vehicleId']], ['$set' => ['status' => 'Booked']]);
    }

    sendResponse(true, 'Demo payment successful. Booking confirmed!', ['reference' => $ref]);
} catch (Throwable $e) {
    error_log('Payment error: ' . $e->getMessage());
    sendResponse(false, 'Payment could not be completed.', null, 500);
}
