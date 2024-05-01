<?php


include('./common/connection.php');
if (!$_POST['csrf_token']) header('location:product.php?errmsg=invalid csrf token');

$name = $_POST['name'];
$price = $_POST['price'];
$mode = $_POST['mode'];
$date = date('Y-m-d H:i:s');



if (!$name || !$price) header('location:product.php?errmsg=fill mandatory fields');

if ($mode != 'edit') {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT id FROM product WHERE name = :name AND deleted_at is null");
        $stmt->execute(['name' => $name]);

        if ($stmt->rowCount() > 0) header('location:product.php?errmsg=Duplicate data');

        $stmt = $pdo->prepare("INSERT INTO product (name, price) VALUES (:name, :price)");
        $stmt->execute(['name' => $name, 'price' => $price]);

        header('location:product.php?msg=Succesfully added');
    } catch (\Throwable $th) {
        header("Location: product.php?errmsg=Failed! please try again");
    }
} else {
    $pid = $_POST['pid'];
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :pid AND deleted_at IS NULL");
    $stmt->execute(['pid' => $pid]);
    if ($stmt->rowCount() <= 0) header('location:product.php?msg=invalid product');

    $updateStmt = $pdo->prepare("UPDATE product SET name = :name, price = :price,updated_at = :date WHERE id = :pid");
    $updateStmt->execute(['name' => $name, 'price' => $price, 'pid' => $pid, 'date' => $date]);

    if ($updateStmt->rowCount() > 0) header('location: product.php?msg=update successful');
    else  header('location: product.php?msg=update failed');
}
