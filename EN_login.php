<?php
include_once 'funcs/conexion.php';
include_once 'funcs/funcs.php';

session_start();

$errors = array();
$login_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = htmlspecialchars(trim($_POST['usuario']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (empty($usuario) || empty($password)) {
        $errors[] = "You must fill in all fields";
    } else {
        $login_result = login($usuario, $password);
        if (is_array($login_result)) {
            $_SESSION['nombre'] = $login_result['nombre'];
            $_SESSION['id_tipo'] = $login_result['id_tipo'];
            $_SESSION['id'] = $login_result['id'];
            $login_success = true;
        } else {
            if ($login_result == 'Invalid username') {
                $errors[] = "Invalid username";
            } elseif ($login_result == 'Invalid password') {
                $errors[] = "Invalid password";
            } else {
                $errors[] = "Incorrect username or password";
            }
        }
    }
}


function showAlert($icon, $title, $message)
{
    echo <<<EOD
    <script>
    Swal.fire({
        icon: '{$icon}',
        title: '{$title}',
        text: '{$message}',
    });
    </script>
EOD;
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
<header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
        <h1 class="logo"><a href="index.php">WoodVibe<span>.</span></a></h1>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto " href="index.php">Home</a></li>
                <li><a class="nav-link scrollto" href="EN_registro.php">Register</a></li>
                <li><a class="nav-link scrollto active" href="#hero">Login</a></li>
                <li class="dropdown"><a href="#"><span>Language</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="EN_login.php">English</a></li>
                        <li><a href="ES_login.php">Spanish</a></li>
                    </ul>
                </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>

<div id="hero">
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-4">Login</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="form-group">
                                    <label class="small mb-1" for="inputEmailAddress">User</label>
                                    <input class="form-control py-2" id="inputEmailAddress" name="usuario" type="text" placeholder="Enter your user" />
                                </div>
                                <br>
                                
                                <div class="form-group">
                                    <label class="small mb-1" for="inputPassword">Password</label>
                                    <input class="form-control py-2" id="inputPassword" name="password" type="password" placeholder="Enter password" />
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" />
                                        <label class="custom-control-label" for="rememberPasswordCheck">Remember password</label>
                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <button type="submit" class="btn btn-primary" id="loginButton">Access</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <div class="small"><a href="restorePass.php">Forgot Password?</a></div>
                        </div>
                    </div>
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
              <strong>Phone:</strong> +503 1234 5678<br>
              <strong>Email:</strong> WoodVibe.shop@gmail.com<br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="index.php">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#hero">Login</a></li>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('loginButton').addEventListener('click', function (e) {
            e.preventDefault();
            var usuario = document.getElementById('inputEmailAddress').value.trim();
            var password = document.getElementById('inputPassword').value.trim();

            if (usuario === '' || password === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'You must fill in all fields',
                });
            } else {
                document.querySelector('form').submit();
            }
        });

        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{$error}',
                });";
            }
        }
        if ($login_success) {
            echo "Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'You have logged in successfully.',
                willClose: () => {
                    window.location.href = 'EN_view_user.php';
                }
            });";
            
        }
        ?>
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
