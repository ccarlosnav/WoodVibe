<?php
// Incluye los archivos necesarios
require 'funcs/conexion.php';
require 'funcs/funcs.php';

function redirectWithError($message)
{
    header('Location: index.php?error=' . urlencode($message));
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['token'])) {
    redirectWithError('Parámetros inválidos.');
}

$id = trim($_GET['id']);
$token = trim($_GET['token']);

if (empty($id) || empty($token)) {
    redirectWithError('Faltan parámetros obligatorios.');
}

if (!verificaTokenPass($id, $token)) {
    redirectWithError('No se pudo verificar la información.');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Change Password</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Cambiar contraseña</h3>
                                </div>
                                <div class="card-body">
                                    <div class="small mb-3 text-muted">Introduzca la nueva contraseña y confirme</div>
                                    <form id="loginform" action="ES_guarda_Pass.php" method="POST" onsubmit="return validateForm()">
                                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $id; ?>" />
                                        <input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Nueva contraseña</label>
                                            <input class="form-control py-4" id="inputPassword" name="password" type="password" placeholder="Ingresar nueva contraseña" />
                                            <label class="small mb-1" for="inputConfirmPassword">Confirmar contraseña</label>
                                            <input class="form-control py-4" id="inputConfirmPassword" name="con_password" type="password" placeholder="Confirmar contraseña" />
                                        </div>
                                        <div id="passwordError" class="text-danger small mb-2" style="display: none;"></div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="ES_login.php">Regresa al inicio de sesión</a>
                                            <button type="submit" class="btn btn-primary">Cambiar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="ES_registro.php">¿Necesita una cuenta? Regístrese</a></div>
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
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
    function validateForm() {
        var password = document.getElementById("inputPassword").value;
        var confirmPassword = document.getElementById("inputConfirmPassword").value;
        var errorElement = document.getElementById("passwordError");
        
        if (password.length < 8) {
            errorElement.innerHTML = "La contraseña debe tener al menos 8 caracteres.";
            errorElement.style.display = "block";
            return false;
        }
        
        if (password !== confirmPassword) {
            errorElement.innerHTML = "Las contraseñas no coinciden.";
            errorElement.style.display = "block";
            return false;
        }
        
        errorElement.style.display = "none";
        return true;
    }
    </script>
</body>
</html>