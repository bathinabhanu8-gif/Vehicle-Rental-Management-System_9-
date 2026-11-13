<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../middleware/authMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

setJsonResponse();
handleCors();
logRequest();
requireCustomer();

try {
    $db = getDatabase();
    $users = $db->users;
    $uid = (preg_match('/^[a-f0-9]{24}$/i', $_SESSION['user_id'])) ? new ObjectId($_SESSION['user_id']) : $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $u = $users->findOne(['_id' => $uid]);
        if (!$u) {
            sendResponse(false, 'Profile not found.', null, 404);
        }
        sendResponse(true, 'Profile loaded', [
            'name' => $u['name'] ?? '',
            'email' => $u['email'] ?? '',
            'phone' => $u['phone'] ?? '',
            'license' => $u['license'] ?? '',
            'address' => $u['address'] ?? '',
            'role' => 'user'
        ]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $d = json_decode(file_get_contents('php://input'), true);
        if (!is_array($d)) {
            sendResponse(false, 'Invalid request data.', null, 400);
        }

        $name = trim($d['name'] ?? '');
        $phone = preg_replace('/\s+/', '', trim($d['phone'] ?? ''));
        $license = trim($d['license'] ?? '');
        $address = trim($d['address'] ?? '');

        if (strlen($name) < 2) {
            sendResponse(false, 'Please enter a valid name (at least 2 characters).', null, 400);
        }
        if ($phone !== '' && !preg_match('/^[0-9]{10,15}$/', $phone)) {
            sendResponse(false, 'Please enter a valid 10-15 digit phone number.', null, 400);
        }

        $users->updateOne(
            ['_id' => $uid],
            ['$set' => [
                'name' => $name,
                'phone' => $phone,
                'license' => $license,
                'address' => $address,
                'updatedAt' => new UTCDateTime()
            ]]
        );

        $_SESSION['user_name'] = $name;
        sendResponse(true, 'Profile updated successfully.');
    }

    sendResponse(false, 'Method not allowed.', null, 405);
} catch (Throwable $e) {
    error_log('Profile error: ' . $e->getMessage());
    sendResponse(false, 'Profile operation failed.', null, 500);
}
