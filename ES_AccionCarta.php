<?php
date_default_timezone_set("America/Lima");
include_once 'La-carta.php';
$cart = new Cart;

include_once 'Configuracion.php';

// Verificar si la sesión no está iniciada antes de llamarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo '<p><script>Swal.fire({
            title: "Advertencia",
            text: "Por favor, inicie sesión nuevamente."
            }).then(function() {
            window.location = "index.php";
            });</script></p>';
    exit();
}

try {
    $dsn = "pgsql:host=localhost;port=5432;dbname=woodvibe;";
    $username = "postgres";
    $password = "postgres";
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No hay conexión con la base de datos: " . $e->getMessage());
}

function logMessage($message)
{
    file_put_contents('log.txt', date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}


try {
    if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
        log_message("Action: " . $_REQUEST['action'] . " ID: " . $_REQUEST['id'] . " Qty: " . $_REQUEST['qty']);

        if ($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id']) && !empty($_REQUEST['qty'])) {
            $qty = filter_var($_REQUEST['qty'], FILTER_VALIDATE_INT);
            if ($qty === false || $qty < 0) {
                echo json_encode(['status' => 'error', 'message' => 'Cantidad inválida']);
                exit();
            }

            $productID = filter_var($_REQUEST['id'], FILTER_VALIDATE_INT);
            if ($productID === false) {
                echo json_encode(['status' => 'error', 'message' => 'ID de producto inválido']);
                exit();
            }

            $productSession = $_REQUEST['idSession'];
            if (!preg_match('/^[a-zA-Z0-9]+$/', $productSession)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de sesión de producto inválido']);
                exit();
            }

            // Obtener el stock actual del producto
            $stmt = $db->prepare("SELECT stock FROM mis_productos WHERE id = :id");
            $stmt->bindValue(':id', $productID, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                if ($qty <= $product['stock']) {
                    $itemData = array(
                        'rowid' => $productSession,
                        'qty' => $qty
                    );
                    $updateItem = $cart->update($itemData);
                    if ($updateItem) {
                        echo json_encode(['status' => 'success', 'message' => 'Artículo actualizado correctamente']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el artículo']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'La cantidad excede el stock disponible']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Producto no encontrado']);
            }
        } elseif ($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {
            $productID = filter_var($_REQUEST['id'], FILTER_VALIDATE_INT);
            if ($productID === false) {
                echo json_encode(['status' => 'error', 'message' => 'ID de producto inválido']);
                exit();
            }

            $stmt = $db->prepare("SELECT * FROM mis_productos WHERE id = :id");
            $stmt->bindValue(':id', $productID, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                if ($row['stock'] < 1) {
                    echo json_encode(['status' => 'error', 'message' => 'No hay suficiente stock disponible']);
                    exit();
                }

                $itemData = array(
                    'id' => $row['id'],
                    'name' => $row['name'],  // Utilizando el nombre en inglés
                    'name_es' => $row['nombre_es'],
                    'price' => $row['price'],
                    'image' => $row['image'],
                    'qty' => 1
                );

                $insertItem = $cart->insert($itemData);
                $redirectLoc = $insertItem ? 'ES_Cart.php' : 'index.php';
                header("Location: " . $redirectLoc);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Producto no encontrado']);
            }
        } elseif ($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])) {
            $productSession = $_REQUEST['id'];

            if (!preg_match('/^[a-zA-Z0-9]+$/', $productSession)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de producto inválido: ' . htmlspecialchars($productSession)]);
                exit();
            }

            $deleteItem = $cart->remove($productSession);
            if ($deleteItem) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el artículo']);
            }
        } elseif ($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['id_usuario'])) {
            $cartItems = $cart->contents();
            $productNames = [];
            $productNamesEs = [];
            $quantities = [];

            foreach ($cartItems as $item) {
                $productNames[] = $item['name'];  // Nombre en inglés
                $productNamesEs[] = $item['name_es'];
                $quantities[] = $item['qty'];
            }

            $productNamesStr = implode(',', $productNames);
            $productNamesEsStr = implode(',', $productNamesEs);
            $quantitiesStr = implode(',', $quantities);

            $stmt = $db->prepare("INSERT INTO orden (customer_id, total_price, created, modified, status, product_names, product_names_es, quantities) VALUES (:customer_id, :total_price, :created, :modified, 1, :product_names, :product_names_es, :quantities)");
            $stmt->bindParam(':customer_id', $_SESSION['id_usuario'], PDO::PARAM_INT);
            $stmt->bindParam(':total_price', $cart->total(), PDO::PARAM_STR);
            $stmt->bindParam(':created', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindParam(':modified', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindParam(':product_names', $productNamesStr, PDO::PARAM_STR);
            $stmt->bindParam(':product_names_es', $productNamesEsStr, PDO::PARAM_STR);
            $stmt->bindParam(':quantities', $quantitiesStr, PDO::PARAM_STR);
            $insertOrder = $stmt->execute();

            if ($insertOrder) {
                $orderID = $db->lastInsertId('orden_id_seq');
                log_message("Order ID: " . $orderID);

                $stmt = $db->prepare("UPDATE mis_productos SET stock = stock - :qty WHERE id = :id");
                foreach ($cartItems as $item) {
                    $stmt->bindParam(':qty', $item['qty'], PDO::PARAM_INT);
                    $stmt->bindParam(':id', $item['id'], PDO::PARAM_INT);
                    $stmt->execute();
                }

                $cart->destroy();
                header("Location: ES_OrdenExito.php?id=$orderID");
            } else {
                log_message("Error: No se pudo insertar la orden");
                header("Location: Pagos.php");
            }
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
} catch (Throwable $th) {
    log_message("Error: " . $th->getMessage());
    echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
}

// Función para guardar el carrito en la base de datos al desloguear
function saveCartToDatabase($db, $cart, $id_usuario) {
    $cartItems = $cart->contents();

    foreach ($cartItems as $item) {
        $stmt = $db->prepare("INSERT INTO carrito (id_usuario, id_producto, nombre_producto, cantidad, precio) VALUES (:id_usuario, :id_producto, :nombre_producto, :cantidad, :precio)");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $item['id'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre_producto', $item['name'], PDO::PARAM_STR);  // Nombre en inglés
        $stmt->bindParam(':cantidad', $item['qty'], PDO::PARAM_INT);
        $stmt->bindParam(':precio', $item['price'], PDO::PARAM_STR);
        $stmt->execute();
    }
}

// Al desloguear, llama a esta función antes de destruir la sesión
if ($_REQUEST['action'] == 'logout') {
    saveCartToDatabase($db, $cart, $_SESSION['id_usuario']);
    $cart->destroy();
    session_destroy();
    header("Location: index.php");
}
