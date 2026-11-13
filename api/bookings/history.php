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
requireCustomer();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

try {
    $db = getDatabase();
    $userId = $_SESSION['user_id'] ?? '';
    $uid = (preg_match('/^[a-f0-9]{24}$/i', $userId)) ? new ObjectId($userId) : $userId;

    $cursor = $db->bookings->find(['userId' => $uid], ['sort' => ['createdAt' => -1]]);
    $list = [];

    $formatDate = function ($dateVal) {
        if ($dateVal instanceof \DateTimeInterface) return $dateVal->format('Y-m-d');
        if (is_object($dateVal) && method_exists($dateVal, 'toDateTime')) return $dateVal->toDateTime()->format('Y-m-d');
        if (is_array($dateVal) && isset($dateVal['$date'])) return date('Y-m-d', (int)floor($dateVal['$date'] / 1000));
        if (is_numeric($dateVal)) return date('Y-m-d', (int)$dateVal);
        $ts = strtotime((string)$dateVal);
        return $ts !== false ? date('Y-m-d', $ts) : '—';
    };

    foreach ($cursor as $b) {
        $list[] = [
            'id' => (string)$b['_id'],
            'vehicleName' => $b['vehicleName'] ?? '',
            'vehicleType' => $b['vehicleType'] ?? 'Car',
            'startDate' => $formatDate($b['startDate'] ?? null),
            'endDate' => $formatDate($b['endDate'] ?? null),
            'pickupLocation' => $b['pickupLocation'] ?? '—',
            'returnLocation' => $b['returnLocation'] ?? '—',
            'totalDays' => (int)($b['totalDays'] ?? 0),
            'pricePerDay' => (float)($b['pricePerDay'] ?? 0),
            'totalPrice' => (float)($b['totalPrice'] ?? 0),
            'status' => $b['status'] ?? 'Pending',
            'paymentStatus' => $b['paymentStatus'] ?? 'Not Paid',
            'paymentMethod' => $b['paymentMethod'] ?? '',
            'paymentReference' => $b['paymentReference'] ?? '',
            'algorithmValidation' => $b['algorithmValidation'] ?? null
        ];
    }

    sendResponse(true, 'Booking history retrieved', $list);
} catch (Throwable $e) {
    error_log('Booking history error: ' . $e->getMessage());
    sendResponse(false, 'Failed to retrieve bookings.', null, 500);
}
