<?php
session_start();
require_once 'funcs/conexion.php';
require_once 'funcs/funcs.php';

// Conexión a la base de datos usando PDO
try {
    $conn = new PDO("pgsql:host=localhost;dbname=woodvibe", "postgres", "postgres");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Consulta SQL para obtener los productos de bedside tables
$sql = "SELECT p.* FROM mis_productos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        WHERE c.nombre = 'Bedsides_table'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>WoodVibe - Mesas de noche</title>
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

    <!-- FontAwesome -->
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="boss/assets/css/style.css" rel="stylesheet">

    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            width: 100%;
            height: auto;
        }

        .text-danger {
            color: #ff4d4d !important;
        }

        .btn-custom {
            width: 45%;
            margin: 2.5%;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .add-to-cart {
            background-color: #00BFFF;
            color: white;
            border: none;
        }

        .modal-dialog {
            max-width: 600px;
            margin: 1.75rem auto;
        }

        .modal-content {
            border-radius: 10px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-header .close {
            padding: 1rem;
            margin: -1rem -1rem -1rem auto;
        }

        .modal-body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-body img {
            max-width: 100%;
            height: auto;
            display: block;
        }
    </style>
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
                            <li><a href="EN_beside_tables.php">Inglés</a></li>
                            <li><a href="ES_beside_tables.php">Español</a></li>
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


    <main id="main">

        <!-- ======= Start Products Section ======= -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <section id="team" class="team section-bg">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>Productos</h2>
                    <h3>Sección de <span>Mesas de noche</span></h3>
                    <p>Mostramos algunos productos que tenemos en stock.</p>
                </div>
                <div class="row">
                    <?php
                    if (!empty($productos)) {
                        foreach ($productos as $producto) {
                    ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <img class="card-img-top" src="imagenes/<?php echo htmlspecialchars($producto['image']); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
                                    <div class="card-body">
                                        <div>
                                            <h5 class="card-title border-bottom pb-3 text-primary">
                                                <?php echo htmlspecialchars($producto['nombre_es']); ?>
                                            </h5>
                                        </div>
                                        <div>
                                            <p class="card-text">
                                                <?php echo htmlspecialchars($producto["descripcion_es"]); ?>
                                            </p>
                                        </div>
                                        <div class="mb-1 text-success fw-bold fst-italic">
                                            Precio: <?php echo '$' . number_format($producto['price'], 2); ?>
                                        </div>
                                        <div class="mb-1 text-dark fw-bold fst-italic">
                                            Stock: <?php echo $producto['stock']; ?> unidades
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $producto['id']; ?>">Ver Imagen</button>
                                            <?php if ($producto['stock'] > 0) { ?>
                                                <a class="btn btn-primary btn-custom add-to-cart" href="ES_AccionCarta.php?action=addToCart&id=<?php echo $producto["id"]; ?>">Añadir al carrito</a>
                                            <?php } else { ?>
                                                <p class="text-danger">Producto no disponible</p>
                                                <button class="btn btn-secondary btn-custom" disabled>No disponible</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="imageModal<?php echo $producto['id']; ?>" tabindex="-1" aria-labelledby="imageModalLabel<?php echo $producto['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel<?php echo $producto['id']; ?>"><?php echo htmlspecialchars($producto['nombre_es']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="imagenes/<?php echo htmlspecialchars($producto['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($producto['nombre_es']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->

                    <?php
                        }
                    } else {
                        echo "<p>No se encontraron productos de mesillas de noche.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>

        <script>
            // Add a click event for the "Add to cart" buttons
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var url = this.getAttribute('href');
                    showSuccessAlert(url);
                });
            });

            function showSuccessAlert(url) {
                Swal.fire({
                    title: "Producto añadido con éxito",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = url;
                });
            }
        </script>
        <!-- End Products Section -->

    </main><!-- End #main -->

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
                            <strong>Email:</strong> WoodVibe.shop@gmail.com<br>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Enlaces Útiles</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_view_user.php">Inicio</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#team">Productos</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
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
                        <li><i class="bx bx-chevron-right"></i> <a href="EN_bedside_tables.php">Inglés</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="ES_bedside_tables.php">Español</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="container py-4">
            <div class="copyright">
                &copy; Copyright <strong><span>WoodVibe</span></strong>. Todos los Derechos Reservados
            </div>
            <div class="credits">

            </div>
        </div>
    </footer><!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="boss/assets/vendor/aos/aos.js"></script>
    <script src="boss/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="boss/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="boss/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="boss/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="boss/assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="boss/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="boss/assets/js/main.js"></script>

</body>

</html>

<?php
$conn = null; // Cerrar la conexión
?>
