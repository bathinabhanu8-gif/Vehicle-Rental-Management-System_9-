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

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

try {
    $db = getDatabase();
    $list = [];

    foreach ($db->users->find(['role' => 'user'], ['sort' => ['createdAt' => -1]]) as $u) {
        $list[] = [
            'id' => (string)$u['_id'],
            'name' => $u['name'] ?? '',
            'email' => $u['email'] ?? '',
            'phone' => $u['phone'] ?? '',
            'license' => $u['license'] ?? '',
            'address' => $u['address'] ?? '',
            'bookings' => $db->bookings->countDocuments(['userId' => $u['_id']])
        ];
    }

    sendResponse(true, 'Customers retrieved', $list);
} catch (Throwable $e) {
    error_log('Admin users error: ' . $e->getMessage());
    sendResponse(false, 'Failed to load customers.', null, 500);
}
