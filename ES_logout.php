<?php
session_start();

include_once 'La-carta.php';
$cart = new Cart;
include_once 'Configuracion.php';

try {
    $dsn = "pgsql:host=localhost;port=5432;dbname=woodvibe;";
    $username = "postgres";
    $password = "postgres";
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Guardar el carrito en la base de datos antes de destruir la sesión
    saveCartToDatabase($db, $cart, $_SESSION['id_usuario']);
    
} catch (PDOException $e) {
    die("No hay conexión con la base de datos: " . $e->getMessage());
}

// Destruir la sesión y redirigir al inicio
// $cart->destroy();
session_destroy();
header("Location: ES_index.php");
exit();

function saveCartToDatabase($db, $cart, $id_usuario) {
    $cartItems = $cart->contents();

    foreach ($cartItems as $item) {
        $stmt = $db->prepare("INSERT INTO carrito (id_usuario, id_producto, nombre_producto, cantidad, precio) VALUES (:id_usuario, :id_producto, :nombre_producto, :cantidad, :precio)");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $item['id'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre_producto', $item['name'], PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $item['qty'], PDO::PARAM_INT);
        $stmt->bindParam(':precio', $item['price'], PDO::PARAM_STR);
        $stmt->execute();
    }
}
?>
