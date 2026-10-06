<?php
function startAppSession()
{
    if (session_status() === PHP_SESSION_NONE) session_start();
}
function requireLogin()
{
    startAppSession();
    if (!isset($_SESSION['user_id'])) sendResponse(false, 'Unauthorized. Please login first.', null, 401);
}
function requireAdmin()
{
    requireLogin();
    if (($_SESSION['user_role'] ?? '') !== 'admin') sendResponse(false, 'Forbidden. Admin access required.', null, 403);
}
