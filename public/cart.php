<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST['productID'];
    $action = $_POST['action'];

    if ($action == "increase") {
        $_SESSION['cart'][$productID]++;
    } elseif ($action == "decrease") {
        if ($_SESSION['cart'][$productID] > 1) {
            $_SESSION['cart'][$productID]--;
        } else {
            unset($_SESSION['cart'][$productID]);
        }
    }
}

$totalPrice = 0;
$cartItems = array();

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $productIDs = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT p.ProductID, p.ProductName, p.Price, pi.Image 
            FROM Products p 
            LEFT JOIN ProductImages pi ON p.ProductID = pi.ProductID AND pi.IsMainImage = TRUE 
            WHERE p.ProductID IN ($productIDs)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $productID = $row['ProductID'];
        $quantity = $_SESSION['cart'][$productID];
        $totalPrice += $row['Price'] * $quantity;
        $cartItems[] = array(
            'ProductID' => $productID,
            'ProductName' => $row['ProductName'],
            'Price' => $row['Price'],
            'Quantity' => $quantity,
            'Image' => $row['Image']
        );
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".update-cart").click(function() {
                var productID = $(this).data("id");
                var action = $(this).data("action");
                $.ajax({
                    url: "cart.php",
                    type: "POST",
                    data: { productID: productID, action: action },
                    success: function(response) {
                        location.reload();
                    }
                });
            });
        });
    </script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="aboutus.php">Hakkımızda</a></li>
                <li><a href="contact.php">İletişim</a></li>
                <?php if (isset($_SESSION['UserName'])): ?>
                    <li><a href="#"><?php echo $_SESSION['UserName']; ?></a></li>
                    <li><a href="cart.php" class="active">Sepetim</a></li>
                <?php else: ?>
                    <li><a href="login.php">Giriş Yap</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
    <h2>Sepetim</h2>
    <?php if (!empty($cartItems)): ?>
        <div class="cart-container">
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <div class="product-image">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($item['Image']); ?>" alt="<?php echo $item['ProductName']; ?>">
                    </div>
                    <div class="product-name"><?php echo $item['ProductName']; ?></div>
                    <div class="product-price"><?php echo $item['Price']; ?> TL</div>
                    <div class="product-quantity">
                        <button class="update-cart" data-id="<?php echo $item['ProductID']; ?>" data-action="decrease">-</button>
                        <span><?php echo $item['Quantity']; ?></span>
                        <button class="update-cart" data-id="<?php echo $item['ProductID']; ?>" data-action="increase">+</button>
                    </div>
                    <div class="product-total"><?php echo $item['Price'] * $item['Quantity']; ?> TL</div>
                </div>
            <?php endforeach; ?>
        </div>
        <h3>Toplam Fiyat: <?php echo $totalPrice; ?> TL</h3>
    <?php else: ?>
        <p>Sepetiniz boş.</p>
    <?php endif; ?>
    </main>
</body>
</html>