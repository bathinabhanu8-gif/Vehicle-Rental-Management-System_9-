<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';

setJsonResponse();
handleCors();
logRequest();

$portal = $_GET['portal'] ?? 'customer';

if ($portal === 'admin') {
    if (session_status() === PHP_SESSION_NONE) {
        session_name('VS_ADMIN');
        session_start();
    }
} else {
    if (session_status() === PHP_SESSION_NONE) {
        session_name('VS_CUSTOMER');
        session_start();
    }
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

sendResponse(true, 'Logged out successfully.');
