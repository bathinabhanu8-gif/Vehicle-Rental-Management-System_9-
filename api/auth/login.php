<?php
require_once '../../middleware/jsonMiddleware.php';require_once '../../middleware/requestLogger.php';require_once '../../middleware/corsMiddleware.php';require_once '../../utils/response.php';require_once '../../config/database.php';
setJsonResponse();handleCors();logRequest();
if($_SERVER['REQUEST_METHOD']!=='POST')sendResponse(false,'Method not allowed.',null,405);
$d=json_decode(file_get_contents('php://input'),true);$email=strtolower(trim($d['email']??''));$password=(string)($d['password']??'');
if(!filter_var($email,FILTER_VALIDATE_EMAIL)||$password==='')sendResponse(false,'Enter your registered email and password.',null,400);
try{
 $u=getDatabase()->users->findOne(['email'=>$email]);
 if(!$u)sendResponse(false,'No customer account was found with this email. Please create an account first.',null,401);
 if(($u['role']??'user')!=='user')sendResponse(false,'This is an administrator account. Please use the separate Admin Login.',null,403);
 $stored=(string)($u['password']??'');
 $valid=$stored!==''&&password_verify($password,$stored);
 // One-time compatibility for old development records that were accidentally stored as plain text.
 if(!$valid && $stored!=='' && (password_get_info($stored)['algoName']??'unknown')==='unknown' && hash_equals($stored,$password)){ $valid=true; $stored=password_hash($password,PASSWORD_DEFAULT); getDatabase()->users->updateOne(['_id'=>$u['_id']],['$set'=>['password'=>$stored]]); }
 if(!$valid)sendResponse(false,'Incorrect password. Please use the same password you created during registration.',null,401);
 if(password_needs_rehash($stored,PASSWORD_DEFAULT)) getDatabase()->users->updateOne(['_id'=>$u['_id']],['$set'=>['password'=>password_hash($password,PASSWORD_DEFAULT)]]);
 session_name('VS_CUSTOMER');session_start();session_regenerate_id(true);$_SESSION['user_id']=(string)$u['_id'];$_SESSION['user_name']=(string)$u['name'];$_SESSION['user_email']=(string)$u['email'];$_SESSION['user_role']='user';
 sendResponse(true,'Customer login successful.',['role'=>'user']);
}catch(Throwable $e){error_log('Customer login: '.$e->getMessage());sendResponse(false,'Login failed because the server could not verify your account.',null,500);}
