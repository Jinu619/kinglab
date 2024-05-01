<?php
$dbhost = 'localhost';
$dbname = 'kingslab';
$dbuser = 'root';
$dbpassword = '';

$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);


function generateCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
