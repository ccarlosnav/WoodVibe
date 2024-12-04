<?php
session_start();
include_once 'funcs/conexion.php';
include_once 'funcs/funcs.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Configuración de la base de datos
$dsn = "pgsql:host=localhost;port=5432;dbname=woodvibe;";
$username = "postgres";
$password = "postgres";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['add_product.php'])) {
        // Código para agregar producto
        // ...
    } elseif (isset($_POST['edit_product.php'])) {
        // Código para editar producto
        // ...
    } elseif (isset($_POST['delete_product.php'])) {
        // Código para eliminar producto
        // ...
    } elseif (isset($_POST['delete_product.php'])) {
        // Código para eliminar producto
        // ...
    }
    else {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
    }
} catch (PDOException $e) {
    error_log('Error en la conexión a la base de datos: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database connection error.']);
}