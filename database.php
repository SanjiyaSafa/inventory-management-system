<?php

$host = "sql306.infinityfree.com";
$username = "if0_42697175";
$password = "Sa12fa45";
$database = "if0_42697175_XXX";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");

?>
