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

// Siparişleri listele
$sql = "SELECT o.OrderID, u.UserName, o.OrderDate, o.TotalAmount, o.Status 
        FROM Orders o 
        JOIN Users u ON o.UserID = u.UserID";
$ordersResult = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Yönetimi</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="dashboard.php">Admin Panel</a></li>
                <li><a href="logout.php">Çıkış Yap</a></li>
            </ul>
        </nav>
    </header>
    <main id="admin-panel">
        <h1>Sipariş Yönetimi</h1>
        <table>
            <thead>
                <tr>
                    <th>Sipariş ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Sipariş Tarihi</th>
                    <th>Toplam Tutar</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $ordersResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['OrderID']; ?></td>
                        <td><?php echo $row['UserName']; ?></td>
                        <td><?php echo $row['OrderDate']; ?></td>
                        <td><?php echo $row['TotalAmount']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>