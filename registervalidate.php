<?php
include('common/connection.php');

$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];
$role = $_POST['role'];
$uname = $_POST['uname'];


if (!$_POST['csrf_token']) header('location:register.php?errmsg=invalid csrf token');

if (!$email || !$name || !$password || !$uname || !$role) header('location:register.php?errmsg=All fields are required');

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :uname OR email = :email");
    $stmt->execute(['uname' => $uname, 'email' => $email]);

    if ($stmt->rowCount() > 0) header('location:register.php?errmsg=Duplicate data');


    $stmt = $pdo->prepare("INSERT INTO users (name, email, username, password,role) VALUES (:name, :email, :uname, :hashedPassword,:role)");
    $stmt->execute(['name' => $name, 'email' => $email, 'uname' => $uname, 'hashedPassword' => $hashedPassword, 'role' => $role]);

    header('location:login.php?msg=Succesfully registerd');
} catch (PDOException $e) {
    header('location:register.php?errmsg=Failed! PLease try again');
}
