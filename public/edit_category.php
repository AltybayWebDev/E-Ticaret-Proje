<?php
session_start();
include 'db.php';

$categoryID = $_GET['categoryID'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST['categoryName'] ?? null;

    if ($categoryName) {
        $sql = "UPDATE Categories SET CategoryName = ? WHERE CategoryID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $categoryName, $categoryID);
        $stmt->execute();
        header("Location: admin_categories.php");
        exit();
    }
}

$sql = "SELECT CategoryName FROM Categories WHERE CategoryID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $categoryID);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Düzenle</title>
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
        <h1>Kategori Düzenle</h1>
        <form method="post" action="">
            <label for="categoryName">Kategori Adı:</label>
            <input type="text" id="categoryName" name="categoryName" value="<?php echo $category['CategoryName']; ?>" required>
            <button type="submit" class="btn btn-warning">Güncelle</button>
        </form>
    </main>
</body>
</html>