<?php
session_start();
include 'db.php';

$userID = $_SESSION['UserID'] ?? null;
$userRole = null;

if ($userID) {
    $sql = "SELECT Role FROM Users WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userRole = $row['Role'];
    }
}

if ($userRole !== 'Admin') {
    header("Location: index.php");
    exit();
}

// Kullanıcıları listele
$sql = "SELECT UserID, UserName, Email, Role FROM Users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetimi</title>
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
        <h1>Kullanıcı Yönetimi</h1>
        <table>
            <thead>
                <tr>
                    <th>Kullanıcı ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['UserID']; ?></td>
                        <td><?php echo $row['UserName']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['Role']; ?></td>
                        <td>
                            <a href="edit_user.php?userID=<?php echo $row['UserID']; ?>">Düzenle</a>
                            <a href="delete_user.php?userID=<?php echo $row['UserID']; ?>" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="add_user.php" class="btn btn-warning">Yeni Kullanıcı Ekle</a>
    </main>
</body>
</html>