
<?php
require 'funcs/conexion.php';
require 'funcs/funcs.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $nombre = $_POST['nombre'];
    $id_tipo = $_POST['id_tipo'];

    try {
        $conn = new PDO("pgsql:host=localhost;dbname=woodvibe", "postgres", "postgres");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE usuarios SET usuario = :usuario, correo = :correo, nombre = :nombre, id_tipo = :id_tipo WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
