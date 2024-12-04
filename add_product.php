<?php
session_start();
require 'funcs/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos los datos del formulario
    $productName = $_POST['name'] ?? '';
    $productDescription = $_POST['description'] ?? '';
    $productNombreEs = $_POST['nombre_es'] ?? '';
    $productDescripcionEs = $_POST['descripcion_es'] ?? '';
    $productPrice = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $productStock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
    $productCategory = $_POST['category'] ?? '';

    // Validación de los datos
    if (empty($productName) || empty($productDescription) || empty($productNombreEs) || empty($productDescripcionEs) || $productPrice <= 0 || $productStock < 0 || empty($productCategory)) {
        if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
            echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields correctly.']);
        }
        exit;
    }

    // Manejo de la imagen
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target = "imagenes/" . basename($image);
        $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));

        // Validar tipo de imagen
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $validExtensions)) {
            if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
                echo json_encode(['success' => false, 'message' => 'Formato de imagen no válido. Solo se permiten JPG, JPEG, PNG y GIF.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.']);
            }
            exit;
        }

        // Mover la imagen
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $error = error_get_last();
            if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
                echo json_encode(['success' => false, 'message' => 'No se pudo subir la imagen.', 'debug' => $error]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload image.', 'debug' => $error]);
            }
            exit;
        }
    } else {
        if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
            echo json_encode(['success' => false, 'message' => 'Por favor, suba una imagen.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Please upload an image.']);
        }
        exit;
    }

    // Insertamos los datos en la base de datos
    try {
        $sql = "INSERT INTO mis_productos (name, description, price, image, stock, categoria_id, status, nombre_es, descripcion_es) VALUES (?, ?, ?, ?, ?, ?, '1', ?, ?)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$productName, $productDescription, $productPrice, $image, $productStock, $productCategory, $productNombreEs, $productDescripcionEs]);

        if ($result) {
            if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
                echo json_encode(['success' => true, 'message' => 'Producto agregado exitosamente']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Product added successfully']);
            }
        } else {
            if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
                echo json_encode(['success' => false, 'message' => 'No se pudo agregar el producto']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add product']);
            }
        }
    } catch (PDOException $e) {
        if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
            echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
} else {
    if (strpos($_SERVER['HTTP_REFERER'], 'ES_DBproducts.php') !== false) {
        echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    }
}
?>
