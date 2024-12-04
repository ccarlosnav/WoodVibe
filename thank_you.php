<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You - WoodVibe</title>
  <link href="boss/assets/css/style.css" rel="stylesheet">
</head>
<body>
  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
      <h1 class="logo"><a href="EN_view_user.php">WoodVibe<span>.</span></a></h1>
    </div>
  </header>

  <!-- ======= Thank You Section ======= -->
  <section id="thank-you" class="thank-you section-bg">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>Thank You!</h2>
        <p>Your order has been placed successfully.</p>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="box">
            <p>Thank you for shopping with us. We will process your order and send you a confirmation email shortly.</p>
            <p><a href="EN_view_user.php">Return to Home</a></p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Thank You Section -->
</body>
</html>
