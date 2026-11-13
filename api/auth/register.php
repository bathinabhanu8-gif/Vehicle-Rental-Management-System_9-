<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';

use MongoDB\BSON\UTCDateTime;

setJsonResponse();
handleCors();
logRequest();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed. Use POST.', null, 405);
}

$d = json_decode(file_get_contents('php://input'), true);
if (!is_array($d)) {
    sendResponse(false, 'Invalid request data.', null, 400);
}

$name = trim($d['name'] ?? '');
$email = strtolower(trim($d['email'] ?? ''));
$phone = preg_replace('/\s+/', '', trim($d['phone'] ?? ''));
$password = (string)($d['password'] ?? '');

if (strlen($name) < 2) {
    sendResponse(false, 'Please enter your full name (minimum 2 characters).', null, 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, 'Please enter a valid email address.', null, 400);
}
if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
    sendResponse(false, 'Please enter a valid phone number (10 to 15 digits).', null, 400);
}
if (strlen($password) < 6) {
    sendResponse(false, 'Password must contain at least 6 characters.', null, 400);
}

try {
    $db = getDatabase();
    $users = $db->users;

    if ($users->findOne(['email' => $email])) {
        sendResponse(false, 'This email address is already registered. Please sign in or use another email.', null, 409);
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    if ($hash === false) {
        throw new RuntimeException('Password hashing failed');
    }

    $r = $users->insertOne([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'password' => $hash,
        'license' => '',
        'address' => '',
        'role' => 'user',
        'createdAt' => new UTCDateTime(),
        'updatedAt' => new UTCDateTime()
    ]);

    sendResponse(true, 'Account created successfully! You can now sign in.', [
        'id' => (string)$r->getInsertedId(),
        'email' => $email
    ], 201);
} catch (Throwable $e) {
    error_log('Registration error: ' . $e->getMessage());
    sendResponse(false, 'Account could not be created. Please try again.', null, 500);
}
