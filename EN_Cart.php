<?php
// Inicializar la clase del carrito de compras
include_once 'La-carta.php';
$cart = new Cart;

// Verificar si la sesión no está iniciada antes de llamarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo '<p><script>Swal.fire({
            title: "Warning",
            text: "Please log in again."
            }).then(function() {
            window.location = "index.php";
            });</script></p>';
    exit();
}

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$id_usuario = $_SESSION['id_usuario'];

try {
    $dsn = "pgsql:host=localhost;port=5432;dbname=woodvibe;";
    $username = "postgres";
    $password = "postgres";
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Restaurar el carrito del usuario desde la base de datos
    $stmt = $db->prepare("SELECT * FROM carrito WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $savedItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $itemsOutOfStock = [];
    foreach ($savedItems as $item) {
        // Verificar stock actual y obtener el nombre en inglés y la imagen
        $stmt = $db->prepare("SELECT stock, name, image FROM mis_productos WHERE id = :id");
        $stmt->bindParam(':id', $item['id_producto'], PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product && $product['stock'] > 0) {
            $qty = min($item['cantidad'], $product['stock']);
            $itemData = array(
                'id' => $item['id_producto'],
                'name' => $product['name'],
                'price' => $item['precio'],
                'qty' => $qty,
                'image' => $product['image']
            );
            $cart->insert($itemData);
        } else {
            $itemsOutOfStock[] = $item['nombre_producto'];
        }
    }

    // Mostrar alerta si hubo productos eliminados por falta de stock
    if (!empty($itemsOutOfStock)) {
        echo '<p><script>Swal.fire({
                title: "Notice",
                text: "Some items were removed from your cart due to lack of stock: ' . implode(', ', $itemsOutOfStock) . '",
                icon: "info"
                });</script></p>';
    }

    // Eliminar los productos del carrito en la base de datos después de restaurar en la sesión
    $stmt = $db->prepare("DELETE FROM carrito WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

} catch (PDOException $e) {
    die("Connection error with the database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>WoodVibe - Shopping Cart</title>
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

        input[type="number"] {
            width: 20%;
        }

        .cart-item img {
            width: 50px;
            height: auto;
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
    <script>
        function updateCartItem(obj, idSession, idProduct) {
        var quantity = parseInt(obj.value, 10);

        if (isNaN(quantity) || quantity < 1) {
            Swal.fire({
                title: 'Invalid Quantity',
                text: 'Please enter a valid quantity.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload(); // Recargar la página para resetear el input de cantidad
            });
            return;
        }

        $.ajax({
            url: 'EN_AccionCarta.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'updateCartItem',
                id: idProduct,
                idSession,
                qty: quantity
            },
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Recargar la página para resetear el input de cantidad
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Request failed: " + textStatus + ", " + errorThrown);
                Swal.fire({
                    title: 'Error!',
                    text: 'Connection error, please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Recargar la página para resetear el input de cantidad
                });
            }
        });
    }

    function removeCartItem(rowId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to remove this item from your cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                if (!/^[a-zA-Z0-9]+$/.test(rowId)) {
                    return Swal.fire({
                        title: 'Invalid Session ID',
                        text: "Invalid ID Product Session",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }

                $.ajax({
                    url: 'EN_AccionCarta.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'removeCartItem',
                        id: rowId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Deleted!',
                                'The item has been removed.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Failed!',
                                'Failed to remove the item: ' + response.message,
                                'error'
                            );
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Request failed: " + textStatus + ", " + errorThrown);
                        Swal.fire(
                            'Error!',
                            'Connection error, please try again.',
                            'error'
                        );
                    }
                });
            }
        })
    }

    $(document).on('click', '.delete-cart-item', function(event) {
        event.preventDefault();
        var rowId = $(this).data('rowid');
        removeCartItem(rowId);
    });

    function updateCartTotal(total, count) {
        $('.text-center strong').text('Total $' + total.toFixed(2) + ' Dollars');
    }
    </script>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="logo"><a href="EN_view_user.php">WoodVibe<span>.</span></a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="EN_view_user.php">Home</a></li>
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
                            <li><a href="EN_Cart.php">English</a></li>
                            <li><a href="ES_Cart.php">Spanish</a></li>
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
                        <li class="active"><a href="EN_Cart.php">Cart</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <h1>Shopping Cart</h1>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Product Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Sub Total</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($cart->total_items() > 0) {
                                    $cartItems = $cart->contents();
                                    foreach ($cartItems as $item) {
                                ?>
                                        <tr class="cart-item" id="cart-item-<?php echo htmlspecialchars($item["rowid"]); ?>">
                                            <td><?php echo htmlspecialchars($item["name"]); ?></td>
                                            <td>
                                                <?php if (!empty($item["image"])) { ?>
                                                    <img src="imagenes/<?php echo htmlspecialchars($item["image"]); ?>" alt="<?php echo htmlspecialchars($item["name"]); ?>">
                                                <?php } else { ?>
                                                    <span>No image available</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo '$' . htmlspecialchars($item["price"]) . ' Dollars'; ?></td>
                                            <td>
                                                <input
                                                    type="number"
                                                    class="form-control text-center"
                                                    value="<?php echo htmlspecialchars($item["qty"]); ?>"
                                                    min="1"
                                                    onchange="updateCartItem(this, '<?php echo htmlspecialchars($item["rowid"]); ?>', '<?php echo htmlspecialchars($item["id"]) ?>')">
                                            </td>
                                            <td><?php echo '$' . htmlspecialchars($item["subtotal"]) . ' Dollars'; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-danger delete-cart-item" data-rowid="<?php echo htmlspecialchars($item["rowid"]); ?>"><i class="glyphicon glyphicon-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="6">
                                            <p>Your cart is empty.....</p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><a href="EN_view_user.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continue Shopping</a></td>
                                    <td colspan="2"></td>
                                    <?php if ($cart->total_items() > 0) { ?>
                                        <td class="text-center"><strong>Total <?php echo '$' . htmlspecialchars($cart->total()) . ' Dollars'; ?></strong></td>
                                        <td><a href="carrito_pagar.php" class="btn btn-success btn-block">Checkout <i class="glyphicon glyphicon-menu-right"></i></a></td>
                                    <?php } ?>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg

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
                            <li><i class="bx bx-chevron-right"></i> <a href="logout.php">Log Out</a></li>
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
                            <li><i class="bx bx-chevron-right"></i> <a href="EN_Cart.php">English</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_Cart.php">Spanish</a></li>
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
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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
