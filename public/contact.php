<?php
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="aboutus.php">Hakkımızda</a></li>
                <li><a href="contact.php" class="active">İletişim</a></li>
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
        <form method="post" action="https://formspree.io/f/mkgnegrw">
            <h2>İletişim</h2>
            <label for="name">Adınız:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="message">Mesajınız:</label>
            <textarea id="message" name="message" required></textarea>
            <button type="submit" class="btn btn-warning">Gönder</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 E-Ticaret Sitesi. Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>