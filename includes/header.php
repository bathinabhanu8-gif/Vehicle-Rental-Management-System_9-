<?php
require_once __DIR__ . '/../config/app.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title><?php echo APP_NAME; ?></title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css"></head><body>
<header class="site-header"><a class="brand" href="<?php echo BASE_URL; ?>/index.php"><span class="brand-mark">VS</span><span>VS Travells<small>Drive your journey</small></span></a><button class="nav-toggle" aria-label="Menu">☰</button><nav class="main-nav"><a href="<?php echo BASE_URL; ?>/index.php">Vehicles</a><?php if($isLoggedIn && !$isAdmin): ?><a href="<?php echo BASE_URL; ?>/dashboard.php">Dashboard</a><a href="<?php echo BASE_URL; ?>/bookings.php">My Bookings</a><a href="<?php echo BASE_URL; ?>/profile.php">Profile</a><?php elseif($isAdmin): ?><a href="<?php echo BASE_URL; ?>/admin/index.php">Admin Dashboard</a><?php endif; ?><?php if($isLoggedIn): ?><span class="nav-user">Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span><a class="nav-cta ghost" href="<?php echo BASE_URL; ?>/logout.php">Logout</a><?php else: ?><a href="<?php echo BASE_URL; ?>/login.php">Login</a><a class="nav-cta" href="<?php echo BASE_URL; ?>/register.php">Create account</a><?php endif; ?></nav></header><main>
