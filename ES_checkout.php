<?php
// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'La-carta.php';
$cart = new Cart;
$product_price = $cart->total(); // Precio total del carrito
$error = "";

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

// Guardar los productos del carrito en la sesión
$_SESSION['carrito_productos'] = $cart->contents();
$_SESSION['subtotal'] = $cart->total();
$_SESSION['tax'] = $cart->total() * 0.13;
$_SESSION['totalAmount'] = $cart->total() + $cart->total() * 0.13;

// Inicializar variables para calcular subtotal, impuestos y total
$subtotal = $cart->total();
$taxRate = 0.13; // Tasa de impuestos (13%)

// Calcular impuestos y total
$taxAmount = $subtotal * $taxRate;
$total = $subtotal + $taxAmount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WoodVibe - Checkout</title>
  <!-- Favicons -->
  <link href="boss/assets/img/favicon.png" rel="icon">
  <link href="boss/assets/img/favicon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="boss/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="boss/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="boss/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="boss/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="boss/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="boss/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="boss/assets/css/style.css" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Include the PayPal JavaScript SDK -->
  <script src="https://www.paypal.com/sdk/js?client-id=AYfj5P9reKDIilhFq7esdy4LCN5vWrJ9mPaInFzRBVIBfRwdqZeaJuK_zxil6iCmvEFpB66pG7w3Di0N&currency=USD"></script>
  
    <!-- FontAwesome -->
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">

  <!-- Custom Styles -->
  <style>
    .checkout-form {
      background-color: #f9f9f9;
      padding: 30px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    
    .checkout-form .form-control {
      margin-bottom: 15px;
    }
    
    .checkout-form button {
      width: 100%;
      padding: 12px 20px;
    }
    
    @media (min-width: 768px) {
      .checkout-form {
        max-width: 600px;
        margin: 0 auto;
      }
    }
  </style>
</head>
<body>


     <!-- ======= Header ======= -->
     <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="logo"><a href="ES_view_user.php">WoodVibe<span>.</span></a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="ES_view_user.php">Inicio</a></li>
                    <li class="dropdown"><a href="#"><span>Categorías</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li class="dropdown"><a href="#"><span>Sala de Estar</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_sofas.php">Sofás</a></li>
                                    <li><a href="ES_tvfurniture.php">Muebles para TV</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Habitación</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_beds.php">Camas</a></li>
                                    <li><a href="ES_closets.php">Closets</a></li>
                                    <li><a href="ES_bedside_tables.php">Mesas de Noche</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Baño</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_open_cabinets.php">Gabinetes Abiertos</a></li>
                                    <li><a href="ES_wall_cabinets.php">Gabinetes de Pared</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Oficina</span> <i class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_desks.php">Escritorios</a></li>
                                    <li><a href="ES_office_chairs.php">Sillas de Oficina</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Cocina</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="ES_cabinets.php">Gabinetes</a></li>
                                    <li><a href="ES_chairs.php">Sillas</a></li>
                                    <li><a href="ES_kitchen_tables.php">Mesas</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li style="position: relative; list-style: none; display: inline-block; margin-right: 15px;">
                        <a class="nav-link scrollto" href="ES_Cart.php"
                            style="position: relative; display: inline-block;">
                            <i class="fas fa-shopping-cart"
                                style="color:#00BFFF; font-size: 1.2em; margin-right: 5px;"></i> Carrito
                        </a>
                    </li>
                    <li class="dropdown"><a href="#"><span>Idioma</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="checkout.php">Inglés</a></li>
                            <li><a href="ES_checkout.php">Español</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" style="display: flex; align-items: center;">
                            <i class="fas fa-user" style="color:#00BFFF; font-size: 1.2em; margin-right: 5px;"></i>
                            <span><?php echo htmlspecialchars($nombre); ?></span>
                            <i class="bi bi-chevron-down" style="margin-left: 5px;"></i>
                        </a>
                        <ul>
                            <li><a href="ES_user_purchases.php">Mis Compras</a></li>
                            <li><a href="ES_logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
        </div>
    </header><!-- End Header -->

  <!-- ======= Checkout Section ======= -->
  <section id="checkout" class="checkout section-bg">
    <div class="container" data-aos="fade-up">

      <div class="section-title">
        <h2>Checkout</h2>
        <h3>Complete su <span>Orden</span></h3>
        <p>Por favor, revise los detalles de su pedido y complete el pago.</p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="box checkout-form">
            <h3>Resumen del pedido</h3>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                  </tr>
                </thead>  
                <tbody>
                  <?php
                  if ($cart->total_items() > 0) {
                    // Obtener los ítems del carrito desde la sesión
                    $cartItems = $cart->contents();
                    foreach ($cartItems as $item) {
                  ?>
                      <tr>
                        <td><?php echo htmlspecialchars($item["name_es"]); ?></td>
                        <td><?php echo htmlspecialchars($item["qty"]); ?></td>
                        <td><?php echo '$' . htmlspecialchars($item["price"]) . ' '; ?></td>
                        <td><?php echo '$' . htmlspecialchars($item["subtotal"]) . ' '; ?></td>
                      </tr>
                    <?php }
                  } else { ?>
                    <tr>
                      <td colspan="4">
                        <p>Su carrito está vacio...</p>
                      </td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td colspan="3" class="text-end">Subtotal:</td>
                    <td><?php echo '$' . number_format($subtotal, 2); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="text-end">Tax (<?php echo ($taxRate * 100) . '%'; ?>):</td>
                    <td><?php echo '$' . number_format($taxAmount, 2); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong><?php echo '$' . number_format($total, 2); ?></strong></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Checkout Form -->
            <form id="checkout-form" action="process_checkout.php" method="POST">
              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="nombre">Nombre</label>
                  <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>" required>
                </div>
                <div class="col-md-6 form-group">
                  <label for="email">Correo</label>
                  <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($correo); ?>" required>
                </div>
              </div>
              <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea name="direccion" id="direccion" class="form-control" rows="2" required></textarea>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="ciudad">País</label>
                  <input class="form-control" type="text" name="country" value="El Salvador" readonly>
                </div>
                <div class="col-md-6 form-group">
                  <label for="ciudad">Ciudad</label>
                  <input class="form-control" type="text" name="state" value="Santa Tecla" readonly>
                </div>
                <div class="col-md-6 form-group">
                  <label for="codigo_postal">Postal Code</label>
                  <input type="text" name="codigo_postal" id="codigo_postal" maxlength="4" class="form-control" required oninput="formatPostalCode(this)">
                </div>
                <div class="col-md-6 form-group">
                  <label for="dui">DUI (Documento Único de Identidad)</label>
                  <input type="text" name="dui" id="dui" maxlength="10" class="form-control" required oninput="formatDUI(this)">
                </div>
              </div>
              <!-- PayPal Checkout -->
              <div id="paypal-button-container"></div>
              <script>
                function formatDUI(input) {
                  let value = input.value.replace(/\D/g, '');
                  if (value.length > 8) {
                    value = value.substring(0, 8) + '-' + value.substring(8, 9);
                  }
                  input.value = value;
                }
                function formatPostalCode(input) {
                  // Elimina cualquier carácter que no sea un número
                  let value = input.value.replace(/\D/g, '');

                  // Limita el valor a un máximo de 4 caracteres
                  if (value.length > 4) {
                    value = value.substring(0, 4);
                  }

                  // Asigna el valor formateado al campo de entrada
                  input.value = value;
                }

                function validateCheckoutForm() {
                  var nombre = document.getElementById('nombre').value.trim();
                  var email = document.getElementById('email').value.trim();
                  var direccion = document.getElementById('direccion').value.trim();
                  var codigo_postal = document.getElementById('codigo_postal').value.trim();
                  var dui = document.getElementById('dui').value.trim();

                  if (nombre === '' || email === '' || direccion === '' || codigo_postal === '' || dui === '') {
                    Swal.fire({
                      icon: 'error',
                      title: 'ERROR',
                      text: 'Debes llenar los espacios en blanco para continuar.',
                    });
                    return false;
                  }

                  var postalCodePattern = /^\d{4}$/;
                  if (!postalCodePattern.test(codigo_postal)) {
                    Swal.fire({
                      icon: 'error',
                      title: 'ERROR',
                      text: 'El código postal debe tener exactamente 4 dígitos.',
                    });
                    return false;
                  }

                  var duiPattern = /^\d{8}-\d{1}$/;
                  if (!duiPattern.test(dui)) {
                    Swal.fire({
                      icon: 'error',
                      title: 'ERROR',
                      text: 'DUI debe estar en el formato 12345678-9.',
                    });
                    return false;
                  }

                  return true;
                }

                paypal.Buttons({
                  createOrder: function(data, actions) {
                    if (!validateCheckoutForm()) {
                      return; // Detener el proceso si la validación falla
                    }

                    return actions.order.create({
                      purchase_units: [{
                        amount: {
                          value: '<?php echo number_format($total, 2, '.', ''); ?>'
                        }
                      }]
                    });
                  },
                  onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                      Swal.fire({
                        icon: 'success',
                        title: 'Transacción completada',
                        text: 'Transacción completada por ' + details.payer.name.given_name,
                      }).then(function() {
                        window.location.href = 'ES_AccionCarta.php?action=placeOrder'; // Redirige a la página de agradecimiento
                      });
                    });
                  },
                  onCancel: function(data) {
                    Swal.fire({
                      icon: 'error',
                      title: 'Pago cancelado',
                      text: 'El pago ha sido cancelado. Por favor, inténtelo de nuevo.',
                    }).then(function() {
                      window.location.href = 'ES_checkout.php'; // Redirige a la página de IDcompra
                    });
                  }
                }).render('#paypal-button-container');
              </script>
              <!-- Fin PayPal Checkout -->

            </form>
            <!-- Fin Checkout Form -->

          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Fin Checkout Section -->

    <!-- ======= Footer ======= -->
    <footer id="footer">


<div class="footer-top">
  <div class="container">
    <div class="row">

      <div class="col-lg-3 col-md-6 footer-contact">
        <h3>WoodVibe<span>.</span></h3>
        <p>
          Santa Tecla <br>
          El Salvador<br><br>
          <strong>Phone:</strong> +503 1234 5678<br>
          <strong>Email:</strong> WoodVibe.shop@gmail.com<br>
        </p>
      </div>

      <div class="col-lg-3 col-md-6 footer-links">
        <h4>Useful Links</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="EN_view_user.php">Home</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#checkout">checkout</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="ES_logout.php">LogOut</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6 footer-links">
        <h4>Our Services</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6 footer-links">
        <h4>Language</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="checkout.php">English</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="ES_checkout.php">Spanish</a></li>

        </ul>
      </div>

    </div>
  </div>
</div>

<div class="container py-4">
  <div class="copyright">
    &copy; Copyright <strong><span>WoodVibe</span></strong>. All Rights Reserved
  </div>
  <div class="credits">

  </div>
</div>
</footer><!-- End Footer -->

<div id="preloader"></div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="boss/assets/vendor/aos/aos.js"></script>
  <script src="boss/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="boss/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="boss/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="boss/assets/vendor/php-email-form/validate.js"></script>
  <script src="boss/assets/vendor/purecounter/purecounter.js"></script>
  <script src="boss/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="boss/assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="boss/assets/js/main.js"></script>

</body>
</html>
