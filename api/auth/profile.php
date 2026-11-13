<?php

require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';

setJsonResponse();
handleCors();
logRequest();

if (session_status() === PHP_SESSION_NONE) {
    session_name('VS_CUSTOMER');
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    sendResponse(false, 'Unauthorized. Please login first.', null, 401);
}

sendResponse(
    true,
    'Profile accessed successfully',
    [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? 'Customer',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'user'
    ]
);
