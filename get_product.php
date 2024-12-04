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
    $productId = isset($_GET['id']) ? $_GET['id'] : '';

    // Verificar que se ha proporcionado un ID de producto válido
    if (!empty($productId)) {
        // Consulta SQL para obtener los detalles del producto
        $sql = "SELECT * FROM mis_productos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$productId]);

        // Obtener el producto como un arreglo asociativo
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró el producto
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['success' => false, 'error' => 'Product not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
    }
} catch (PDOException $e) {
    error_log('Error en la conexión a la base de datos: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database connection error']);
}
?>
