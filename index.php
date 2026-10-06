<?php
session_name('VS_CUSTOMER');session_start();
if(isset($_SESSION['user_id']) && ($_SESSION['user_role']??'')==='user'){header('Location: dashboard.php');exit;}

header('Location: login.php');exit;
