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

$name = trim($data['name'] ?? '');
$email = strtolower(trim($data['email'] ?? ''));
$password = $data['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    sendResponse(
        false,
        'Name, email and password are required.',
        null,
        400
    );
}

if (strlen($name) < 2) {
    sendResponse(
        false,
        'Name must contain at least 2 characters.',
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

if (strlen($password) < 6) {
    sendResponse(
        false,
        'Password must contain at least 6 characters.',
        null,
        400
    );
}

try {

    $database = getDatabase();
    $users = $database->users;

    $existingUser = $users->findOne([
        'email' => $email
    ]);

    if ($existingUser) {
        sendResponse(
            false,
            'User with this email already exists.',
            null,
            409
        );
    }

    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $result = $users->insertOne([
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => 'user',
        'createdAt' => new MongoDB\BSON\UTCDateTime()
    ]);

    sendResponse(
        true,
        'User registered successfully',
        [
            'id' => (string) $result->getInsertedId(),
            'name' => $name,
            'email' => $email
        ],
        201
    );

} catch (Exception $e) {

    error_log(
        'Registration error: ' . $e->getMessage()
    );

    sendResponse(
        false,
        'Registration failed.',
        null,
        500
    );
}