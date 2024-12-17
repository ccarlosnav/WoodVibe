<?php
include_once 'Configuracion.php';
?>
<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo '<p><script>Swal.fire({
                title: "Warning",
                text: "Please log in again."
                }).then(function() {
                window.location = "index.php";
                });</script></p>';
}

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>WoodVibe - MainPage</title>
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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
                            <li><a href="EN_view_user.php">English</a></li>
                            <li><a href="ES_view_user.php">Spanish</a></li>
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

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container" data-aos="zoom-out" data-aos-delay="100">
            <h1>Welcome to <span>WoodVibe</span></h1>
            <h2>We are a team that seeks to facilitate the purchase of furniture in the area.</h2>
            <div class="d-flex">
                <a href="#about" class="btn-get-started scrollto">Get Started</a>
            </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= About Section ======= -->
        <section id="about" class="about section-bg">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>About Us</h2>
                    <h3>Learn more <span>About Us</span></h3>
                    <p>We are a team of programmers, with the vision of creating a fully functional website and thus
                        making an online store of various types of furniture.</p>
                </div>

                <div class="row">
                    <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                        <img src="boss/assets/img/about.jpg" class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0 content d-flex flex-column justify-content-center"
                        data-aos="fade-up" data-aos-delay="100">
                        <h3>What is our initiative to develop this page?</h3>
                        <p class="fst-italic"></p>
                        <ul>
                            <li>
                                <i class="bx bx-store-alt"></i>
                                <div>
                                    <h5>We quote the best furniture</h5>
                                    <p>We talk to several distributors to bring you the best products.</p>
                                </div>
                            </li>
                            <li>
                                <i class="bx bx-images"></i>
                                <div>
                                    <h5>High quality products</h5>
                                    <p>On our site, we will not sell you products that are not worth it, you can be sure
                                        of that.</p>
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
                    <h2>Services</h2>
                    <h3>Explore our <span>Services</span></h3>
                    <p>Here you will find the services we offer you as a team and as a furniture shopping site.</p>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bxl-dribbble"></i></div>
                            <h4><a href="">Furniture purchase</a></h4>
                            <p>Wide variety of furniture, and several types of furniture that we distribute.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in"
                        data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-file"></i></div>
                            <h4><a href="">Excellent service</a></h4>
                            <p>We will give you the best shopping and delivery experience.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in"
                        data-aos-delay="300">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-tachometer"></i></div>
                            <h4><a href="">Security in every process</a></h4>
                            <p>Every step you take to buy your furniture is secure.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in"
                        data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-world"></i></div>
                            <h4><a href="">Shipments throughout the Santa Tecla area</a></h4>
                            <p>We are a new site, so we will only ship to Santa Tecla.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in"
                        data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-slideshow"></i></div>
                            <h4><a href="">Good presentation of our products</a></h4>
                            <p>We will give you the best images and presentations so that you are sure of what you are
                                buying.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Services Section -->



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
                            <strong>Phone:</strong> +503 1234 5678<br>
                            <strong>Email:</strong> WoodVibe.shop@gmail.com<br>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#hero">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#about">About us</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#services">Services</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#team">Products</a></li>
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
                            <li><i class="bx bx-chevron-right"></i> <a href="EN_view_user.php">English</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_view_user.php">Spanish</a></li>

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

</body>

</html>
