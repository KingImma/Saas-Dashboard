<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare('
        SELECT u.*, r.id as role_id, r.role_name 
        FROM users u
        LEFT JOIN user_roles ur ON u.id = ur.user_id
        LEFT JOIN roles r ON ur.role_id = r.id
        WHERE u.email = :email
    ');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role_id'] ?? 0;
        $_SESSION['role_name'] = $user['role_name'] ?? 'no_role';
        
        switch ($_SESSION['user_role']) {
            case 1: 
                header('Location: index.php');
                break;
            case 2: 
                header('Location: seller_dashboard.php');
                break;
            case 3: 
                header('Location: user_dashboard.php');
                break;
            default:
                header('Location: default_dashboard.php');
                break;
        }
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid credentials";
        header('Location: login.html');
        exit();
    }
} else {
    header('Location: login.html');
    exit();
}