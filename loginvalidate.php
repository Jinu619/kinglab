<?php
include('./common/connection.php');

if (!$_POST['csrf_token']) header('location:login.php?errmsg=invalid csrf token');

$uname = $_POST['uname'];
$password = $_POST['password'];

if (!$uname || !$password) header('location:login.php?errmsg=fill mandatory fields');

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :uname");
    $stmt->execute(['uname' => $uname]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_start();


        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        // print_r($_SESSION);
        // exit;
        header("Location: index.php");
        exit();
    } else {
        header("Location: login.php?errmsg=invalid username or password");
    }
} catch (\Throwable $th) {
    header("Location: login.php?errmsg=Failed! please try again");
}
