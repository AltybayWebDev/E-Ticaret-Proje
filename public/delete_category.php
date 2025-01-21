<?php
session_start();
include 'db.php';

$categoryID = $_GET['categoryID'] ?? null;

if ($categoryID) {
    $sql = "DELETE FROM Categories WHERE CategoryID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoryID);
    $stmt->execute();
}

header("Location: admin_categories.php");
exit();
?>