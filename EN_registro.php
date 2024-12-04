<?php
require 'funcs/conexion.php';
require 'funcs/funcs.php';

$errors = array();
$emailExists = false;
$usernameExists = false;
$invalidEmail = false;
$passwordMismatch = false;

if (!empty($_POST)) {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $passwordc = $_POST['passwordc'];
    $email = $_POST['email'];

    $activo = 1;
    $tipo_usuario = 2;

    if (isNull($nombre, $usuario, $password, $passwordc, $email)) {
        $errors[] = "You must fill in all fields";
    }

    if (!isEmail($email)) {
        $invalidEmail = true;
        $errors[] = "Invalid email address";
    }

    if (!validaPassword($password, $passwordc)) {
        $passwordMismatch = true;
        $errors[] = "Passwords do not match";
    }

    if (usuarioExiste($usuario)) {
        $usernameExists = true;
        $errors[] = "The user name $usuario already exists";
    }

    if (emailExiste($email)) {
        $emailExists = true;
        $errors[] = "The email $email already exists";
    }

    if (count($errors) == 0) {
        $pass_hash = hashPassword($password);
        $token = generateToken();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, activacion, token, id_tipo) VALUES (:usuario, :password, :nombre, :email, :activo, :token, :tipo_usuario)");
            
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':password', $pass_hash);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':activo', $activo);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);
            
            $stmt->execute();
            
            $registro = $pdo->lastInsertId();
            
            $pdo->commit();

            if ($registro > 0) {
                // Si el registro fue exitoso, redirige con éxito a través de la URL
                header("Location: EN_registro.php?success=1");
                exit();
            } else {
                $errors[] = "Error registering";
            }
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>WoodVibe - MainPage</title>
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
</head>

<body class="bg-primary">
    <!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="logo"><a href="index.php">WoodVibe<span>.</span></a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto " href="index.php">Home</a></li>
                    <li><a class="nav-link scrollto active" href="EN_registro.php">Register</a></li>
                    <li><a class="nav-link scrollto" href="EN_login.php">Login</a></li>
                    <li class="dropdown"><a href="#"><span>Language</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="EN_registro.php">English</a></li>
                            <li><a href="ES_registro.php">Spanish</a></li>
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
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">Create Account</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="registerForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-4 pb-1">
                                            <div class="form-outline">
                                                <label class="small mb-1" for="nombre">Name</label>
                                                <input class="form-control py-2" id="nombre" name="nombre" type="text" placeholder="Name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4 pb-1">
                                            <div class="form-outline">
                                                <label class="small mb-1" for="usuario">Username</label>
                                                <input class="form-control py-2" id="usuario" name="usuario" type="text" placeholder="Username" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4 pb-1">
                                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                                        <input class="form-control py-2" id="inputEmailAddress" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email address" required />
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-2" id="inputPassword" name="password" minlength="8" type="password" placeholder="Enter password" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                <input class="form-control py-2" id="inputConfirmPassword" name="passwordc" minlength="8" type="password" placeholder="Confirm password" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4 mb-0">
                                        <button type="submit" class="btn btn-primary btn-block" id="createAccountButton">Create Account</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a href="EN_login.php">Have an account? Go to login</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ======= Footer ======= -->
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
          <li><i class="bx bx-chevron-right"></i> <a href="EN_login.php">Login</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="EN_registro.php">Register</a></li>
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
          <li><i class="bx bx-chevron-right"></i> <a href="EN_registro.php">English</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="ES_registro.php">Spanish</a></li>

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

    <!-- Vendor JS Files -->
    <script src="boss/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="boss/assets/vendor/aos/aos.js"></script>
    <script src="boss/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="boss/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="boss/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="boss/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="boss/assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="boss/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="boss/assets/js/main.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');

        if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Account created successfully.',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'EN_registro.php';
                }
            });
            history.replaceState(null, null, 'EN_registro.php');
        }

        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var nombre = document.getElementById('nombre').value.trim();
            var usuario = document.getElementById('usuario').value.trim();
            var email = document.getElementById('inputEmailAddress').value.trim();
            var password = document.getElementById('inputPassword').value.trim();
            var passwordc = document.getElementById('inputConfirmPassword').value.trim();

            if (nombre === '' || usuario === '' || email === '' || password === '' || passwordc === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'You must fill in all fields',
                });
            } else if (!/^[a-zA-Z\s]+$/.test(nombre)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Name can only contain letters and spaces',
                });
            } else if (!validateEmail(email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Invalid email address',
                });
            } else if (password.length < 8 || passwordc.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Passwords must be at least 8 characters long',
                });
            } else if (password !== passwordc) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Passwords do not match',
                });
            } else {
                this.submit();
            }
        });

        <?php
        if ($usernameExists) {
            echo "Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'The user name already exists',
            });";
        }
        if ($emailExists) {
            echo "Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'The email already exists',
            });";
        }
        ?>

        function validateEmail(email) {
            var re = /^(([^<>()\[\]\.,;:\s@"]+(\.[^<>()\[\]\.,;:\s@"]+)*)|(".+"))@(([^<>()[\]\.,;:\s@"]+\.)+[^<>()[\]\.,;:\s@"]{2,})$/i;
            return re.test(email);
        }
    });
    </script>

    <style>
        #hero:before {
            width: 100%;
            height: 96%;
            background: url(../img/hero-bg.jpg) top left;
            background-size: cover;
            position: relative;
        }

        #hero {
            width: 100%;
            height: 91.3vh;
        }
    </style>

</body>
</html>