<?php
require_once __DIR__ . '/../config/app.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<header>
    <h1><?php echo APP_NAME; ?></h1>

    <nav>
        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
        <a href="<?php echo BASE_URL; ?>/login.php">Login</a>
        <a href="<?php echo BASE_URL; ?>/register.php">Register</a>
    </nav>
</header>

<main>