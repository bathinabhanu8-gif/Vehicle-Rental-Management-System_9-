<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../algorithms/IntervalOverlap.php';

use DAA\IntervalOverlap;

setJsonResponse();
handleCors();
logRequest();

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$vehicleId = trim($_GET['vehicleId'] ?? $input['vehicleId'] ?? '');
$startDate = trim($_GET['startDate'] ?? $input['startDate'] ?? '');
$endDate = trim($_GET['endDate'] ?? $input['endDate'] ?? '');

if ($startDate === '' || $endDate === '') {
    sendResponse(false, 'Please provide both startDate and endDate (Y-m-d).', null, 400);
}

try {
    $db = getDatabase();
    $filter = [];
    if ($vehicleId !== '') {
        $filter['vehicleId'] = (preg_match('/^[a-f0-9]{24}$/i', $vehicleId)) ? new \MongoDB\BSON\ObjectId($vehicleId) : $vehicleId;
    }

    $existingBookings = iterator_to_array($db->bookings->find($filter));

    // Run Interval Overlap Detection Algorithm
    $result = IntervalOverlap::checkOverlap($startDate, $endDate, $existingBookings);

    // Also compute free slots in the next 30 days
    $today = date('Y-m-d');
    $thirtyDaysOut = date('Y-m-d', strtotime('+30 days'));
    $freeSlots = IntervalOverlap::findAvailableSlots($today, $thirtyDaysOut, $existingBookings);
    $result['availableSlotsNext30Days'] = $freeSlots;

    sendResponse(true, $result['hasConflict'] ? 'Overlap collision detected.' : 'Interval is completely available.', $result);
} catch (Throwable $e) {
    error_log('IntervalOverlap API error: ' . $e->getMessage());
    sendResponse(false, 'Interval overlap algorithm failed.', null, 500);
}
