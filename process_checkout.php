<?php
// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$host = "localhost";
$port = "5432";
$dbname = "woodvibe";
$user = "postgres";
$password = "postgres";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Error en la conexión a la base de datos: " . pg_last_error());
}

// Verificar si la sesión está iniciada y obtener detalles del usuario
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo '<p><script>Swal.fire({
            title: "Warning",
            text: "Please log in again."
            }).then(function() {
            window.location = "index.php";
            });</script></p>';
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$codigo_postal = $_POST['codigo_postal'];
$dui = $_POST['dui'];
$total = $_POST['total']; // Asegúrate de enviar este valor desde el frontend

// Insertar datos de la transacción en la base de datos
$insert_order_query = '
    INSERT INTO ventas (user_id, customer_name, email, direccion, codigo_postal, dui, total_price, created_at)
    VALUES ($1, $2, $3, $4, $5, $6, $7, NOW())
    RETURNING id
';
$result = pg_query_params($conn, $insert_order_query, array($id_usuario, $nombre, $email, $direccion, $codigo_postal, $dui, $total));

if (!$result) {
    die("Error en la inserción de datos de la transacción: " . pg_last_error($conn));
}

// Obtener el ID de la transacción recién insertada
$order_id = pg_fetch_result($result, 0, 'id');

// Insertar los productos del carrito en la base de datos
foreach ($_SESSION['carrito'] as $item) {
    $product_id = $item['id']; // Asumiendo que cada item en el carrito tiene un id de producto
    $quantity = $item['qty'];
    $price = $item['price'];
    $subtotal = $item['subtotal'];

    $insert_item_query = '
        INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
        VALUES ($1, $2, $3, $4, $5)
    ';
    $result_item = pg_query_params($conn, $insert_item_query, array($order_id, $product_id, $quantity, $price, $subtotal));

    if (!$result_item) {
        die("Error en la inserción de datos de los productos: " . pg_last_error($conn));
    }
}

// Vaciar el carrito después de completar la compra
unset($_SESSION['carrito']);

// Redirigir al usuario a una página de confirmación
header('Location: confirmation.php');
exit();
?>
