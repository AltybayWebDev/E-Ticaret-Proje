<?php
session_start();
include 'db.php';

$search = $_GET['search'] ?? '';
$categoryID = $_GET['categoryID'] ?? '';
$minPrice = $_GET['minPrice'] ?? '';
$maxPrice = $_GET['maxPrice'] ?? '';

$sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, pi.Image, c.CategoryName 
        FROM Products p 
        LEFT JOIN ProductImages pi ON p.ProductID = pi.ProductID AND pi.IsMainImage = TRUE
        JOIN Categories c ON p.CategoryID = c.CategoryID 
        WHERE p.ProductName LIKE ?";

$params = ["%$search%"];
$types = "s";

if ($categoryID) {
    $sql .= " AND p.CategoryID = ?";
    $params[] = $categoryID;
    $types .= "i";
}

if ($minPrice) {
    $sql .= " AND p.Price >= ?";
    $params[] = $minPrice;
    $types .= "d";
}

if ($maxPrice) {
    $sql .= " AND p.Price <= ?";
    $params[] = $maxPrice;
    $types .= "d";
}

$sql .= " ORDER BY p.ProductID DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="products.php" class="active">Ürünler</a></li>
                <li><a href="aboutus.php">Hakkımızda</a></li>
                <li><a href="contact.php">İletişim</a></li>
                <?php if (isset($_SESSION['UserName'])): ?>
                    <li><a href="#"><?php echo $_SESSION['UserName']; ?></a></li>
                <?php else: ?>
                    <li><a href="login.php">Giriş Yap</a></li>
                <?php endif; ?>
                <li><a href="cart.php">Sepetim</a></li> 
            </ul>
        </nav>
    </header>
    <main>
        <h1>Ürünler</h1>
        <form method="get" action="products.php" class="filter-form">
            <input type="text" name="search" placeholder="Ürün Ara" value="<?php echo htmlspecialchars($search); ?>">
            <select name="categoryID">
                <option value="">Kategori Seç</option>
                <?php
                $categorySql = "SELECT CategoryID, CategoryName FROM Categories";
                $categoryResult = $conn->query($categorySql);
                while ($categoryRow = $categoryResult->fetch_assoc()):
                ?>
                    <option value="<?php echo $categoryRow['CategoryID']; ?>" <?php if ($categoryRow['CategoryID'] == $categoryID) echo 'selected'; ?>><?php echo $categoryRow['CategoryName']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="minPrice" placeholder="Min Fiyat" value="<?php echo htmlspecialchars($minPrice); ?>">
            <input type="number" name="maxPrice" placeholder="Max Fiyat" value="<?php echo htmlspecialchars($maxPrice); ?>">
            <button type="submit" class="btn btn-warning">Filtrele</button>
        </form>
        <div class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-item" data-id="<?php echo $row['ProductID']; ?>" style="height: 450px;"> <!-- Inline stil eklendi -->
                        <a href="product_details.php?productID=<?php echo $row['ProductID']; ?>" class="product-link">
                            <div class="product-image">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Image']); ?>" alt="<?php echo $row['ProductName']; ?>">
                            </div>
                            <div class="product-content">
                                <h3><?php echo $row['ProductName']; ?></h3>
                                <p>Fiyat: <?php echo $row['Price']; ?> ₺</p>
                            </div>
                        </a>
                        <button class="add-to-cart" data-id="<?php echo $row['ProductID']; ?>">Sepete Ekle</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Ürün bulunamadı.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>