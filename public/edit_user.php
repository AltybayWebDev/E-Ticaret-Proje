<?php
session_start();
include 'db.php';

$userID = $_GET['userID'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['userName'] ?? null;
    $email = $_POST['email'] ?? null;
    $role = $_POST['role'] ?? null;

    if ($userName && $email && $role) {
        $sql = "UPDATE Users SET UserName = ?, Email = ?, Role = ? WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $userName, $email, $role, $userID);
        $stmt->execute();
        header("Location: admin_users.php");
        exit();
    }
}

$sql = "SELECT UserName, Email, Role FROM Users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Düzenle</title>
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
        <h1>Kullanıcı Düzenle</h1>
        <form method="post" action="">
            <label for="userName">Kullanıcı Adı:</label>
            <input type="text" id="userName" name="userName" value="<?php echo $user['UserName']; ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
            <label for="role">Rol:</label>
            <select id="role" name="role" required>
                <option value="Customer" <?php if ($user['Role'] == 'Customer') echo 'selected'; ?>>Customer</option>
                <option value="Admin" <?php if ($user['Role'] == 'Admin') echo 'selected'; ?>>Admin</option>
            </select>
            <button type="submit" class="btn btn-warning">Güncelle</button>
        </form>
    </main>
</body>
</html>