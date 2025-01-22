<?php
session_start();
include 'db.php';

$userID = $_SESSION['UserID'] ?? null; // Giriş yapan kullanıcının ID'si
$cart = $_SESSION['cart'] ?? []; // Sepetteki ürünler: [ProductID => Quantity]
$totalPrice = 0;
$finalPrice = 0;
$orderSuccess = false;

if (!$userID || empty($cart)) {
    die("Kullanıcı oturumu yok veya sepet boş!");
}

// Sepet ürünlerini ve toplam fiyatı hesapla
$cartItems = [];
if (!empty($cart)) {
    $productIDs = implode(',', array_keys($cart));
    $sql = "SELECT ProductID, ProductName, Price FROM Products WHERE ProductID IN ($productIDs)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $productID = $row['ProductID'];
        $quantity = $cart[$productID];
        $totalPrice += $row['Price'] * $quantity;
        $cartItems[] = [
            'ProductName' => $row['ProductName'],
            'Quantity' => $quantity,
            'Price' => $row['Price'],
            'Total' => $row['Price'] * $quantity
        ];
    }
}

$finalPrice = $totalPrice;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = $_POST['address'] ?? null;
    $postalCode = $_POST['postalCode'] ?? null;
    $paymentMethod = $_POST['paymentMethod'] ?? null;

    if (!$address || !$postalCode || !$paymentMethod) {
        die("Adres, posta kodu veya ödeme yöntemi eksik!");
    }

    // Adresi kaydet
    $sql = "INSERT INTO Addresses (UserID, Address, PostalCode) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL sorgusu hazırlanırken bir hata oluştu: " . $conn->error);
    }
    $stmt->bind_param("iss", $userID, $address, $postalCode);
    if ($stmt->execute()) {
        $addressID = $stmt->insert_id;

        // Sipariş oluştur
        $sql = "INSERT INTO Orders (UserID, TotalAmount, Status, OrderDate) VALUES (?, ?, 'Pending', NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL sorgusu hazırlanırken bir hata oluştu: " . $conn->error);
        }
        $stmt->bind_param("id", $userID, $finalPrice);
        if ($stmt->execute()) {
            $orderID = $stmt->insert_id;

            // Sepetteki ürünleri OrderDetails tablosuna ekle
            $sql = "INSERT INTO OrderDetails (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("SQL sorgusu hazırlanırken bir hata oluştu: " . $conn->error);
            }

            foreach ($cart as $productID => $quantity) {
                $sqlProduct = "SELECT Price FROM Products WHERE ProductID = ?";
                $stmtProduct = $conn->prepare($sqlProduct);
                if (!$stmtProduct) {
                    die("SQL sorgusu hazırlanırken bir hata oluştu: " . $conn->error);
                }
                $stmtProduct->bind_param("i", $productID);
                $stmtProduct->execute();
                $productResult = $stmtProduct->get_result()->fetch_assoc();
                $productPrice = $productResult['Price'];

                $stmt->bind_param("iiid", $orderID, $productID, $quantity, $productPrice);
                $stmt->execute();
            }

            // Ödeme bilgilerini kaydet
            $sql = "INSERT INTO Payments (OrderID, PaymentMethod, PaymentStatus, PaymentDate) 
                    VALUES (?, ?, 'Paid', NOW())";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("SQL sorgusu hazırlanırken bir hata oluştu: " . $conn->error);
            }
            $stmt->bind_param("is", $orderID, $paymentMethod);
            $stmt->execute();

            // Sipariş başarıyla tamamlandı
            $orderSuccess = true;

            // Sepeti sıfırla
            unset($_SESSION['cart']);
            $successMessage = "Sipariş başarıyla tamamlandı!";
            header("Location: index.php");
        } else {
            $errorMessage = "Sipariş oluşturulurken bir hata oluştu!";
        }
    } else {
        $errorMessage = "Adres kaydedilirken bir hata oluştu!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme</title>
    <link rel="stylesheet" href="css/style.css">
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
                <?php else: ?>
                    <li><a href="login.php">Giriş Yap</a></li>
                <?php endif; ?>
                <li><a href="cart.php">Sepetim</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php if (isset($successMessage)): ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php elseif (isset($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form method="post" action="" class="checkout-form">
            <h2>Ödeme</h2>

            <h3>Adres Bilgileri</h3>
            <textarea name="address" placeholder="Adresinizi girin" required></textarea>
            <label for="postalCode">Posta Kodu:</label>
            <input type="text" id="postalCode" name="postalCode" placeholder="Posta Kodu" required>

            <h3>Ödeme Bilgileri</h3>
            <label for="paymentMethod">Ödeme Yöntemi:</label>
            <select name="paymentMethod" id="paymentMethod" required>
                <option value="Credit Card">Kredi Kartı</option>
                <option value="PayPal">PayPal</option>
                <option value="Bank Transfer">Banka Transferi</option>
            </select>
            <div id="creditCardInfo">
                <label for="cardNumber">Kart Numarası:</label>
                <input type="text" id="cardNumber" name="cardNumber" placeholder="Kart Numarası" required>
                <label for="cardExpiry">Son Kullanma Tarihi:</label>
                <input type="text" id="cardExpiry" name="cardExpiry" placeholder="AA/YY" required>
                <label for="cardCVC">CVC:</label>
                <input type="text" id="cardCVC" name="cardCVC" placeholder="CVC" required>
            </div>

            <h3>Ödenecek Tutar: <span id="finalPrice"><?php echo $finalPrice; ?> TL</span></h3>
            <button type="submit" class="btn btn-warning">Ödemeyi Tamamla</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 E-Ticaret Sitesi. Tüm hakları saklıdır.</p>
    </footer>
    <script>
        document.getElementById('paymentMethod').addEventListener('change', function () {
            const creditCardInfo = document.getElementById('creditCardInfo');
            if (this.value === 'Credit Card') {
                creditCardInfo.style.display = 'block';
            } else {
                creditCardInfo.style.display = 'none';
            }
        });
    </script>
</body>
</html>