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

// Kategorileri listele
$sql = "SELECT CategoryID, CategoryName FROM Categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Yönetimi</title>
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
        <h1>Kategori Yönetimi</h1>
        <table>
            <thead>
                <tr>
                    <th>Kategori ID</th>
                    <th>Kategori Adı</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['CategoryID']; ?></td>
                        <td><?php echo $row['CategoryName']; ?></td>
                        <td>
                            <a href="edit_category.php?categoryID=<?php echo $row['CategoryID']; ?>">Düzenle</a>
                            <a href="delete_category.php?categoryID=<?php echo $row['CategoryID']; ?>" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="add_category.php" class="btn btn-warning">Yeni Kategori Ekle</a>
    </main>
</body>
</html>