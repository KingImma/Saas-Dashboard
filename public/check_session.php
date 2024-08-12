<?php
session_start();

require "db.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_role = $_SESSION['user_role'] ?? 0; 

$allowed_roles = [1];

if (!in_array($user_role, $allowed_roles)) {
    header('Location: default_dashboard.php');
    exit();
} else {
    // header('Location: index.php');
    // exit();
}
