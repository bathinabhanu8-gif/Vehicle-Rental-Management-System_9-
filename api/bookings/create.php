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
requireCustomer();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    sendResponse(false, 'Invalid request data.', null, 400);
}

$vehicleId = trim($data['vehicleId'] ?? '');
$st = trim($data['startDate'] ?? '');
$et = trim($data['endDate'] ?? '');
$pickup = trim($data['pickupLocation'] ?? '');
$return = trim($data['returnLocation'] ?? '');

if ($vehicleId === '' || $pickup === '' || $return === '') {
    sendResponse(false, 'Vehicle, pickup location and return location are required.', null, 400);
}

$start = DateTimeImmutable::createFromFormat('!Y-m-d', $st);
$end = DateTimeImmutable::createFromFormat('!Y-m-d', $et);
$today = new DateTimeImmutable('today');

if (!$start || !$end || $start < $today || $end < $start) {
    sendResponse(false, 'Please choose valid rental dates (start date cannot be in the past, and return date cannot precede pickup date).', null, 400);
}

try {
    $db = getDatabase();
    $vid = (preg_match('/^[a-f0-9]{24}$/i', $vehicleId)) ? new ObjectId($vehicleId) : $vehicleId;
    $v = $db->vehicles->findOne(['_id' => $vid]);

    if (!$v) {
        sendResponse(false, 'Vehicle not found.', null, 404);
    }

    if (($v['status'] ?? 'Available') === 'Maintenance') {
        sendResponse(false, 'Vehicle is currently under maintenance.', null, 409);
    }

    // Retrieve all active bookings for this vehicle to run DAA Interval Overlap Algorithm
    $existingBookings = iterator_to_array($db->bookings->find([
        'vehicleId' => $vid,
        'status' => ['$in' => ['Approved', 'Confirmed', 'Active', 'Returned']]
    ]));

    // Execute Interval Overlap Detection Algorithm
    $overlapReport = IntervalOverlap::checkOverlap($start, $end, $existingBookings);

    if ($overlapReport['hasConflict']) {
        $firstConflict = $overlapReport['conflicts'][0] ?? null;
        $conflictMsg = 'These dates are already reserved.';
        if ($firstConflict) {
            $conflictMsg = "Vehicle is already booked from {$firstConflict['existingStart']} to {$firstConflict['existingEnd']}. Please choose different dates.";
        }
        sendResponse(false, $conflictMsg, ['overlapReport' => $overlapReport], 409);
    }

    $days = (int)$start->diff($end)->days + 1;
    $price = (float)($v['pricePerDay'] ?? 0);
    $total = $days * $price;

    $su = new UTCDateTime($start->getTimestamp() * 1000);
    $eu = new UTCDateTime($end->getTimestamp() * 1000);

    $r = $db->bookings->insertOne([
        'userId' => (preg_match('/^[a-f0-9]{24}$/i', $_SESSION['user_id'])) ? new ObjectId($_SESSION['user_id']) : $_SESSION['user_id'],
        'vehicleId' => $vid,
        'vehicleName' => (string)$v['name'],
        'vehicleType' => (string)($v['type'] ?? 'Car'),
        'pricePerDay' => $price,
        'startDate' => $su,
        'endDate' => $eu,
        'pickupLocation' => $pickup,
        'returnLocation' => $return,
        'totalDays' => $days,
        'totalPrice' => $total,
        'status' => 'Pending',
        'paymentStatus' => 'Not Paid',
        'algorithmValidation' => [
            'method' => 'DAA Interval Overlap Detection',
            'validated' => true,
            'checkedIntervals' => $overlapReport['totalIntervalsChecked']
        ],
        'createdAt' => new UTCDateTime(),
        'updatedAt' => new UTCDateTime()
    ]);

    sendResponse(true, 'Booking request submitted successfully! Awaiting administrator approval.', [
        'bookingId' => (string)$r->getInsertedId(),
        'totalDays' => $days,
        'totalPrice' => $total
    ], 201);
} catch (Throwable $e) {
    error_log('Booking create error: ' . $e->getMessage());
    sendResponse(false, 'Booking request failed.', null, 500);
}
