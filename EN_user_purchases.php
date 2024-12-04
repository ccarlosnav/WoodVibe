<?php
session_start();

include_once 'funcs/conexion.php';
include_once 'funcs/funcs.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$id = $_SESSION['id_usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

// Configuración de la base de datos
$dsn = "pgsql:host=localhost;port=5432;dbname=woodvibe;";
$username = "postgres";
$password = "postgres";

try {
    // Crear conexión a la base de datos
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener los datos de la tabla "orden" para el usuario actual
    $sql = "SELECT id, total_price, created, modified, status, product_names, quantities FROM orden WHERE customer_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generar la tabla de resultados
    ob_start(); // Iniciar almacenamiento en buffer
    if (count($result) > 0) {
        echo "<table id='ordersTable' class='blue-table'>";
        echo "<thead><tr>";
        echo "<th>ID</th><th>Total Price</th><th>Created</th><th>Modified</th><th>Status</th><th>Product Names</th><th>Quantities</th>";
        echo "</tr></thead><tbody>";

        foreach ($result as $row) {
            echo "<tr id='row_" . $row['id'] . "'>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>$" . number_format($row['total_price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['created']) . "</td>";
            echo "<td>" . htmlspecialchars($row['modified']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>" . htmlspecialchars($row['product_names']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantities']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No orders found for this user.";
    }
    $table_html = ob_get_clean(); // Obtener el contenido del buffer y limpiar
} catch (PDOException $e) {
    error_log('Error en la conexión a la base de datos: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database connection error.']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>WoodVibe - My Purchases</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="boss/assets/img/favicon.png" rel="icon">
    <link href="boss/assets/img/favicon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="boss/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="boss/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="boss/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="boss/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="boss/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Products Design CSS File -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
        integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link href="imagenes" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="boss/assets/css/style.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .blue-table {
            width: 100%;
            border-collapse: collapse;
        }

        .blue-table thead {
            background-color: #007bff;
            color: white;
        }

        .blue-table th,
        .blue-table td {
            border: 1px solid #007bff;
            padding: 10px;
            text-align: left;
        }

        .blue-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .blue-table tbody tr:hover {
            background-color: #e0e0e0;
        }

        /* Estilos para la nueva navbar */
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
        }

        .ms-auto {
            margin-left: auto !important;
        }

        .dropdown-menu {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
        }

        .dropdown-item i {
            margin-right: 5px;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
        }

        .d-none.d-md-inline {
            display: inline-block !important;
        }

        .navbar {
            position: relative;
        }

        .nav-item.dropdown.ms-auto {
            position: absolute;
            right: 0;
        }
    </style>

</head>

<body class="sb-nav-fixed">

    <!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="logo"><a href="EN_view_user.php">WoodVibe<span>.</span></a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="EN_view_user.php">Home</a></li>
                    <li class="dropdown"><a href="#"><span>Categories</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li class="dropdown"><a href="#"><span>Living Room</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_sofas.php">Sofas</a></li>
                                    <li><a href="EN_tvfurniture.php">TV Furniture</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Bedroom</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_beds.php">Beds</a></li>
                                    <li><a href="EN_closets.php">Closets</a></li>
                                    <li><a href="EN_bedside_tables.php">Bedside tables</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Bathroom</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_open_cabinets.php">Open cabinets</a></li>
                                    <li><a href="EN_wall_cabinets.php">Wall cabinets</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Office</span> <i class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_desks.php">Desks</a></li>
                                    <li><a href="EN_office_chairs.php">Office chairs</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#"><span>Kitchen</span> <i
                                        class="bi bi-chevron-right"></i></a>
                                <ul>
                                    <li><a href="EN_cabinets.php">Cabinets</a></li>
                                    <li><a href="EN_chairs.php">Chairs</a></li>
                                    <li><a href="EN_kitchen_tables.php">Tables</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li style="position: relative; list-style: none; display: inline-block; margin-right: 15px;">
                        <a class="nav-link scrollto" href="EN_Cart.php"
                            style="position: relative; display: inline-block;">
                            <i class="fas fa-shopping-cart"
                                style="color:#00BFFF; font-size: 1.2em; margin-right: 5px;"></i> Cart
                        </a>
                    </li>
                    <li class="dropdown"><a href="#"><span>Language</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="EN_user_purchases.php">English</a></li>
                            <li><a href="ES_user_purchases.php">Spanish</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" style="display: flex; align-items: center;">
                            <i class="fas fa-user" style="color:#00BFFF; font-size: 1.2em; margin-right: 5px;"></i>
                            <span><?php echo htmlspecialchars($nombre); ?></span>
                            <i class="bi bi-chevron-down" style="margin-left: 5px;"></i>
                        </a>
                        <ul>
                            <li><a href="EN_user_purchases.php">My purchase</a></li>
                            <li><a href="logout.php">LogOut</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
        </div>
    </header><!-- End Header -->

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">My Purchases</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">My Registered Purchases</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <button id="generateReport" class="btn btn-danger mb-3 d-flex align-items-center">
                            <span class="me-3">Generate Report</span>
                            <i class="fas fa-file-pdf fa-lg" style="margin-left: 0.75rem;"></i>
                        </button>
                        <i class="fas fa-table mr-1"></i>
                        My Registered Purchases
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php echo $table_html; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

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
                            <li><i class="bx bx-chevron-right"></i> <a href="#layoutSidenav_content">Ordens</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="logout.php">LogOut</a></li>
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
                            <li><i class="bx bx-chevron-right"></i> <a href="EN_user_purchases.php">English</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="ES_user_purchases.php">Spanish</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="container py-4">
            <div class="copyright">
                &copy; Copyright <strong><span>WoodVibe</span></strong>. All Rights Reserved
            </div>
        </div>
    </footer><!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <script src="boss/assets/js/main.js"></script>

    <!-- Bootstrap core JS-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <!-- Data Tables -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.min.js"></script>
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#ordersTable').DataTable({
                "language": {
                    "lengthMenu": "Show _MENU_",
                    "search": "Search:"
                }
            });

            $('#generateReport').on('click', function () {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                let pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight();
                let pageWidth = doc.internal.pageSize.width || doc.internal.pageSize.getWidth();
                let y = 10;

                doc.setFontSize(20);
                doc.text("My Orders Report", pageWidth / 2, y, {
                    align: 'center'
                });
                y += 10;

                const headers = [
                    ["ID", "Total Price", "Purchased", "Purchased", "Status", "Product Names", "Quantities"]
                ];
                const rows = [];

                document.querySelectorAll('#ordersTable tbody tr').forEach(row => {
                    const rowData = [];
                    row.querySelectorAll('td').forEach(cell => {
                        rowData.push(cell.innerText);
                    });
                    rows.push(rowData);
                });

                doc.autoTable({
                    head: headers,
                    body: rows,
                    startY: y,
                    styles: {
                        overflow: 'linebreak',
                        fontSize: 10,
                        cellPadding: 2,
                        halign: 'center'
                    },
                    theme: 'grid',
                    headStyles: {
                        fillColor: [22, 160, 133],
                        textColor: [255, 255, 255]
                    }
                });

                doc.save('my_orders_report.pdf');
            });
        });
    </script>
</body>

</html>