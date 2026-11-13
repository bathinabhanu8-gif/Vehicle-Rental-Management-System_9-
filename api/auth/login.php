<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';

setJsonResponse();
handleCors();
logRequest();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

$d = json_decode(file_get_contents('php://input'), true);
if (!is_array($d)) {
    sendResponse(false, 'Invalid request payload.', null, 400);
}

$email = strtolower(trim($d['email'] ?? ''));
$password = (string)($d['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    sendResponse(false, 'Enter your registered email and password.', null, 400);
}

try {
    $db = getDatabase();
    $u = $db->users->findOne(['email' => $email]);

    if (!$u) {
        sendResponse(false, 'No customer account was found with this email. Please register first.', null, 401);
    }

    if (($u['role'] ?? 'user') !== 'user') {
        sendResponse(false, 'This is an administrator account. Please use the separate Admin Login portal.', null, 403);
    }

    $stored = (string)($u['password'] ?? '');
    $valid = $stored !== '' && password_verify($password, $stored);

    // Fallback for plaintext development records
    if (!$valid && $stored !== '' && (password_get_info($stored)['algoName'] ?? 'unknown') === 'unknown' && hash_equals($stored, $password)) {
        $valid = true;
        $stored = password_hash($password, PASSWORD_DEFAULT);
        $db->users->updateOne(['_id' => $u['_id']], ['$set' => ['password' => $stored]]);
    }

    if (!$valid) {
        sendResponse(false, 'Incorrect password. Please check your credentials.', null, 401);
    }

    if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
        $db->users->updateOne(['_id' => $u['_id']], ['$set' => ['password' => password_hash($password, PASSWORD_DEFAULT)]]);
    }

    session_name('VS_CUSTOMER');
    session_start();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (string)$u['_id'];
    $_SESSION['user_name'] = (string)($u['name'] ?? 'Customer');
    $_SESSION['user_email'] = (string)($u['email'] ?? $email);
    $_SESSION['user_role'] = 'user';

    sendResponse(true, 'Customer login successful.', [
        'role' => 'user',
        'name' => $_SESSION['user_name']
    ]);
} catch (Throwable $e) {
    error_log('Customer login error: ' . $e->getMessage());
    sendResponse(false, 'Login failed because the server could not verify your account.', null, 500);
}
