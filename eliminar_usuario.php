<?php
session_start();
require 'funcs/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

try {
    $conn = new PDO("pgsql:host=localhost;dbname=woodvibe", "postgres", "postgres");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el usuario']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}