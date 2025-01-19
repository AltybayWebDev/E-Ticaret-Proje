<?php
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakkımızda</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="aboutus.php" class="active">Hakkımızda</a></li>
                <li><a href="contact.php">İletişim</a></li>
                <?php if (isset($_SESSION['UserName'])): ?>
                    <li><a href="#"><?php echo $_SESSION['UserName']; ?></a></li>
                    <li><a href="cart.php">Sepetim</a></li>
                <?php else: ?>
                    <li><a href="login.php">Giriş Yap</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
    <!-- Hakkımızda Başlığı -->
    <section id="about-us">
        <h2>Hakkımızda</h2>
        <p>OkulShop, öğrencilere yönelik okul malzemeleri ve eğitim gereçleri sunan bir online alışveriş platformudur. Müşterilerimize kaliteli ürünler sunarken, onlara uygun fiyatlarla alışveriş yapma imkanı tanıyoruz. Eğitimde başarıya giden yolda, öğrencilerimizin yanında olmayı hedefliyoruz.</p>
    </section>
</main>

    <footer style="background-color: #f8f8f8; padding: 20px; color: #333;">
        <h3>İletişim</h3>
        <p>Bizimle iletişime geçmek için aşağıdaki bilgileri kullanabilirsiniz:</p>
        <ul>
            <li><strong>Email:</strong> info@okulshop.com</li>
            <li><strong>Telefon:</strong> +90 555 123 45 67</li>
            <li><strong>Adres:</strong> Lefkoşa, Kıbrıs</li>
        </ul>
    </footer>
</body>
</html>