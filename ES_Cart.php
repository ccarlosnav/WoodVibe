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
            title: "Advertencia",
            text: "Por favor, inicie sesión nuevamente."
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
                'name' => $product['name'], // Guardando nombre en inglés bajo la clave 'name'
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
                title: "Aviso",
                text: "Algunos artículos fueron eliminados de tu carrito debido a la falta de stock: ' . implode(', ', $itemsOutOfStock) . '",
                icon: "info"
                });</script></p>';
    }

    // Eliminar los productos del carrito en la base de datos después de restaurar en la sesión
    $stmt = $db->prepare("DELETE FROM carrito WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

} catch (PDOException $e) {
    die("Error de conexión con la base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>WoodVibe - Carrito de Compras</title>
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

        .cart-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-image: url('/imagenes/icono-carrito.png'); /* Verifica la ruta */
            background-size: contain;
            background-repeat: no-repeat;
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
    <script>
         function updateCartItem(obj, idSession, idProduct) {
        var quantity = parseInt(obj.value, 10);

        if (isNaN(quantity) || quantity < 1) {
            Swal.fire({
                title: 'Cantidad Inválida',
                text: 'Por favor ingrese una cantidad válida.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload(); // Recargar la página para resetear el input de cantidad
            });
            return;
        }

        $.ajax({
            url: 'ES_AccionCarta.php',
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
                    text: 'Error de conexión, por favor inténtelo de nuevo.',
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
            title: '¿Estás seguro?',
            text: "¿Deseas eliminar este producto de tu carrito?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!',
            cancelButtonText: 'No, mantenerlo'
        }).then((result) => {
            if (result.isConfirmed) {
                if (!/^[a-zA-Z0-9]+$/.test(rowId)) {
                    return Swal.fire({
                        title: 'ID Inválido',
                        text: "ID de producto no válido",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }

                $.ajax({
                    url: 'ES_AccionCarta.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'removeCartItem',
                        id: rowId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Eliminado!',
                                'El producto ha sido eliminado.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'No se pudo eliminar el producto: ' + response.message,
                                'error'
                            );
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Request failed: " + textStatus + ", " + errorThrown);
                        Swal.fire(
                            'Error!',
                            'Error de conexión, por favor inténtelo de nuevo.',
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
        $('.text-center strong').text('Total $' + total.toFixed(2) + ' Dólares');
    }
    </script>
</head>

<body>

     <!-- ======= Header ======= -->
     <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="logo"><a href="ES_view_user.php">WoodVibe<span>.</span></a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="ES_view_user.php">Inicio</a></li>
                    <li class="dropdown"><a href="#"><span>Categorías</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li class="dropdown"><a href="#"><span>Sala de Estar</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_sofas.php">Sofás</a></li>
                                    <li><a href="ES_tvfurniture.php">Muebles para TV</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Habitación</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_beds.php">Camas</a></li>
                                    <li><a href="ES_closets.php">Closets</a></li>
                                    <li><a href="ES_bedside_tables.php">Mesas de Noche</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Baño</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_open_cabinets.php">Gabinetes Abiertos</a></li>
                                    <li><a href="ES_wall_cabinets.php">Gabinetes de Pared</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Oficina</span> <i class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_desks.php">Escritorios</a></li>
                                    <li><a href="ES_office_chairs.php">Sillas de Oficina</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Cocina</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_cabinets.php">Gabinetes</a></li>
                                    <li><a href="ES_chairs.php">Sillas</a></li>
                                    <li><a href="ES_kitchen_tables.php">Mesas</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li style="position: relative; list-style: none; display: inline-block; margin-right: 15px;">
                        <a class="nav-link scrollto" href="ES_Cart.php"
                            style="position: relative; display: inline-block;">
                            <i class="fas fa-shopping-cart"
                                style="color:#00BFFF; font-size: 1.2em; margin-right: 5px;"></i> Carrito
                        </a>
                    </li>
                    <li class="dropdown"><a href="#"><span>Idioma</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="EN_Cart.php">Inglés</a></li>
                            <li><a href="ES_Cart.php">Español</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" style="display: flex; align-items: center;">
                            <i class="fas fa-user" style="color:#00BFFF; font-size: 1.2em; margin-right: 5px;"></i>
                            <span><?php echo htmlspecialchars($nombre); ?></span>
                            <i class="bi bi-chevron-down" style="margin-left: 5px;"></i>
                        </a>
                        <ul>
                            <li><a href="ES_user_purchases.php">Mis Compras</a></li>
                            <li><a href="ES_logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
        </div>
    </header><!-- End Header -->

    <div id="hero">
        <main>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="nav nav-pills">
                            <li role="presentation" class="active"><a href="ES_Cart.php">Carrito</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <h1>Carrito</h1>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Imagen del Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Sub total</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($cart->total_items() > 0) {
                                    $cartItems = $cart->contents();
                                    foreach ($cartItems as $item) {
                                ?>
                                        <tr class="cart-item">
                                            <td><?php echo htmlspecialchars($item["name"]); ?></td>
                                            <td>
                                                <?php if (!empty($item["image"])) { ?>
                                                    <img src="imagenes/<?php echo htmlspecialchars($item["image"]); ?>" alt="<?php echo htmlspecialchars($item["name_es"]); ?>">
                                                <?php } else { ?>
                                                    <span>No hay imagen disponible</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo '$' . htmlspecialchars($item["price"]) . ' Dólares'; ?></td>
                                            <td>
                                                <input
                                                    type="number"
                                                    class="form-control text-center"
                                                    value="<?php echo htmlspecialchars($item["qty"]); ?>"
                                                    min="1"
                                                    onchange="updateCartItem(this, '<?php echo htmlspecialchars($item["rowid"]); ?>', '<?php echo htmlspecialchars($item["id"]) ?>')">
                                            </td>
                                            <td><?php echo '$' . htmlspecialchars($item["subtotal"]) . ' Dólares'; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-danger delete-cart-item" data-rowid="<?php echo htmlspecialchars($item["rowid"]); ?>"><i class="glyphicon glyphicon-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="6">
                                            <p>No has pedido ningún producto.....</p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><a href="ES_view_user.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Volver a la tienda</a></td>
                                    <td colspan="2"></td>
                                    <?php if ($cart->total_items() > 0) { ?>
                                        <td class="text-center"><strong>Total <?php echo '$' . htmlspecialchars($cart->total()) . ' Dólares'; ?></strong></td>
                                        <td><a href="ES_carrito_pagar.php" class="btn btn-success btn-block">Pagar <i class="glyphicon glyphicon-menu-right"></i></a></td>
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

                    <div class="col-lg-3 col-md-6 footer-contact">
                        <h3>WoodVibe<span>.</span></h3>
                        <p>
                            Santa Tecla <br>
                            El Salvador<br><br>
                            <strong>Teléfono:</strong> +503 1234 5678<br>
                            <strong>Correo:</strong> WoodVibe.shop@gmail.com<br>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Enlaces Útiles</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_view_user.php">Inicio</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#hero">Carrito</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md=6 footer-links">
                        <h4>Nuestros Servicios</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Diseño Web</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Desarrollo Web</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Gestión de Productos</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Diseño Gráfico</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Idioma</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="EN_Cart.php">Inglés</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_Cart.php">Español</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="container py-4">
            <div class="copyright">
                &copy; Copyright <strong><span>WoodVibe</span></strong>. Todos los derechos reservados
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
