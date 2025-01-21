<?php
session_start();
include 'db.php';

$imageID = $_GET['imageID'] ?? null;

// Ürünleri çek
$sql = "SELECT ProductID, ProductName FROM Products";
$result = $conn->query($sql);
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST['productID'] ?? null;
    $isMainImage = isset($_POST['isMainImage']) ? 1 : 0;
    $image = $_FILES['image']['tmp_name'] ?? null;

    if ($productID) {
        if ($image) {
            $imageData = file_get_contents($image);
            $sql = "UPDATE ProductImages SET ProductID = ?, Image = ?, IsMainImage = ? WHERE ImageID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $productID, $imageData, $isMainImage, $imageID);
        } else {
            $sql = "UPDATE ProductImages SET ProductID = ?, IsMainImage = ? WHERE ImageID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $productID, $isMainImage, $imageID);
        }
        $stmt->execute();
        header("Location: admin_productimages.php");
        exit();
    }
}

$sql = "SELECT ProductID, IsMainImage FROM ProductImages WHERE ImageID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $imageID);
$stmt->execute();
$result = $stmt->get_result();
$image = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resim Düzenle</title>
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
        <h1>Resim Düzenle</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="productID">Ürün:</label>
            <select id="productID" name="productID" required>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['ProductID']; ?>" <?php if ($product['ProductID'] == $image['ProductID']) echo 'selected'; ?>><?php echo $product['ProductName']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="image">Resim:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <label for="isMainImage">Ana Resim:</label>
            <input type="checkbox" id="isMainImage" name="isMainImage" <?php if ($image['IsMainImage']) echo 'checked'; ?>>
            <button type="submit" class="btn btn-warning">Güncelle</button>
        </form>
    </main>
</body>
</html>