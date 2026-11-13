<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../middleware/authMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../algorithms/IntervalOverlap.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use DAA\IntervalOverlap;

setJsonResponse();
handleCors();
logRequest();
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

$d = json_decode(file_get_contents('php://input'), true);
$id = trim($d['id'] ?? '');
$decision = trim($d['decision'] ?? '');

if ($id === '' || !in_array($decision, ['Approved', 'Rejected'], true)) {
    sendResponse(false, 'Invalid booking decision.', null, 400);
}

try {
    $db = getDatabase();
    $bid = (preg_match('/^[a-f0-9]{24}$/i', $id)) ? new ObjectId($id) : $id;
    $b = $db->bookings->findOne(['_id' => $bid, 'status' => 'Pending']);

    if (!$b) {
        sendResponse(false, 'Pending booking not found.', null, 404);
    }

    if ($decision === 'Approved') {
        // Run Interval Overlap algorithm to guarantee no overlapping reservations exist
        $existing = iterator_to_array($db->bookings->find([
            '_id' => ['$ne' => $bid],
            'vehicleId' => $b['vehicleId'],
            'status' => ['$in' => ['Approved', 'Confirmed', 'Active', 'Returned']]
        ]));

        $overlapReport = IntervalOverlap::checkOverlap($b['startDate'], $b['endDate'], $existing);
        if ($overlapReport['hasConflict']) {
            $conflict = $overlapReport['conflicts'][0] ?? null;
            $msg = 'Cannot approve this request because the vehicle is already reserved for overlapping dates.';
            if ($conflict) {
                $msg .= " (Conflicts with booking {$conflict['bookingId']} from {$conflict['existingStart']} to {$conflict['existingEnd']})";
            }
            sendResponse(false, $msg, null, 409);
        }
    }

    $db->bookings->updateOne(
        ['_id' => $b['_id']],
        ['$set' => [
            'status' => $decision,
            'paymentStatus' => $decision === 'Approved' ? 'Awaiting Payment' : 'Not Paid',
            'updatedAt' => new UTCDateTime()
        ]]
    );

    sendResponse(true, $decision === 'Approved' ? 'Booking approved. Customer can now proceed with demo payment.' : 'Booking request rejected.');
} catch (Throwable $e) {
    error_log('Booking decision error: ' . $e->getMessage());
    sendResponse(false, 'Unable to update booking.', null, 500);
}
