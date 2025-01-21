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

// Ürünleri listele
$sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, p.Stock, c.CategoryName, p.CreatedAt 
        FROM Products p 
        JOIN Categories c ON p.CategoryID = c.CategoryID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Yönetimi</title>
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
    <main>
        <h1>Ürün Yönetimi</h1>
        <table>
            <thead>
                <tr>
                    <th>Ürün ID</th>
                    <th>Ürün Adı</th>
                    <th>Açıklama</th>
                    <th>Fiyat</th>
                    <th>Stok</th>
                    <th>Kategori Adı</th>
                    <th>Oluşturulma Tarihi</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['ProductID']; ?></td>
                        <td><?php echo $row['ProductName']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['Price']; ?></td>
                        <td><?php echo $row['Stock']; ?></td>
                        <td><?php echo $row['CategoryName']; ?></td>
                        <td><?php echo $row['CreatedAt']; ?></td>
                        <td>
                            <a href="edit_product.php?productID=<?php echo $row['ProductID']; ?>">Düzenle</a>
                            <a href="delete_product.php?productID=<?php echo $row['ProductID']; ?>" onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="add_product.php" class="btn btn-warning">Yeni Ürün Ekle</a>
    </main>
</body>
</html>