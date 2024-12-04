<?php
require 'funcs/conexion.php';

function updateDropdownFiles($pdo) {
    try {
        $sql = "SELECT id, nombre FROM categorias";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dropdown_files = ['ES_view_user.php', 'EN_view_user.php'];

        foreach ($dropdown_files as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);

                $new_dropdown = '';
                foreach ($categories as $category) {
                    $new_dropdown .= '<li><a href="ES_' . strtolower($category['nombre']) . '.php">' . htmlspecialchars($category['nombre']) . '</a></li>';
                }

                $pattern = '/<!-- Categorías Inicio -->.*<!-- Categorías Fin -->/s';
                $replacement = '<!-- Categorías Inicio -->' . $new_dropdown . '<!-- Categorías Fin -->';
                $new_content = preg_replace($pattern, $replacement, $content);

                file_put_contents($file, $new_content);
            }
        }
    } catch (PDOException $e) {
        echo "Error al actualizar dropdowns: " . $e->getMessage();
    }
}

if (isset($_POST['add_category'])) {
    echo "Agregar categoría llamada correctamente<br>";
    $name = $_POST['categoryName'];
    $description = $_POST['categoryDescription'];
    $language = $_POST['language'];

    try {
        // Intentar la conexión y la inserción
        $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description]);

        echo "Categoría insertada en la base de datos<br>";

        // Crear un nuevo archivo de categoría
        $filename = strtolower($language . '_' . str_replace(' ', '_', $name) . '.php');
        $filepath = 'categorias/' . $filename;

        if (copy('ejemplo_sofas.php', $filepath)) {
            echo "Archivo de categoría creado: $filepath<br>";
        } else {
            echo "Error al copiar archivo de plantilla<br>";
        }

        // Reemplazar contenido específico
        $content = file_get_contents($filepath);
        $content = str_replace(['ES_sofas', 'Sofás'], [$language . '_' . strtolower($name), $name], $content);
        file_put_contents($filepath, $content);

        echo "Contenido del archivo de categoría actualizado<br>";

        // Actualizar dropdowns
        updateDropdownFiles($pdo);

        echo "Dropdowns actualizados<br>";

        // Redireccionar al dashboard con éxito
        header("Location: ES_DBproducts.php?success=true");
        exit;
    } catch (PDOException $e) {
        error_log("Error al agregar categoría: " . $e->getMessage());
        echo "Error al agregar categoría: " . $e->getMessage();
    }
}

if (isset($_POST['edit_category'])) {
    echo "Editar categoría llamada correctamente<br>";
    $name = $_POST['editCategoryName'];
    $description = $_POST['editCategoryDescription'];

    try {
        // Actualizar la categoría
        $sql = "UPDATE categorias SET descripcion = ? WHERE nombre = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$description, $name]);

        echo "Categoría actualizada en la base de datos<br>";

        // Actualizar dropdowns
        updateDropdownFiles($pdo);

        echo "Dropdowns actualizados<br>";

        // Redireccionar al dashboard con éxito
        header("Location: ES_DBproducts.php?success=true");
        exit;
    } catch (PDOException $e) {
        error_log("Error al editar categoría: " . $e->getMessage());
        echo "Error al editar categoría: " . $e->getMessage();
    }
}
?>
