<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['nombre'];
$user_type = $_SESSION['tipo_usuario'];

// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'woodvibe';
$username = 'postgres';
$password = 'postgres';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el número de usuarios registrados
    $stmt_users = $pdo->query("SELECT COUNT(*) FROM usuarios");
    $num_users = $stmt_users->fetchColumn();

    // Obtener el número de productos registrados
    $stmt_products = $pdo->query("SELECT COUNT(*) as count FROM mis_productos");
    $num_products = $stmt_products->fetchColumn();

    // Obtener el número de ventas registradas
    $stmt_sales = $pdo->query("SELECT COUNT(*) FROM orden");
    $num_sales = $stmt_sales->fetchColumn();

} catch (PDOException $e) {
    error_log("Connection error: " . $e->getMessage());
    $num_users = 0;
    $num_products = 0;
    $num_sales = 0;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>WoodVibe - Panel de Control</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        .card {
            border-radius: 10px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-body {
            font-size: 1.2rem;
        }

        .card-footer a {
            text-decoration: none;
            font-weight: bold;
        }

        .card.bg-primary {
            background-color: #007bff !important;
            color: white;
        }

        .card.bg-success {
            background-color: #28a745 !important;
            color: white;
        }

        .card.bg-warning {
            background-color: #ffc107 !important;
            color: white;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        /* Estilo adicional para las gráficas */
        .chart-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .download-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        .download-btn:hover {
            background-color: #c82333;
        }

        .download-btn i {
            margin-left: 8px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="DB.php">Panel de Control</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <div class="input-group-append"></div>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i><?php echo htmlspecialchars($name); ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menú Principal</div>
                        <a class="nav-link" href="ES_DB.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Panel de Control
                        </a>
                        <div class="sb-sidenav-menu-heading">Configuración</div>
                        <a class="nav-link" href="ES_DBproducts.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Productos
                        </a>
                        <a class="nav-link" href="ES_DBusers.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Usuarios
                        </a>
                        <a class="nav-link" href="ES_DBsales.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                            Ventas
                        </a>
                        <div class="sb-sidenav-menu-heading">Idioma</div>
                        <a class="nav-link" href="DB.php">
                            <div class="sb-nav-link-icon"></div>
                            Inglés
                        </a>
                        <a class="nav-link" href="ES_DB.php">
                            <div class="sb-nav-link-icon"></div>
                            Español
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Conectado como:</div>
                    <?php echo htmlspecialchars($name); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Panel de Control</h1>
                    <ol class="breadcrumb mb=4">
                        <li class="breadcrumb-item active">Panel de Control</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-user card-icon"></i>
                                            Usuarios: <?php echo htmlspecialchars($num_users); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="ES_DBusers.php">Ver detalles</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-box-open card-icon"></i>
                                            Productos: <?php echo htmlspecialchars($num_products); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="ES_DBproducts.php">Ver detalles</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-dollar-sign card-icon"></i>
                                            Ventas: <?php echo htmlspecialchars($num_sales); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="ES_DBsales.php">Ver detalles</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección para la gráfica de pastel -->
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="chart-card">
                                <div class="chart-title">Comparación de Usuarios Registrados, Productos y Ventas</div>
                                <canvas id="comparisonChart"></canvas>
                                <button id="downloadBtn" class="download-btn">
                                    Generar Reporte
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; WoodVibe 2024</div>
                        <div></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script>
        // Gráfica de comparación entre Usuarios, Productos y Ventas
        const comparisonData = {
            labels: ['Usuarios Registrados', 'Productos Registrados', 'Ventas'],
            datasets: [{
                data: [<?php echo $num_users; ?>, <?php echo $num_products; ?>, <?php echo $num_sales; ?>],
                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
            }]
        };

        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        const comparisonChart = new Chart(comparisonCtx, {
            type: 'pie',
            data: comparisonData,
            options: {
                responsive: true
            }
        });

        // Descargar la gráfica como PDF
        document.getElementById('downloadBtn').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Añadir la gráfica al PDF
            doc.text('Comparación de Usuarios Registrados, Productos y Ventas', 10, 10);
            const canvas = document.getElementById('comparisonChart');
            const imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 10, 20, 180, 160);

            // Descargar el PDF
            doc.save('grafica-comparacion.pdf');
        });
    </script>
</body>

</html>
