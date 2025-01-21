<?php
session_start();
include 'db.php';

$userID = $_GET['userID'] ?? null;

if ($userID) {
    $sql = "DELETE FROM Users WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
}

header("Location: admin_users.php");
exit();
?>