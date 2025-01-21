<?php
session_start();
include 'db.php';

$userID = $_SESSION['UserID'] ?? null;
$userRole = null;

if ($userID) {
    $sql = "SELECT Role FROM Users WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userRole = $row['Role'];
    }
}

if ($userRole !== 'Admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="logout.php">Çıkış Yap</a></li>
            </ul>
        </nav>
    </header>
    <main id="admin-panel">
        <h1>Admin Panel</h1>
        <ul>
            <li><a href="admin_users.php" class="btn btn-warning">Kullanıcı Yönetimi</a></li>
            <li><a href="admin_products.php" class="btn btn-warning">Ürün Yönetimi</a></li>
            <li><a href="admin_categories.php" class="btn btn-warning">Kategori Yönetimi</a></li>
            <li><a href="admin_addresses.php" class="btn btn-warning">Adres Yönetimi</a></li>
            <li><a href="admin_orderdetails.php" class="btn btn-warning">Sipariş Detayları Yönetimi</a></li>
            <li><a href="admin_orders.php" class="btn btn-warning">Sipariş Yönetimi</a></li>
        </ul>
    </main>
</body>
</html>