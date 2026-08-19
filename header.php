<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inventory Management System</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar">

    <div class="logo">
        Inventory Management System
    </div>

    <div class="nav-links">

        <a href="dashboard.php">Dashboard</a>

        <a href="products.php">Products</a>

        <a href="stock.php">Stock</a>

        <a href="stock_history.php">History</a>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="users.php">Users</a>
        <?php endif; ?>

        <a href="logout.php">Logout</a>

    </div>

</nav>

<div class="container">