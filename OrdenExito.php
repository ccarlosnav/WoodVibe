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

if (!isset($_REQUEST['id'])) {
    header("Location: index.php");
    exit();
}

$orderId = $_GET['id'];

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo '<p><script>Swal.fire({
            title: "Warning",
            text: "Please log in again."
            }).then(function() {
            window.location = "index.php";
            });</script></p>';
    exit();
}

// Obtener detalles del usuario de la sesión
$id_usuario = $_SESSION['id_usuario'];
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$correo = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';

// Si el correo no está en la sesión, obtenerlo de la base de datos
if (empty($correo)) {
    $result = pg_query_params($conn, 'SELECT nombre, correo FROM usuarios WHERE id = $1', array($id_usuario));
    if ($result) {
        $row = pg_fetch_assoc($result);
        $nombre = $row['nombre'];
        $correo = $row['correo'];

        // Actualizar la sesión con los valores obtenidos
        $_SESSION['nombre'] = $nombre;
        $_SESSION['correo'] = $correo;
    } else {
        echo "Error en la consulta: " . pg_last_error($conn);
        exit();
    }
}

// Obtener productos del carrito desde la sesión
$carrito_productos = isset($_SESSION['carrito_productos']) ? $_SESSION['carrito_productos'] : [];
$subtotal = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0;
$tax = isset($_SESSION['tax']) ? $_SESSION['tax'] : 0;
$totalAmount = isset($_SESSION['totalAmount']) ? $_SESSION['totalAmount'] : 0;

// Consultar la base de datos para obtener detalles de los productos comprados
$product_details = [];
foreach ($carrito_productos as $producto) {
    $product_id = $producto['id'];
    $result = pg_query_params($conn, 'SELECT name, price FROM mis_productos WHERE id = $1', array($product_id));
    if ($result) {
        $row = pg_fetch_assoc($result);
        if ($row) {
            $row['qty'] = $producto['qty'];
            $row['subtotal'] = $producto['qty'] * $row['price'];
            $product_details[] = $row;
        }
    }
}

pg_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Order Completed - Cart</title>
    <meta charset="utf-8">
    <style>
    .container {
        padding: 20px;
    }

    p {
        color: #34a853;
        font-size: 18px;
    }

    .swal2-title-large {
        font-size: 24px;
        /* Aumenta el tamaño del título */
    }

    .swal2-popup-large {
        font-size: 18px;
        /* Aumenta el tamaño del texto en el popup */
    }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul class="nav nav-pills">
                    <li role="presentation" class="active"><a href="#" id="backToStore">Back to the store</a></li>
                </ul>
            </div>
            <div class="panel-body">
                <h1>Status of your request</h1>
                <p>The Order has been shipped successfully. Your order ID is <?php echo htmlspecialchars($orderId); ?>
                </p>
                <button id="downloadReceipt" class="btn btn-primary">Download Receipt</button>
            </div>
        </div>
    </div>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>
    <script>
    let receiptDownloaded = false;

    document.getElementById('downloadReceipt').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();

        const orderId = "<?php echo htmlspecialchars($orderId); ?>";
        const fullName = "<?php echo htmlspecialchars($nombre); ?>";
        const email = "<?php echo htmlspecialchars($correo); ?>";
        const productos = <?php echo json_encode($product_details); ?>;
        const subtotal = "<?php echo htmlspecialchars($subtotal); ?>";
        const tax = "<?php echo htmlspecialchars($tax); ?>";
        const totalAmount = "<?php echo htmlspecialchars($totalAmount); ?>";

        // Encabezado
        doc.setFontSize(22);
        doc.text("Your Receipt", 105, 20, null, null, "center");

        // Información del cliente
        doc.setFontSize(16);
        doc.text(`Order ID: ${orderId}`, 20, 40);
        doc.text(`Full Name: ${fullName}`, 20, 50);
        doc.text(`Email: ${email}`, 20, 60);

        // Información de la orden
        if (Array.isArray(productos) && productos.length > 0) {
            doc.autoTable({
                startY: 70,
                head: [
                    ['Product', 'Quantity', 'Price', 'Total']
                ],
                body: productos.map(item => [item.name, item.qty, `$${item.price}`,
                    `$${item.subtotal}`])
            });

            // Totales
            const finalY = doc.lastAutoTable.finalY;
            doc.setFontSize(16);
            doc.text(`Subtotal: $${subtotal}`, 20, finalY + 10);
            doc.text(`Tax (13%): $${tax}`, 20, finalY + 20);
            doc.text(`Total: $${totalAmount}`, 20, finalY + 30);
        } else {
            doc.text("No products found for this order.", 20, 70);
        }

        doc.save(`OrderReceipt_${orderId}.pdf`);

        receiptDownloaded = true; // Marcar que el recibo fue descargado
    });

    document.getElementById('backToStore').addEventListener('click', function(event) {
        if (!receiptDownloaded) {
            event.preventDefault();
            Swal.fire({
                title: "Warning",
                text: "Please download your receipt before going back to the store.",
                icon: "warning",
                width: '600px', // Aumenta el ancho de la alerta
                customClass: {
                    title: 'swal2-title-large', // Clase personalizada para el título
                    popup: 'swal2-popup-large' // Clase personalizada para el popup
                }
            });
        } else {
            window.location.href = "EN_view_user.php"; // Redirigir si se descargó el recibo
        }
    });
    </script>
</body>

</html>