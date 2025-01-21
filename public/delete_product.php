<?php
session_start();
include 'db.php';

$productID = $_GET['productID'] ?? null;

if ($productID) {
    $sql = "DELETE FROM Products WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
}

header("Location: admin_products.php");
exit();
?>