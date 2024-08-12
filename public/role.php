<?php

require "./db.php";

function registerUser($name, $emai, $password, $role_id){
    global $pdo;

    $hashedPassword = password_hash($password,  PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users(name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $emai, $hashedPassword]);
    $user_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO role_user (user_id, role_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $role_id]);
}