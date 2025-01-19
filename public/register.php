<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO Users (UserName, Email, Password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "Kayıt başarılı!";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="post" action="">
    <h2>Kayıt Ol</h2>
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Şifre:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" class="btn btn-warning">Kayıt Ol</button>
        <a href="index.php" class="btn btn-info td-none">Anasayfa</a>
        <a href="login.php" class="links">Giriş Yap</a>
    </form>
</body>
</html>