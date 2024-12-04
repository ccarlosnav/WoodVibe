<?php
include_once 'Configuracion.php';
?>
<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo '<p><script>Swal.fire({
                title: "Advertencia",
                text: "Inicie sesión nuevamente"
                }).then(function() {
                window.location = "index.php";
                });</script></p>';
}

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>WoodVibe - Página Principal</title>
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

    <!-- Products Design CSS File -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
        integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link href="imagenes" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="boss/assets/css/style.css" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>

     <!-- ======= Header ======= -->
     <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="logo"><a href="ES_view_user.php">WoodVibe<span>.</span></a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Inicio</a></li>
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
                            <li><a href="EN_view_user.php">Inglés</a></li>
                            <li><a href="ES_view_user.php">Español</a></li>
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

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container" data-aos="zoom-out" data-aos-delay="100">
            <h1>Bienvenido a <span>WoodVibe</span></h1>
            <h2>Somos un equipo que busca facilitar la compra de muebles en la zona.</h2>
            <div class="d-flex">
                <a href="#about" class="btn-get-started scrollto">Comenzar</a>
            </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= About Section ======= -->
        <section id="about" class="about section-bg">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>Sobre Nosotros</h2>
                    <h3>Conoce más sobre <span>Nosotros</span></h3>
                    <p>Somos un equipo de programadores, con la visión de crear un sitio web completamente funcional y,
                        así, hacer una tienda en línea de varios tipos de muebles.</p>
                </div>

                <div class="row">
                    <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                        <img src="boss/assets/img/about.jpg" class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0 content d-flex flex-column justify-content-center"
                        data-aos="fade-up" data-aos-delay="100">
                        <h3>¿Cuál es nuestra iniciativa para desarrollar esta página?</h3>
                        <p class="fst-italic"></p>
                        <ul>
                            <li>
                                <i class="bx bx-store-alt"></i>
                                <div>
                                    <h5>Cotizamos los mejores muebles</h5>
                                    <p>Hablamos con varios distribuidores para traerte los mejores productos.</p>
                                </div>
                            </li>
                            <li>
                                <i class="bx bx-images"></i>
                                <div>
                                    <h5>Alta calidad de productos</h5>
                                    <p>En nuestro sitio, no te venderemos productos que no valgan la pena, puedes estar
                                        seguro de eso.</p>
                                </div>
                            </li>
                        </ul>
                        <p></p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End About Section -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>Servicios</h2>
                    <h3>Explora nuestros <span>servicios</span></h3>
                    <p>Aquí encontrarás los servicios que te ofrecemos como equipo y como sitio de compras de muebles.
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bxl-dribbble"></i></div>
                            <h4><a href="">Compra de muebles</a></h4>
                            <p>Gran variedad de muebles, y varios tipos de muebles que distribuimos.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in"
                        data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-file"></i></div>
                            <h4><a href="">Servicio excelente</a></h4>
                            <p>Te brindaremos la mejor experiencia de compra y entrega.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in"
                        data-aos-delay="300">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-tachometer"></i></div>
                            <h4><a href="">Seguridad en cada proceso</a></h4>
                            <p>Todos los pasos que realices para comprar tus muebles son seguros.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in"
                        data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-world"></i></div>
                            <h4><a href="">Envíos a toda la zona de Santa Tecla</a></h4>
                            <p>Somos un sitio nuevo, por lo que solo enviaremos a Santa Tecla.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in"
                        data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-slideshow"></i></div>
                            <h4><a href="">Buena presentación de nuestros productos</a></h4>
                            <p>Te daremos las mejores imágenes y presentaciones para que estés seguro de lo que estás
                                comprando.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Services Section -->

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
                                <li><i class="bx bx-chevron-right"></i> <a href="#hero">Inicio</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="#about">Sobre nosotros</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="#services">Servicios</a></li>
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
                                <li><i class="bx bx-chevron-right"></i> <a href="EN_view_user.php">Inglés</a></li>
                                <li><i class="bx bx-chevron-right"></i> <a href="ES_view_user.php">Español</a></li>
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
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <!-- Vendor JS Files -->
        <script src="boss/assets/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="boss/assets/vendor/aos/aos.js"></script>
        <script src="boss/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="boss/assets/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="boss/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="boss/assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="boss/assets/vendor/waypoints/noframework.waypoints.js"></script>
        <script src="boss/assets/vendor/php-email-form/validate.js"></script>
        <script src="boss/assets/js/main.js"></script>

        <style>
            #footer {
                background: #ffffff;
            }
        </style>

</body>

</html>