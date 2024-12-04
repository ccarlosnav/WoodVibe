<?php
session_start();

include_once 'funcs/conexion.php';
include_once 'funcs/funcs.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$id = $_SESSION['id_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario'];

$where = ($tipo_usuario == 2) ? "WHERE id = ?" : "";

try {
    $sql = "SELECT * FROM usuarios $where";
    $stmt = $pdo->prepare($sql);
    if ($tipo_usuario == 2) {
        $stmt->execute([$id]);
    } else {
        $stmt->execute();
    }
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $nombre = $_SESSION['nombre'];
} catch (PDOException $e) {
    error_log('Error al obtener datos del usuario: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error en la consulta de la base de datos.']);
    exit;
}

// Configuración de la base de datos
$dsn = "pgsql:host=localhost;port=5432;dbname=woodvibe;";
$username = "postgres";
$password = "postgres";

try {
    // Crear conexión a la base de datos
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener los datos de la tabla "orden"
    $sql = "SELECT id, customer_id, total_price, created, modified, status, product_names_es, quantities FROM orden";
    $result = $pdo->query($sql);

    // Generar la tabla de resultados
    ob_start(); // Iniciar almacenamiento en buffer
    if ($result->rowCount() > 0) {
        echo "<table id='ordersTable' class='blue-table'>";
        echo "<thead><tr>";
        echo "<th>ID</th><th>ID Cliente</th><th>Precio Total</th><th>Creado</th><th>Modificado</th><th>Estado</th><th>Nombres de Productos</th><th>Cantidades</th>";
        echo "</tr></thead><tbody>";

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr id='row_" . $row['id'] . "'>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['customer_id']) . "</td>";
            echo "<td>$" . number_format($row['total_price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['created']) . "</td>";
            echo "<td>" . htmlspecialchars($row['modified']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>" . htmlspecialchars($row['product_names_es']) . "</td>";  // Campo en español
            echo "<td>" . htmlspecialchars($row['quantities']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No se encontraron pedidos.";
    }
    $table_html = ob_get_clean(); // Obtener el contenido del buffer y limpiar
} catch (PDOException $e) {
    error_log('Error en la conexión a la base de datos: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos.']);
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
    <title>WoodVibe - Panel de Pedidos</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
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
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="ES_DB.php">Panel de Control</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <!-- Barra de búsqueda -->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <div class="input-group-append"></div>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i><?php echo htmlspecialchars($nombre) ?>
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
                            <div class="sb-nav-link-icon"><i class="fa fa-dollar-sign"></i></div>
                            Ventas
                        </a>
                        <div class="sb-sidenav-menu-heading">Idioma</div>
                        <a class="nav-link" href="DBsales.php">
                            <div class="sb-nav-link-icon"></div>
                            Inglés
                        </a>
                        <a class="nav-link" href="ES_DBsales.php">
                            <div class="sb-nav-link-icon"></div>
                            Español
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Conectado como:</div>
                    <?php echo htmlspecialchars($nombre); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Tabla de Pedidos</h1>
                    <ol class="breadcrumb mb=4">
                        <li class="breadcrumb-item"><a href="ES_DB.php">Panel de Control</a></li>
                        <li class="breadcrumb-item active">Pedidos</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button id="generateReport" class="btn btn-danger mb-3 d-flex align-items-center">
                                <span class="me-3">Generar Informe</span>
                                <i class="fas fa-file-pdf fa-lg" style="margin-left: 0.75rem;"></i>
                            </button>
                            <i class="fas fa-table mr-1"></i>
                            Pedidos Registrados
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <?php echo $table_html; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; WoodVibe</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Bootstrap core JS-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <!-- Data Tables -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.min.js"></script>
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_",
                    "search": "Buscar:"
                }
            });

            $('#generateReport').on('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                let pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight();
                let pageWidth = doc.internal.pageSize.width || doc.internal.pageSize.getWidth();
                let y = 10;

                doc.setFontSize(20);
                doc.text("Informe de Pedidos", pageWidth / 2, y, {
                    align: 'center'
                });
                y += 10;

                const headers = [
                    ["ID", "ID Cliente", "Precio Total", "Creado", "Modificado", "Estado", "Nombres de Productos", "Cantidades"]
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

                doc.save('informe_pedidos.pdf');
            });
        });
    </script>
</body>

</html>
