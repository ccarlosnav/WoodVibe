<?php
// Configuración de la base de datos
$dsn = "pgsql:host=localhost;port=5432;dbname=woodvibe;";
$username = "postgres";
$password = "postgres";

try {
    // Crear conexión a la base de datos
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener ID del producto desde la solicitud
    $productId = isset($_POST['id']) ? $_POST['id'] : '';

    // Verificar que se ha proporcionado un ID de producto válido
    if (!empty($productId)) {
        // Consulta SQL para eliminar el producto
        $sql = "DELETE FROM mis_productos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$productId]);

        // Verificar si se eliminó el producto
        if ($stmt->rowCount() > 0) {
            if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
                echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Product successfully deleted']);
            }
        } else {
            if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
                echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Product not found']);
            }
        }
    } else {
        if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
            echo json_encode(['success' => false, 'error' => 'ID de producto no válido']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
        }
    }
} catch (PDOException $e) {
    if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
        echo json_encode(['success' => false, 'error' => 'Error en la conexión a la base de datos']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database connection error']);
    }
}
?>
