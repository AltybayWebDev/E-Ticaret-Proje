<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST['categoryName'] ?? null;

    if ($categoryName) {
        $sql = "INSERT INTO Categories (CategoryName) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        header("Location: admin_categories.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Kategori Ekle</title>
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
        <h1>Yeni Kategori Ekle</h1>
        <form method="post" action="">
            <label for="categoryName">Kategori Adı:</label>
            <input type="text" id="categoryName" name="categoryName" required>
            <button type="submit" class="btn btn-warning">Ekle</button>
        </form>
    </main>
</body>
</html>