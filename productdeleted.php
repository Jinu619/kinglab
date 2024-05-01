<?php

include('./common/connection.php');
$pid = $_GET['pid'];

$stmt1 = $pdo->prepare("SELECT id FROM product WHERE id=:pid AND deleted_at is null");
$stmt1->execute(['pid' => $pid]);
$prduct = $stmt1->fetch(PDO::FETCH_OBJ);
$date = date('Y-m-d H:i:s');

if (!$prduct) header('location: product.php?msg=Invalid product');

$updateStmt = $pdo->prepare("UPDATE product SET deleted_at = :date WHERE id = :pid");
$updateStmt->execute(['date' => $date, 'pid' => $pid]);

if ($updateStmt->rowCount() > 0) header('location: product.php?msg=Deleted successful');
else  header('location: product.php?msg=Deleted failed');
