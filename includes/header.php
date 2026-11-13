<?php
require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name('VS_CUSTOMER');
    session_start();
}

$isCustomer = isset($_SESSION['user_id']) && (($_SESSION['user_role'] ?? '') === 'user');
$currentScript = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Cloud Vehicle Rental & Algorithm Optimization</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="<?php echo BASE_URL; ?>/index.php">
            <span class="brand-mark">VS</span>
            <div>
                <span>VS TRAVELLS</span>
                <small>Cloud & Algorithm Optimized</small>
            </div>
        </a>

        <button class="nav-toggle" aria-label="Toggle navigation" onclick="toggleNav()">☰</button>

        <nav class="main-nav" id="mainNav">
            <a href="<?php echo BASE_URL; ?>/index.php" class="<?php echo $currentScript === 'index.php' ? 'active' : ''; ?>">Home</a>
            <a href="<?php echo BASE_URL; ?>/vehicles.php" class="<?php echo $currentScript === 'vehicles.php' ? 'active' : ''; ?>">Fleet</a>
            <a href="<?php echo BASE_URL; ?>/algorithms.php" class="algo-pill <?php echo $currentScript === 'algorithms.php' ? 'active' : ''; ?>">
                ⚡ DAA Algorithm Lab
            </a>

            <?php if ($isCustomer): ?>
                <a href="<?php echo BASE_URL; ?>/dashboard.php" class="<?php echo $currentScript === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/bookings.php" class="<?php echo $currentScript === 'bookings.php' ? 'active' : ''; ?>">My Bookings</a>
                <a href="<?php echo BASE_URL; ?>/profile.php" class="<?php echo $currentScript === 'profile.php' ? 'active' : ''; ?>">Profile</a>
                <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Customer'); ?></span>
                <a class="nav-cta ghost" href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/login.php" class="<?php echo $currentScript === 'login.php' ? 'active' : ''; ?>">Customer Login</a>
                <a class="nav-cta" href="<?php echo BASE_URL; ?>/register.php">Register</a>
                <a class="nav-cta admin-badge" href="<?php echo BASE_URL; ?>/admin/login.php">Admin Portal</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
