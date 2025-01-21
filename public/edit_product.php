<?php
session_start();
include 'db.php';

$productID = $_GET['productID'] ?? null;

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
        $sql = "UPDATE Products SET ProductName = ?, Description = ?, Price = ?, Stock = ?, CategoryID = ? WHERE ProductID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdisi", $productName, $description, $price, $stock, $categoryID, $productID);
        $stmt->execute();
        header("Location: admin_products.php");
        exit();
    }
}

$sql = "SELECT ProductName, Description, Price, Stock, CategoryID FROM Products WHERE ProductID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Düzenle</title>
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
        <h1>Ürün Düzenle</h1>
        <form method="post" action="">
            <label for="productName">Ürün Adı:</label>
            <input type="text" id="productName" name="productName" value="<?php echo $product['ProductName']; ?>" required>
            <label for="description">Açıklama:</label>
            <textarea id="description" name="description" required><?php echo $product['Description']; ?></textarea>
            <label for="price">Fiyat:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['Price']; ?>" required>
            <label for="stock">Stok:</label>
            <input type="number" id="stock" name="stock" value="<?php echo $product['Stock']; ?>" required>
            <label for="categoryID">Kategori:</label>
            <select id="categoryID" name="categoryID" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['CategoryID']; ?>" <?php if ($category['CategoryID'] == $product['CategoryID']) echo 'selected'; ?>><?php echo $category['CategoryName']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-warning" class="btn btn-warning">Güncelle</button>
        </form>
    </main>
</body>
</html>