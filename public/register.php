<?php

require "./db.php";

function hashPassword($password){
    return password_hash($password, PASSWORD_DEFAULT);
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = $_POST['role'];

    $hashedpassword = hashPassword($password);

    try{
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedpassword]);

        $user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $role_id]);

        $pdo->commit();

        echo "User Registered successfully";
        header("Location: index.php");
        exit();
    }catch(Exception $e){
        $pdo->rollBack();
        echo "Failed to register user:" . $e->getMessage();
    }
}