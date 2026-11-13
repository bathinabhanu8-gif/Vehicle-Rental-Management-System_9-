<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../middleware/authMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';

setJsonResponse();
handleCors();
logRequest();
requireAdmin();

try {
    $db = getDatabase();
    $b = $db->bookings;

    $revenue = 0.0;
    $paid = 0;
    $recentPayments = [];

    foreach ($b->find(['paymentStatus' => 'Paid (Demo)'], ['sort' => ['paidAt' => -1]]) as $x) {
        $revenue += (float)($x['totalPrice'] ?? 0);
        $paid++;
        if (count($recentPayments) < 5) {
            $recentPayments[] = [
                'vehicle' => $x['vehicleName'] ?? 'Vehicle',
                'amount' => (float)($x['totalPrice'] ?? 0),
                'reference' => $x['paymentReference'] ?? 'Demo Paid'
            ];
        }
    }

    $recent = [];
    foreach ($b->find([], ['sort' => ['createdAt' => -1], 'limit' => 5]) as $x) {
        $recent[] = [
            'vehicle' => $x['vehicleName'] ?? 'Vehicle',
            'status' => $x['status'] ?? 'Pending',
            'amount' => (float)($x['totalPrice'] ?? 0)
        ];
    }

    sendResponse(true, 'Dashboard statistics', [
        'vehicles' => $db->vehicles->countDocuments([]),
        'availableVehicles' => $db->vehicles->countDocuments(['status' => 'Available']),
        'customers' => $db->users->countDocuments(['role' => 'user']),
        'pending' => $b->countDocuments(['status' => 'Pending']),
        'approved' => $b->countDocuments(['status' => 'Approved']),
        'confirmed' => $b->countDocuments(['status' => 'Confirmed']),
        'active' => $b->countDocuments(['status' => 'Active']),
        'returned' => $b->countDocuments(['status' => 'Returned']),
        'completed' => $b->countDocuments(['status' => 'Completed']),
        'paidBookings' => $paid,
        'revenue' => $revenue,
        'recentPayments' => $recentPayments,
        'recentBookings' => $recent
    ]);
} catch (Throwable $e) {
    error_log('Admin stats error: ' . $e->getMessage());
    sendResponse(false, 'Failed to load statistics.', null, 500);
}
