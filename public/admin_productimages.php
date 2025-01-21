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

// Ürün resimlerini listele
$sql = "SELECT ImageID, ProductID, Image, IsMainImage FROM ProductImages";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Resimleri Yönetimi</title>
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
        <h1>Ürün Resimleri Yönetimi</h1>
        <table>
            <thead>
                <tr>
                    <th>Resim ID</th>
                    <th>Ürün ID</th>
                    <th>Resim</th>
                    <th>Ana Resim</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['ImageID']; ?></td>
                        <td><?php echo $row['ProductID']; ?></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['Image']); ?>" alt="Ürün Resmi" width="100"></td>
                        <td><?php echo $row['IsMainImage'] ? 'Evet' : 'Hayır'; ?></td>
                        <td>
                            <a href="edit_productimage.php?imageID=<?php echo $row['ImageID']; ?>">Düzenle</a>
                            <a href="delete_productimage.php?imageID=<?php echo $row['ImageID']; ?>" onclick="return confirm('Bu resmi silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="add_productimage.php" class="btn btn-warning">Yeni Resim Ekle</a>
    </main>
</body>
</html>