<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            // Giriş başarılı, oturumu başlat
            $_SESSION['UserID'] = $row['UserID'];
            $_SESSION['UserName'] = $row['UserName'];
            header("Location: index.php");
            exit();
        } else {
            echo "Yanlış şifre!";
        }
    } else {
        echo "Kullanıcı bulunamadı!";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="post" action="">
    <h2>Giriş Yap</h2>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Şifre:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" class="btn btn-warning">Giriş Yap</button>
        <a href="index.php" class="btn btn-info td-none">Anasayfa</a>
        <a href="register.php" class="links">Kayıt Yap</a>
    </form>
</body>
</html>