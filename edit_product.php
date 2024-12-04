<?php
// Configuración de la base de datos
$dsn = "pgsql:host=localhost;port=5432;dbname=woodvibe;";
$username = "postgres";
$password = "postgres";

try {
    // Crear conexión a la base de datos
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos del producto desde la solicitud
    $productId = isset($_POST['id']) ? $_POST['id'] : '';
    $productName = isset($_POST['name']) ? $_POST['name'] : '';
    $productDescription = isset($_POST['description']) ? $_POST['description'] : '';
    $productNombreEs = isset($_POST['nombre_es']) ? $_POST['nombre_es'] : '';
    $productDescripcionEs = isset($_POST['descripcion_es']) ? $_POST['descripcion_es'] : '';
    $productPrice = isset($_POST['price']) ? $_POST['price'] : '';
    $productStock = isset($_POST['stock']) ? $_POST['stock'] : '';
    $productCategory = isset($_POST['category']) ? $_POST['category'] : '';
    $productImage = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

    if (!empty($productId) && !empty($productName) && !empty($productDescription) && !empty($productNombreEs) && !empty($productDescripcionEs) && !empty($productPrice) && !empty($productStock) && !empty($productCategory)) {
        // Manejar la subida de la imagen si se proporciona una nueva
        if (!empty($productImage)) {
            $targetDir = "imagenes/";
            $targetFile = $targetDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

            // Actualizar producto con nueva imagen
            $sql = "UPDATE mis_productos SET name = ?, description = ?, nombre_es = ?, descripcion_es = ?, price = ?, stock = ?, categoria_id = ?, image = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$productName, $productDescription, $productNombreEs, $productDescripcionEs, $productPrice, $productStock, $productCategory, $productImage, $productId]);
        } else {
            // Actualizar producto sin cambiar la imagen
            $sql = "UPDATE mis_productos SET name = ?, description = ?, nombre_es = ?, descripcion_es = ?, price = ?, stock = ?, categoria_id = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$productName, $productDescription, $productNombreEs, $productDescripcionEs, $productPrice, $productStock, $productCategory, $productId]);
        }

        if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
            echo json_encode(['success' => true, 'message' => 'Producto actualizado exitosamente']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
        }
    } else {
        if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
            echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios']);
        } else {
            echo json_encode(['success' => false, 'error' => 'All fields are required']);
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
