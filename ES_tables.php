<?php
session_start();
require 'funcs/conexion.php'; // Archivo de conexión PDO
require 'funcs/funcs.php'; // Archivo con funciones útiles

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$nombre = $_SESSION['nombre']; // Obtener nombre de usuario
$tipo_usuario = $_SESSION['tipo_usuario'];
$id = $_SESSION['id_usuario'];

$usuarios = [];

try {
    $conn = new PDO("pgsql:host=localhost;dbname=woodvibe", "postgres", "postgres");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($tipo_usuario == 1) {
        $sql = "SELECT id, usuario, correo, nombre, id_tipo FROM usuarios";
        $stmt = $conn->prepare($sql);
    } else if ($tipo_usuario == 2) {
        $sql = "SELECT id, usuario, correo, nombre, id_tipo FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }

    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;
$edit_usuario = [];
if (!empty($edit_id)) {
    try {
        $sql = "SELECT id, usuario, correo, nombre, id_tipo FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $edit_id, PDO::PARAM_INT);
        $stmt->execute();
        $edit_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener datos del usuario: " . $e->getMessage();
        exit;
    }
}

// Procesar el formulario de edición si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $usuario = $_POST['usuario'];
        $correo = $_POST['correo'];
        $nombre = $_POST['nombre'];
        $id_tipo = $_POST['id_tipo'];

        try {
            $sql = "UPDATE usuarios SET usuario = :usuario, correo = :correo, nombre = :nombre, id_tipo = :id_tipo WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['success' => false]);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];

        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['success' => false]);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}
?>
