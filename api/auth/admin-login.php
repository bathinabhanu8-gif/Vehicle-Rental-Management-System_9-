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
    sendResponse(false, 'Method not allowed.', null, 405);
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    sendResponse(false, 'Invalid request data.', null, 400);
}

$email = strtolower(trim((string)($data['email'] ?? '')));
$password = (string)($data['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    sendResponse(false, 'Enter a valid admin email and password.', null, 400);
}

try {
    $db = getDatabase();
    $users = $db->users;

    // Normal records use lowercase email. Fall back to a normalized scan for
    // legacy records that may contain uppercase letters or accidental spaces.
    $admin = $users->findOne(['email' => $email]);
    if (!$admin) {
        foreach ($users->find([]) as $candidate) {
            if (strtolower(trim((string)($candidate['email'] ?? ''))) === $email) {
                $admin = $candidate;
                break;
            }
        }
    }

    if (!$admin || strtolower(trim((string)($admin['role'] ?? ''))) !== 'admin') {
        sendResponse(false, 'Invalid admin email or password.', null, 401);
    }

    $storedHash = (string)($admin['password'] ?? '');
    if ($storedHash === '' || !password_verify($password, $storedHash)) {
        sendResponse(false, 'Invalid admin email or password.', null, 401);
    }

    if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
        $users->updateOne(
            ['_id' => $admin['_id']],
            ['$set' => ['password' => password_hash($password, PASSWORD_DEFAULT)]]
        );
    }

    session_name('VS_ADMIN');
    session_start();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (string)$admin['_id'];
    $_SESSION['user_name'] = (string)($admin['name'] ?? 'Admin');
    $_SESSION['user_email'] = (string)($admin['email'] ?? $email);
    $_SESSION['user_role'] = 'admin';

    sendResponse(true, 'Admin login successful.', ['role' => 'admin']);
} catch (Throwable $e) {
    error_log('Admin login error: ' . $e->getMessage());
    sendResponse(false, 'Admin login failed because the server could not verify the account.', null, 500);
}
