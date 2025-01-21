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

// Adresleri listele
$sql = "SELECT a.AddressID, u.UserName, a.Address, a.PostalCode 
        FROM Addresses a 
        JOIN Users u ON a.UserID = u.UserID";
$addressesResult = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adres Yönetimi</title>
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
    <main id="admin-panel">
        <h1>Adres Yönetimi</h1>
        <table>
            <thead>
                <tr>
                    <th>Adres ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Adres</th>
                    <th>Posta Kodu</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $addressesResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['AddressID']; ?></td>
                        <td><?php echo $row['UserName']; ?></td>
                        <td><?php echo $row['Address']; ?></td>
                        <td><?php echo $row['PostalCode']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>