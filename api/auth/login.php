<?php

require_once '../../middleware/jsonMiddleware.php';
require_once '../../middleware/requestLogger.php';
require_once '../../middleware/corsMiddleware.php';
require_once '../../utils/response.php';
require_once '../../config/database.php';

setJsonResponse();
handleCors();
logRequest();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(
        false,
        'Method not allowed. Use POST.',
        null,
        405
    );
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    sendResponse(
        false,
        'Invalid JSON data.',
        null,
        400
    );
}

$email = strtolower(trim($data['email'] ?? ''));
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    sendResponse(
        false,
        'Email and password are required.',
        null,
        400
    );
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(
        false,
        'Invalid email address.',
        null,
        400
    );
}

try {

    $database = getDatabase();
    $users = $database->users;

    $user = $users->findOne([
        'email' => $email
    ]);

    if (!$user || !password_verify($password, $user['password'])) {
        sendResponse(
            false,
            'Invalid email or password.',
            null,
            401
        );
    }

    session_start();

    session_regenerate_id(true);

    $_SESSION['user_id'] = (string) $user['_id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];

    sendResponse(
        true,
        'Login successful',
        [
            'id' => (string) $user['_id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    );

} catch (Exception $e) {

    error_log(
        'Login error: ' . $e->getMessage()
    );

    sendResponse(
        false,
        'Login failed.',
        null,
        500
    );
}