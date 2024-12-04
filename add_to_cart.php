<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Asegúrate de que el carrito esté inicializado
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Agregar el producto al carrito o incrementar la cantidad
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    // Devolver una respuesta exitosa
    echo json_encode(array('status' => 'success'));
} else {
    // Devolver una respuesta de error si no se proporciona un ID de producto
    echo json_encode(array('status' => 'error', 'message' => 'Product ID missing.'));
}
?>
