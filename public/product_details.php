<?php
include 'db.php';
session_start();

if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    $sql = "SELECT p.ProductName, p.Description, p.Price, pi.Image 
            FROM Products p 
            LEFT JOIN ProductImages pi ON p.ProductID = pi.ProductID AND pi.IsMainImage = TRUE 
            WHERE p.ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Ürün bulunamadı.";
        exit;
    }
} else {
    echo "Geçersiz ürün.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['ProductName']; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="aboutus.html">Hakkımızda</a></li>
                <li><a href="contact.html">İletişim</a></li>
                <?php if (isset($_SESSION['UserName'])): ?>
                    <li><a href="#"><?php echo $_SESSION['UserName']; ?></a></li>
                    <li><a href="cart.php">Sepetim</a></li>
                <?php else: ?>
                    <li><a href="login.php">Giriş Yap</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main class="product-main">
        <h1><?php echo $product['ProductName']; ?></h1>
        <div class="product-details">
            <div class="product-image">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['Image']); ?>" alt="<?php echo $product['ProductName']; ?>">
            </div>
            <div class="product-info">
                <p><?php echo $product['Description']; ?></p>
                <p>Fiyat: <?php echo $product['Price']; ?> TL</p>
                <button class="add-to-cart btn btn-warning" data-id="<?php echo $productID; ?>">Sepete Ekle</button>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 E-Ticaret Sitesi. Tüm hakları saklıdır.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Sepete Ekle butonuna tıklama olayı
        $(".add-to-cart").click(function() {
            var productID = $(this).data("id"); // Ürün ID'sini al
            $.ajax({
                url: "add_to_cart.php", // Sepete ekleme işlemini yapacak PHP dosyası
                type: "POST",
                data: { productID: productID },
                success: function(response) {
                    alert(response); // Sepete ürün eklendi mesajı
                }
            });
        });
    });
</script>
</body>
</html>
