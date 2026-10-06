<?php
function startNamedSession(string $name): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_name($name);
    session_start();
}
function startCustomerSession(): void { startNamedSession('VS_CUSTOMER'); }
function startAdminSession(): void { startNamedSession('VS_ADMIN'); }
function requireCustomer(){
    startCustomerSession();
    if(!isset($_SESSION['user_id'])) sendResponse(false,'Unauthorized. Please login first.',null,401);
    if(($_SESSION['user_role']??'')!=='user') sendResponse(false,'Customer access only.',null,403);
}
function requireAdmin(){
    startAdminSession();
    if(!isset($_SESSION['user_id'])) sendResponse(false,'Unauthorized. Admin login required.',null,401);
    if(($_SESSION['user_role']??'')!=='admin') sendResponse(false,'Forbidden. Admin access required.',null,403);
}
