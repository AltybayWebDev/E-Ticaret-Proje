<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['userName'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $role = $_POST['role'] ?? null;

    if ($userName && $email && $password && $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Users (UserName, Email, Password, Role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $userName, $email, $hashedPassword, $role);
        $stmt->execute();
        header("Location: admin_users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Kullanıcı Ekle</title>
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
        <h1>Yeni Kullanıcı Ekle</h1>
        <form method="post" action="">
            <label for="userName">Kullanıcı Adı:</label>
            <input type="text" id="userName" name="userName" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required>
            <label for="role">Rol:</label>
            <select id="role" name="role" required>
                <option value="Customer">Customer</option>
                <option value="Admin">Admin</option>
            </select>
            <button type="submit" class="btn btn-warning">Ekle</button>
        </form>
    </main>
</body>
</html>