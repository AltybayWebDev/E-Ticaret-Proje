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

// Sipariş detaylarını listele
$sql = "SELECT od.OrderDetailID, od.OrderID, p.ProductName, od.Quantity, od.Price 
        FROM OrderDetails od 
        JOIN Products p ON od.ProductID = p.ProductID";
$orderDetailsResult = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Detayları Yönetimi</title>
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
        <h1>Sipariş Detayları Yönetimi</h1>
        <table>
            <thead>
                <tr>
                    <th>Sipariş Detay ID</th>
                    <th>Sipariş ID</th>
                    <th>Ürün Adı</th>
                    <th>Miktar</th>
                    <th>Fiyat</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $orderDetailsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['OrderDetailID']; ?></td>
                        <td><?php echo $row['OrderID']; ?></td>
                        <td><?php echo $row['ProductName']; ?></td>
                        <td><?php echo $row['Quantity']; ?></td>
                        <td><?php echo $row['Price']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>