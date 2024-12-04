<?php
require 'funcs/conexion.php';
require 'funcs/funcs.php';

$user_id = $_POST['user_id'] ?? null;
$token = $_POST['token'] ?? null;
$password = $_POST['password'] ?? null;
$con_password = $_POST['con_password'] ?? null;

if (validaPassword($password, $con_password)) {
    $pass_hash = hashPassword($password);

    if (cambiaPassword($pass_hash, $user_id, $token, $pdo)) {
        echo "Password modified";
        echo "<br> <a href='EN_login.php'>Login</a>";
    } else {
        echo "Error al modificar el Password";
    }
} else {
    echo 'Las contraseñas no coinciden';
}
