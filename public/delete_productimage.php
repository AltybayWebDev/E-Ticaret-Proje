<?php
session_start();
include 'db.php';

$imageID = $_GET['imageID'] ?? null;

if ($imageID) {
    $sql = "DELETE FROM ProductImages WHERE ImageID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $imageID);
    $stmt->execute();
}

header("Location: admin_productimages.php");
exit();
?>