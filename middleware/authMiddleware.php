<?php

function requireLogin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        sendResponse(
            false,
            'Unauthorized. Please login first.',
            null,
            401
        );
    }
}