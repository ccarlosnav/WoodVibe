<?php
session_start();
require 'funcs/conexion.php';
require 'funcs/funcs.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $id_tipo = filter_input(INPUT_POST, 'id_tipo', FILTER_SANITIZE_NUMBER_INT);

    if (!$id || !$usuario || !$correo || !$nombre || !$id_tipo) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit;
    }

    try {
        $conn = new PDO("pgsql:host=localhost;dbname=woodvibe", "postgres", "postgres");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE usuarios SET usuario = :usuario, correo = :correo, nombre = :nombre, id_tipo = :id_tipo WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>