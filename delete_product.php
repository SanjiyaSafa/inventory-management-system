<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: products.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {

    $stmt = $conn->prepare(
        "DELETE FROM products WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->close();
}

header("Location: products.php");

exit;

?>