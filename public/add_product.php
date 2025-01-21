<?php
session_start();
include 'db.php';

// Kategorileri çek
$sql = "SELECT CategoryID, CategoryName FROM Categories";
$result = $conn->query($sql);
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'] ?? null;
    $description = $_POST['description'] ?? null;
    $price = $_POST['price'] ?? null;
    $stock = $_POST['stock'] ?? null;
    $categoryID = $_POST['categoryID'] ?? null;

    if ($productName && $description && $price && $stock && $categoryID) {
        $sql = "INSERT INTO Products (ProductName, Description, Price, Stock, CategoryID, CreatedAt) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdis", $productName, $description, $price, $stock, $categoryID);
        $stmt->execute();
        header("Location: admin_products.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Ürün Ekle</title>
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
        <h1>Yeni Ürün Ekle</h1>
        <form method="post" action="">
            <label for="productName">Ürün Adı:</label>
            <input type="text" id="productName" name="productName" required>
            <label for="description">Açıklama:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="price">Fiyat:</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <label for="stock">Stok:</label>
            <input type="number" id="stock" name="stock" required>
            <label for="categoryID">Kategori:</label>
            <select id="categoryID" name="categoryID" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['CategoryID']; ?>"><?php echo $category['CategoryName']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-warning">Ekle</button>
        </form>
    </main>
</body>
</html>