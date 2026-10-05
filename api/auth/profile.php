<?php

require_once '../../middleware/jsonMiddleware.php';
require_once '../../middleware/requestLogger.php';
require_once '../../middleware/corsMiddleware.php';
require_once '../../utils/response.php';

setJsonResponse();
handleCors();
logRequest();

session_start();

if (!isset($_SESSION['user_id'])) {
    sendResponse(
        false,
        'Unauthorized. Please login first.',
        null,
        401
    );
}

sendResponse(
    true,
    'Profile accessed successfully',
    [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role']
    ]
);