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
    error_log('Error fetching user data: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database query error.']);
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

    // Consulta SQL para obtener los productos en español junto con el nombre de la categoría
    $sql = "SELECT p.id, p.nombre_es AS name, p.descripcion_es AS description, p.price, p.stock, c.nombre AS categoria, p.image
            FROM mis_productos p
            JOIN categorias c ON p.categoria_id = c.id";
    $result = $pdo->query($sql);

    // Generar la tabla de resultados
    ob_start(); // Iniciar almacenamiento en buffer
    if ($result->rowCount() > 0) {
        echo "<table id='productsTable' class='blue-table'>";
        echo "<thead><tr>";
        echo "<th>ID</th><th>Nombre del Producto</th><th>Descripción</th><th>Precio</th><th>Stock</th><th>Categoría</th><th>Imagen</th><th>Acciones</th>";
        echo "</tr></thead><tbody>";

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr id='row_" . $row['id'] . "'>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
            echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
            echo "<td><img src='imagenes/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' width='50'></td>";
            echo "<td class='action-buttons'>
                    <button type='button' class='btn btn-warning' data-toggle='modal' data-target='#editProductModal' onclick='loadEditForm(" . $row['id'] . ")'>
                        <i class='fas fa-edit'></i> Editar
                    </button>
                    <button type='button' class='btn btn-danger' onclick='confirmDelete(" . $row['id'] . ")'>
                        <i class='fas fa-trash-alt'></i> Eliminar
                    </button>
                  </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No se encontraron productos.";
    }
    $table_html = ob_get_clean(); // Obtener el contenido del buffer y limpiar
} catch (PDOException $e) {
    error_log('Error en la conexión a la base de datos: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database connection error.']);
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
    <title>WoodVibe - Dashboard Productos</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"
        crossorigin="anonymous"></script>
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

        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="DB.php">Dashboard</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <div class="input-group-append"></div>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
            <button class="nav-link dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onKeyPress="handleKeyPress(event)">User</button>
            aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i><?php echo htmlspecialchars($nombre) ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="logout.php">Logout</a>
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
                        <a class="nav-link" href="DBproducts.php">
                            <div class="sb-nav-link-icon"></div>
                            Inglés
                        </a>
                        <a class="nav-link" href="ES_DBproducts.php">
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
                    <h1 class="mt-4">Tabla de Productos</h1>
                    <ol class="breadcrumb mb=4">
                        <li class="breadcrumb-item"><a href="ES_DB.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Productos</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            Productos Registrados
                            <button type="button" class="btn btn-success float-right" data-toggle="modal"
                                data-target="#addProductModal">
                                <i class="fas fa-plus"></i> Agregar Producto
                            </button>
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

    <!-- Modal para agregar un producto -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Nombre del Producto</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea name="description" id="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="nombre_es">Nombre en Español</label>
                            <input type="text" name="nombre_es" id="nombre_es" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion_es">Descripción en Español</label>
                            <textarea name="descripcion_es" id="descripcion_es" class="form-control"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Precio</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Categoría</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="1">Sofás</option>
                                <option value="2">Muebles para TV</option>
                                <option value="3">Camas</option>
                                <option value="4">Closets</option>
                                <option value="5">Mesas de noche</option>
                                <option value="6">Escritorios</option>
                                <option value="7">Sillas de oficina</option>
                                <option value="8">Gabinetes</option>
                                <option value="9">Sillas</option>
                                <option value="10">Mesas</option>
                                <option value="11">Gabinetes abiertos</option>
                                <option value="12">Gabinetes de pared</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagen</label>
                            <input type="file" id="image" name="image" accept="image/*" class="form-control">
                        </div>
                        <button type="submit" name="add_product" class="btn btn-success">Agregar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar un producto -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="edit_id">ID del Producto</label>
                            <input type="text" name="id" id="edit_id" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_name">Nombre del Producto</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Descripción</label>
                            <textarea name="description" id="edit_description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_nombre_es">Nombre en Español</label>
                            <input type="text" name="nombre_es" id="edit_nombre_es" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_descripcion_es">Descripción en Español</label>
                            <textarea name="descripcion_es" id="edit_descripcion_es" class="form-control"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_price">Precio</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_stock">Stock</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_category">Categoría</label>
                            <select name="category" id="edit_category" class="form-control" required>
                                <option value="1">Sofás</option>
                                <option value="2">Muebles para TV</option>
                                <option value="3">Camas</option>
                                <option value="4">Closets</option>
                                <option value="5">Mesas de noche</option>
                                <option value="6">Escritorios</option>
                                <option value="7">Sillas de oficina</option>
                                <option value="8">Gabinetes</option>
                                <option value="9">Sillas</option>
                                <option value="10">Mesas</option>
                                <option value="11">Gabinetes abiertos</option>
                                <option value="12">Gabinetes de pared</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_image">Imagen</label>
                            <input type="file" id="edit_image" name="image" accept="image/*" class="form-control">
                        </div>
                        <button type="submit" name="edit_product" class="btn btn-primary">Editar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.min.js"></script>
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>

    <script>

        $(document).ready(function () {
            $('#addCategoryForm').on('submit', function (event) {
                event.preventDefault();
                $.ajax({
                    url: 'manage_categories.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error: ' + error);
                    }
                });
            });

            $('#editCategoryForm').on('submit', function (event) {
                event.preventDefault();
                $.ajax({
                    url: 'manage_categories.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error: ' + error);
                    }
                });
            });
        });


        $(document).ready(function () {
            window.confirmDelete = function (productId) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_product.php',
                            type: 'POST',
                            data: {
                                id: productId
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: 'Producto eliminado exitosamente',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#row_' + productId).remove();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.error || 'Error eliminando el producto',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error conectando con el servidor: ' + error,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            }

            $('#addProductForm').on('submit', function (event) {
                event.preventDefault();
                confirmAdd(event);
            });

            function confirmAdd(event) {
                const form = $('#addProductForm')[0];
                const formData = new FormData(form);

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Vas a agregar un nuevo producto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, agregarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'add_product.php',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#addProductModal').modal('hide');
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Error agregando el producto',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error(xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error conectando con el servidor: ' + error,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            }

            $('#editProductForm').on('submit', function (event) {
                event.preventDefault();
                confirmEdit(event);
            });

            function confirmEdit(event) {
                const form = $('#editProductForm')[0];
                const formData = new FormData(form);

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Vas a editar este producto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, editarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'edit_product.php',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#editProductModal').modal('hide');
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.error || 'Error editando el producto',
                                        confirmButtonText: 'OK'
                                    });
                                    console.error('Error details:', response.details);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error(xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error conectando con el servidor: ' + error,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            }

            window.loadEditForm = function (productId) {
                $.ajax({
                    url: 'get_product.php',
                    type: 'GET',
                    data: {
                        id: productId
                    },
                    dataType: 'json',
                    success: function (product) {
                        if (product) {
                            $('#edit_id').val(product.id);
                            $('#edit_name').val(product.name);
                            $('#edit_description').val(product.description);
                            $('#edit_nombre_es').val(product.nombre_es);
                            $('#edit_descripcion_es').val(product.descripcion_es);
                            $('#edit_price').val(product.price);
                            $('#edit_stock').val(product.stock);
                            $('#edit_category').val(product.categoria_id);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Producto no encontrado',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error obteniendo datos del producto: ' + error,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            };

            $('#productsTable').DataTable({
                "language": {
                    "lengthMenu": "Show _MENU_",
                    "search": "Buscar:"
                }
            });
        });
    </script>
</body>

</html>
