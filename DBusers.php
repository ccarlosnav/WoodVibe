<?php
session_start();
require_once 'funcs/conexion.php'; // Archivo de conexión PDO
require_once 'funcs/funcs.php'; // Archivo de funciones utilitarias

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$nombre = $_SESSION['nombre']; // Obtener el nombre de usuario
$tipo_usuario = $_SESSION['tipo_usuario'];
$id = $_SESSION['id_usuario'];

$usuarios = [];

try {
    $conn = new PDO("pgsql:host=localhost;dbname=woodvibe", "postgres", "postgres");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Solo seleccionar usuarios (id_tipo = 2)
    $sql = "SELECT id, usuario, correo, nombre, id_tipo FROM usuarios WHERE id_tipo = 2";
    $stmt = $conn->prepare($sql);

    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;
$edit_usuario = [];
if (!empty($edit_id)) {
    try {
        $sql = "SELECT id, usuario, correo, nombre, id_tipo FROM usuarios WHERE id = :id AND id_tipo = 2";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $edit_id, PDO::PARAM_INT);
        $stmt->execute();
        $edit_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener los datos del usuario: " . $e->getMessage();
        exit;
    }
}

// Procesar el formulario de edición si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $usuario = trim($_POST['usuario']);
        $correo = trim($_POST['correo']);
        $nombre = trim($_POST['nombre']);
        $id_tipo = $_POST['id_tipo'];

        // Validar la entrada
        if ($id <= 0 || empty($usuario) || empty($correo) || empty($nombre) || ($id_tipo != 2)) {
            echo json_encode(['success' => false, 'message' => 'Datos de entrada no válidos.']);
            exit;
        }

        try {
            $sql = "UPDATE usuarios SET usuario = :usuario, correo = :correo, nombre = :nombre, id_tipo = :id_tipo WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['success' => false]);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];

        try {
            $sql = "DELETE FROM usuarios WHERE id = :id AND id_tipo = 2";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['success' => false]);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>WoodVibe - User Dashboard</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #343a40 !important;
        }

        .card {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .card h1 {
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }

        .card h1.title {
            color: #000;
        }

        table.dataTable thead th,
        table.dataTable tbody td {
            border: 1px solid #007bff;
            padding: 10px;
            color: black;
        }

        table.dataTable thead th {
            background-color: #007bff;
            color: #fff;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table.dataTable tbody tr:hover {
            background-color: #e0e0e0;
        }

        .dataTables_info {
            display: none;
        }

        .sb-sidenav-footer .small {
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        th,
        td {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                text-align: center;
            }

            .dataTables_wrapper .dataTables_paginate {
                float: none;
                margin-top: 10px;
            }

            .dataTables_wrapper .dataTables_length {
                float: none;
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_filter {
                float: none;
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_info {
                float: none;
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                display: inline-block;
                padding: 6px 12px;
                margin-left: 2px;
                line-height: 1.42857143;
                color: #333;
                text-decoration: none;
                background-color: #fff;
                border: 1px solid #ddd;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background-color: #007bff;
                color: white !important;
            }
        }
    </style>
</head>

<body class="sb-nav-fixed">
     <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="DB.php">Dashboard</a>
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
            <button id="userDropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onKeyPress="handleKeyPress(event)" onKeyDown="handleKeyDown(event)" onKeyUp="handleKeyUp(event)">
    User Dropdown
</button>
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
                        <div class="sb-sidenav-menu-heading">Main Menu</div>
                        <a class="nav-link" href="DB.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Configuration</div>
                        <a class="nav-link" href="DBproducts.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Products
                        </a>
                        <a class="nav-link" href="DBusers.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="DBsales.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                            Sales
                        </a>
                        <div class="sb-sidenav-menu-heading">Language</div>
                        <a class="nav-link" href="DBusers.php">
                            <div class="sb-nav-link-icon"></div>
                            English
                        </a>
                        <a class="nav-link" href="ES_DBusers.php">
                            <div class="sb-nav-link-icon"></div>
                            Spanish
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo htmlspecialchars($nombre); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="title">Users Table</h1>
                    <div class="card">
                        <div class="card-header">
                            <button id="generateReport" class="btn btn-danger mb-3 d-flex align-items-center">
                                <span class="me-3">Generate Report</span>
                                <i class="fas fa-file-pdf fa-lg" style="margin-left: 0.75rem;"></i>
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>User Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $usuario) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                        <td>
                                            <?php echo 'User'; ?>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="?edit_id=<?php echo $usuario['id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="far fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $usuario['id']; ?>">
                                                <i class="far fa-trash-alt"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; WoodVibe 2024</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal -->
    <dialog id="editModal" aria-labelledby="editModalLabel" aria-hidden="true">
</dialog>
<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?php echo isset($edit_usuario['id']) ? $edit_usuario['id'] : ''; ?>">
                        <div class="form-group">
                            <label for="editUsuario">Username</label>
                            <input type="text" class="form-control" id="editUsuario" name="usuario" value="<?php echo isset($edit_usuario['usuario']) ? $edit_usuario['usuario'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editCorreo">Email address</label>
                            <input type="email" class="form-control" id="editCorreo" name="correo" value="<?php echo isset($edit_usuario['correo']) ? $edit_usuario['correo'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editNombre">Name</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" value="<?php echo isset($edit_usuario['nombre']) ? $edit_usuario['nombre'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editTipo">User Type (1 for Admin, 2 for User)</label>
                            <select class="form-control" id="editTipo" name="id_tipo" required>
                                <option value="2" selected>User</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

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
            var table = $('#usersTable').DataTable({
                "autoWidth": false,
                "columns": [
                    { "width": "10%" },
                    { "width": "20%" },
                    { "width": "20%" },
                    { "width": "20%" },
                    { "width": "10%" },
                    { "width": "20%" }
                ]
            });

            $('#generateReport').on('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                let pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight();
                let pageWidth = doc.internal.pageSize.width || doc.internal.pageSize.getWidth();
                let y = 10;

                doc.setFontSize(20);
                doc.text("Users Report", pageWidth / 2, y, { align: 'center' });
                y += 10;

                const headers = [
                    ["ID", "Username", "Email", "Name", "User Type"]
                ];
                const rows = [];

                document.querySelectorAll('#usersTable tbody tr').forEach(row => {
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

                doc.save('users_report.pdf');
            });

            function attachDeleteHandler() {
                $('.delete-btn').on('click', function() {
                    var id = $(this).data('id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'DBusers.php',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    action: 'delete',
                                    id: id
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: 'The user has been deleted.',
                                            icon: 'success'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Failed to delete user.',
                                            icon: 'error'
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('AJAX Error:', status, error);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Failed to delete user.',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            }

            attachDeleteHandler();

            $('#usersTable').on('draw.dt', function() {
                attachDeleteHandler();
            });

            $('#editForm').submit(function(event) {
                event.preventDefault();

                const id = $('#editUsuario').val().trim();
                const usuario = $('#editUsuario').val().trim();
                const correo = $('#editCorreo').val().trim();
                const nombre = $('#editNombre').val().trim();
                const id_tipo = $('#editTipo').val();

                if (usuario === '' || correo === '' || nombre === '' || id <= 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please fill in all fields correctly.',
                        icon: 'error'
                    });
                    return;
                }

                $.ajax({
                    url: 'DBusers.php',
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editModal').modal('hide');
                            Swal.fire({
                                title: 'Updated!',
                                text: 'The user information has been updated.',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to update user information.',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update user information.',
                            icon: 'error'
                        });
                    }
                });
            });

            $('#editModal').on('hidden.bs.modal', function() {
                history.replaceState(null, null, window.location.pathname);
            });

            <?php if (!empty($edit_usuario)) : ?>
            $('#editModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>

</html>
