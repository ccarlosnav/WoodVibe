<?php
session_start();
require 'funcs/conexion.php'; // Archivo de conexión PDO

if (!isset($_SESSION['id_usuario'])) {
    error_log("Error: Usuario no autenticado intentando eliminar usuario.");
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit;
}

if (!isset($_POST['id'])) {
    error_log("Error: Intento de eliminación sin ID de usuario especificado.");
    echo json_encode(['success' => false, 'message' => 'ID de usuario no especificado.']);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    error_log("Error: ID de usuario no válido proporcionado: " . $_POST['id']);
    echo json_encode(['success' => false, 'message' => 'ID de usuario no válido.']);
    exit;
}

$servername = "localhost";
$username = "postgres";
$password = "postgres";
$database = "woodvibe";

try {
    $conn = new PDO("pgsql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el usuario existe antes de intentar eliminarlo
    $checkSql = "SELECT COUNT(*) FROM usuarios WHERE id = :id";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $checkStmt->execute();
    
    if ($checkStmt->fetchColumn() == 0) {
        error_log("Error: Intento de eliminar usuario inexistente con ID: " . $id);
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        exit;
    }

    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        error_log("Usuario eliminado correctamente. ID: " . $id);
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
    } else {
        error_log("Error al eliminar usuario. ID: " . $id . ". Error info: " . json_encode($stmt->errorInfo()));
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el usuario.']);
    }
} catch (PDOException $e) {
    error_log("Excepción PDO al eliminar usuario. ID: " . $id . ". Mensaje: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error de base de datos.']);
}
?>