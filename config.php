<?php
session_start();

$host = "127.0.0.1";
$dbUser = "root";
$dbPass = "";
$dbName = "apexplanet_task4";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Database connection failed. Start MySQL in XAMPP and import database.sql.");
}
$conn->set_charset("utf8mb4");

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function isLoggedIn() {
    return isset($_SESSION["user_id"]);
}

function isAdmin() {
    return isset($_SESSION["role"]) && $_SESSION["role"] === "Admin";
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: dashboard.php");
        exit;
    }
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}
?>