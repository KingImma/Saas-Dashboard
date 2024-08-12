<?php

require "/xamp3/htdocs/Task for dashboard/public/db.php";

$initial_password = 'defaultpassword123';
$hashedpassword = password_hash($initial_password, PASSWORD_DEFAULT);

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role_id = $_POST['role'];

    try{
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedpassword]);

        $user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $role_id]);

        $pdo->commit();

        header("Location: ../index.php");
        exit();
    }catch(Exception $e){
        $pdo->rollBack();
        echo "Failed to add new user:" . $e->getMessage();
    }
}