<?php
require_once 'funcs/conexion.php';
require_once 'funcs/funcs.php';

$errors = array();

if (!empty($_POST)) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Debe introducir una dirección de correo electrónico válida']);
        exit;
    }

    if (!emailExiste($email)) {
        echo json_encode(['status' => 'error', 'message' => 'La dirección de correo electrónico introducida no está registrada']);
        exit;
    }

    $user_id = getValor('id', 'correo', $email);
    $nombre = getValor('nombre', 'correo', $email);

    if ($user_id === false || $nombre === false) {
        echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud']);
        exit;
    }

    $token = generateTokenPass($user_id);

    if ($token === false) {
        echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud']);
        exit;
    }

    $url = 'http://' . $_SERVER["SERVER_NAME"] . '/ES_cambiaPass.php?id=' . urlencode($user_id) . '&token=' . urlencode($token);

    $asunto = 'Password recovery - WoodVibe';
    $cuerpo = "Estimado $nombre: <br /><br />Se ha solicitado un restablecimiento de contraseña <br/><br/>Para restablecer su contraseña, haga clic en el siguiente enlace: <a href='$url'>Cambiar contraseña</a>";

    if (enviarEmail($email, $nombre, $asunto, $cuerpo)) {
        echo json_encode(['status' => 'success', 'message' => "Hemos enviado un correo electrónico a la siguiente dirección $email para restablecer su contraseña."]);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al enviar correo electrónico']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>WoodVibe - Restore Password</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="boss/assets/img/favicon.png" rel="icon">
    <link href="boss/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="hero">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">Recuperar contraseña</h3>
                            </div>
                            <div class="card-body">
                                <div class="small mb-3 text-muted">Introduzca su dirección de correo electrónico y le enviaremos un enlace para restablecer su contraseña.</div>
                                <form id="loginform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                                        <input class="form-control py-4" id="inputEmailAddress" name="email" type="email" aria-describedby="emailHelp" placeholder="Introducir dirección de correo electrónico" />
                                    </div>
                                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <a class="small" href="ES_login.php">Regresar al inicio de sesión</a>
                                        <button type="submit" id="submitBtn" class="btn btn-primary">Enviar</button>
                                    </div>
                                </form>
                                <?php echo resultBlock($errors); ?>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a href="EN_registro.php">¿Necesita una cuenta? Regístrese</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="layoutAuthentication_footer">
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2020</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
        </footer>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('loginform').addEventListener('submit', function(e) {
            e.preventDefault();

            var submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';

            var formData = new FormData(this);

            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'ES_login.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ha ocurrido un error inesperado'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send';
                });
        });
    </script>
</body>

</html>
</body>
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

</html>
