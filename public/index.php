<?php
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OkulShop</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body>
    <header>
        <nav>
            <div class="container">
            <div>
                <a href="index.php"><h3>OkulShop</h3></a>
            </div>
            <div>
                <input type="text" placeholder="Ürün Ara"><button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <ul>
                <li><a href="aboutus.php">Hakkımızda</a></li>
                <li><a href="contact.php">İletişim</a></li>
                <?php if (isset($_SESSION['UserName'])): ?>
                    <li><a href="#"><?php echo $_SESSION['UserName']; ?></a></li>
                    <li><a href="cart.php">Sepetim</a></li>
                <?php else: ?>
                    <li><a href="login.php">Giriş Yap</a></li>
                <?php endif; ?>
            </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="products">
            <h2>Kırtasiye Eşyaları</h2>
            <div class="product-slider">
                <?php
                $sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, pi.Image 
                        FROM Products p 
                        LEFT JOIN ProductImages pi ON p.ProductID = pi.ProductID AND pi.IsMainImage = TRUE
                        WHERE p.CategoryID = 8
                        ORDER BY p.ProductID DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-item" data-id="' . $row["ProductID"] . '">';
                        echo '<a href="product_details.php?productID=' . $row["ProductID"] . '" class="product-link">';
                        echo '<div class="product-image">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row["Image"]) . '" alt="' . $row["ProductName"] . '">';
                        echo '</div>';
                        echo '<div class="product-content">';
                        echo '<h3>' . $row["ProductName"] . '</h3>';
                        echo '<p>Fiyat: ' . $row["Price"] . ' ₺</p>';
                        echo '</div>';
                        echo '</a>';
                        echo '<button class="add-to-cart" data-id="' . $row["ProductID"] . '">Sepete Ekle</button>';
                        echo '</div>';
                    }
                } else {
                    echo "Ürün bulunamadı.";
                }
                ?>
        </section>
        <!-- kampanya resmi -->
        <section class="campaign">
            <div class="container">
            <div class="campaign-container">
                <img src="img/7c912d6a-0e2b-4a9b-b826-3343eef19933.png" alt="Kampanya">
            </div>
            </div>
        </section>
        <section class="products">
            <h2>Yeni Eklenenler</h2>
            <div class="product-slider">
                <?php
                include 'db.php';
                $sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, pi.Image 
                        FROM Products p 
                        LEFT JOIN ProductImages pi ON p.ProductID = pi.ProductID AND pi.IsMainImage = TRUE
                        ORDER BY p.ProductID DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-item" data-id="' . $row["ProductID"] . '">';
                        echo '<a href="product_details.php?productID=' . $row["ProductID"] . '" class="product-link">';
                        echo '<div class="product-image">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row["Image"]) . '" alt="' . $row["ProductName"] . '">';
                        echo '</div>';
                        echo '<div class="product-content">';
                        echo '<h3>' . $row["ProductName"] . '</h3>';
                        echo '<p>Fiyat: ' . $row["Price"] . ' ₺</p>';
                        echo '</div>';
                        echo '</a>';
                        echo '<button class="add-to-cart" data-id="' . $row["ProductID"] . '">Sepete Ekle</button>';
                        echo '</div>';
                    }
                } else {
                    echo "Ürün bulunamadı.";
                }
                $conn->close();
                ?>
            </div> 
        </section>
        <section class="products">
            <h2>Giyim</h2>
            <div class="product-slider">
                <?php
                include 'db.php';
                $sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, pi.Image 
                        FROM Products p 
                        LEFT JOIN ProductImages pi ON p.ProductID = pi.ProductID AND pi.IsMainImage = TRUE
                        WHERE p.CategoryID = 2
                        ORDER BY p.ProductID DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-item" data-id="' . $row["ProductID"] . '">';
                        echo '<a href="product_details.php?productID=' . $row["ProductID"] . '" class="product-link">';
                        echo '<div class="product-image">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row["Image"]) . '" alt="' . $row["ProductName"] . '">';
                        echo '</div>';
                        echo '<div class="product-content">';
                        echo '<h3>' . $row["ProductName"] . '</h3>';
                        echo '<p>Fiyat: ' . $row["Price"] . ' ₺</p>';
                        echo '</div>';
                        echo '</a>';
                        echo '<button class="add-to-cart" data-id="' . $row["ProductID"] . '">Sepete Ekle</button>';
                        echo '</div>';
                    }
                } else {
                    echo "Ürün bulunamadı.";
                }
                $conn->close();
                ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 E-Ticaret Sitesi. Tüm hakları saklıdır.</p>
    </footer>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".add-to-cart").click(function() {
                var productID = $(this).data("id");
                $.ajax({
                    url: "add_to_cart.php",
                    type: "POST",
                    data: { productID: productID },
                    success: function(response) {
                        alert(response);
                    }
                });
            });
        });
        </script>
    <script>
        $(document).ready(function(){
            var slider = $('.product-slider');
            
            slider.slick({
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 4,
                autoplay: false,
                dots: true,
                arrows: false, 
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots: false,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots: false,
                        }
                    }
                ]
            });

            slider.on('mousedown', function() {
                $(this).addClass('grabbing');  
            }).on('mouseup', function() {
                $(this).removeClass('grabbing');  
            });

            slider.on('dragstart', function() {
                $(this).addClass('grabbing');
            }).on('dragend', function() {
                $(this).removeClass('grabbing');
            });
        });
        </script>
</body>
</html>