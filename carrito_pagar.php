<?php
// include database configuration file
include_once 'Configuracion.php';

// initializ shopping cart class
include_once 'La-carta.php';
$cart = new Cart;

// redirect to home if cart is empty
if ($cart->total_items() <= 0) {
    header("Location: index.php");
}

// set customer ID in session
$_SESSION['sessCustomerID'] = 1;

// get customer details by session customer ID
try {
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['sessCustomerID'], PDO::PARAM_INT);
    $stmt->execute();
    $custRow = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pay - Cart</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="boss/assets/img/favicon.png" rel="icon">
    <link href="boss/assets/img/favicon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="boss/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="boss/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="boss/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="boss/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="boss/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="boss/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="boss/assets/css/style.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">


    <style>
    .container {
        padding: 20px;
    }

    .table {
        width: 65%;
        float: left;
    }

    .shipAddr {
        width: 30%;
        float: left;
        margin-left: 30px;
    }

    .footBtn {
        width: 95%;
        float: left;
    }

    .orderBtn {
        float: right;
    }

    #hero {
        width: 100%;
        height: 75vh;
        background: url(boss/assets/img/fondo-principal.jpg) top left;
        background-size: cover;
        position: relative;
    }

    #hero:before {
        content: "";
        background: rgb(255 255 255 / 0%);
        position: absolute;
        bottom: 0;
        top: 0;
        left: 0;
        right: 0;
    }
    </style>

</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="logo"><a href="EN_view_user.php">WoodVibe<span>.</span></a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                    <li class="dropdown"><a href="#"><span>Categories</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li class="dropdown"><a href="#"><span>Living Room</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_sofas.php">Sofas</a></li>
                                    <li><a href="EN_tvfurniture.php">TV Furniture</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Bedroom</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_beds.php">Beds</a></li>
                                    <li><a href="EN_closets.php">Closets</a></li>
                                    <li><a href="EN_bedside_tables.php">Bedside tables</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Bathroom</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_open_cabinets.php">Open cabinets</a></li>
                                    <li><a href="EN_wall_cabinets.php">Wall cabinets</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Office</span> <i class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_desks.php">Desks</a></li>
                                    <li><a href="EN_office_chairs.php">Office chairs</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Kitchen</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_cabinets.php">Cabinets</a></li>
                                    <li><a href="EN_chairs.php">Chairs</a></li>
                                    <li><a href="EN_kitchen_tables.php">Tables</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li style="position: relative; list-style: none; display: inline-block; margin-right: 15px;">
                        <a class="nav-link scrollto" href="EN_Cart.php"
                            style="position: relative; display: inline-block;">
                            <i class="fas fa-shopping-cart"
                                style="color:#00BFFF; font-size: 1.2em; margin-right: 5px;"></i> Cart
                        </a>
                    </li>
                    <li class="dropdown"><a href="#"><span>Language</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="carrito_pagar">English</a></li>
                            <li><a href="ES_carrito_pagar.php">Spanish</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" style="display: flex; align-items: center;">
                            <i class="fas fa-user" style="color:#00BFFF; font-size: 1.2em; margin-right: 5px;"></i>
                            <span><?php echo htmlspecialchars($nombre); ?></span>
                            <i class="bi bi-chevron-down" style="margin-left: 5px;"></i>
                        </a>
                        <ul>
                            <li><a href="EN_user_purchases.php">My purchase</a></li>
                            <li><a href="logout.php">LogOut</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
                </li>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->


    <div id="hero">
        <main>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="nav nav-pills">
                            <li role="presentation"><a href="EN_Cart.php">Cart</a></li>
                            <li role="presentation" class="active"><a href="Pagos.php">Pay</a></li>
                        </ul>
                    </div>

                    <div class="panel-body">
                        <h1>Order Preview</h1>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Sub total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($cart->total_items() > 0) {
                                    //get cart items from session
                                    $cartItems = $cart->contents();
                                    foreach ($cartItems as $item) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item["name"]); ?></td>
                                    <td><?php echo '$' . htmlspecialchars($item["price"]) . ' '; ?></td>
                                    <td><?php echo htmlspecialchars($item["qty"]); ?></td>
                                    <td><?php echo '$' . htmlspecialchars($item["subtotal"]) . ' '; ?></td>
                                </tr>
                                <?php }
                                } else { ?>
                                <tr>
                                    <td colspan="4">
                                        <p>No hay artículos en tu carrito......</p>
                                    </td>
                                    <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <?php if ($cart->total_items() > 0) { ?>
                                    <td class="text-center"><strong>Total
                                            <?php echo '$' . htmlspecialchars($cart->total()) . ' '; ?></strong></td>
                                    <?php } ?>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="footBtn">
                            <a href="EN_view_user.php" class="btn btn-warning"><i
                                    class="glyphicon glyphicon-menu-left"></i> Continue Shopping</a>
                            <a href="checkout.php" class="btn btn-success orderBtn" id="placeOrderBtn">
                                Place an order <i class="glyphicon glyphicon-menu-right"></i>
                            </a>

                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
document.getElementById('placeOrderBtn').addEventListener('click', function(e) {
    e.preventDefault(); // Evita la redirección inmediata

    Swal.fire({
        icon: 'warning',
        text: 'A 13% tax will be added to your purchase.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        customClass: {
            popup: 'swal-medium' // Clase personalizada para tamaño medio
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirige al usuario a checkout.php al hacer clic en "OK"
            window.location.href = 'checkout.php';
        }
    });
});
</script>

<style>
/* Clase CSS para hacer la alerta de tamaño medio */
.swal-medium {
    width: 450px !important; /* Ancho personalizado */
    font-size: 16px; /* Tamaño de fuente ajustado */
}
</style>




                        </div>
                    </div>
                </div>
                <!--Panel cierra-->
            </div>
        </main>
    </div>

    <!-- ======= Footer ======= -->
    <footer id="footer">


        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6 footer-contact">
                        <h3>WoodVibe<span>.</span></h3>
                        <p>
                            Santa Tecla <br>
                            El Salvador<br><br>
                            <strong>Phone:</strong> +503 1234 5678<br>
                            <strong>Email:</strong> WoodVibe.shop@gmail.com<br>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="EN_view_user.php">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#hero">Cart</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="logout.php">LogOut</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Our Services</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Language</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="carrito_pagar.php">English</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_carrito_pagar.php">Spanish</a></li>

                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="container py-4">
            <div class="copyright">
                &copy; Copyright <strong><span>WoodVibe</span></strong>. All Rights Reserved
            </div>
            <div class="credits">

            </div>
        </div>
    </footer><!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <script src="boss/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="boss/assets/vendor/aos/aos.js"></script>
    <script src="boss/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="boss/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="boss/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="boss/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="boss/assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="boss/assets/vendor/php-email-form/validate.js"></script>
    <script src="boss/assets/js/main.js"></script>

</body>

</html>