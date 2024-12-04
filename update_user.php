<?php
session_start();
require 'funcs/conexion.php'; // Archivo de conexión PDO
 
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
}
 
$id = $_POST['id'];
$usuario = $_POST['edit_usuario'];
$correo = $_POST['edit_correo'];
$nombre = $_POST['edit_nombre'];
$id_tipo = $_POST['edit_id_tipo'];
 
// Configuración de la conexión PDO
$servername = "localhost";
$username = "postgres";
$password = "postgres";
$database = "woodvibe";
 
try {
    $conn = new PDO("pgsql:host=$servername;dbname=$database", $username, $password);
    // Habilitar excepciones de PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    // Consulta preparada para actualizar usuario
    $sql = "UPDATE usuarios SET usuario = :usuario, correo = :correo, nombre = :nombre, id_tipo = :id_tipo WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
 
    // Redirigir de nuevo a la página de usuarios
    header("Location: DBusers.php");
    exit();
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
    exit;
}
?>