<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST['productID'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID]++;
    } else {
        $_SESSION['cart'][$productID] = 1;
    }

    echo "Ürün sepete eklendi.";
}
?>