
<?php

$host = 'localhost';
$port = '5432';
$dbname = 'woodvibe';
$username = 'postgres';
$password = 'postgres';

try {
    // Crear una instancia de PDO
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=disable";
    $pdo = new PDO($dsn, $username, $password);

    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

// Devolver la conexión PDO para que pueda ser utilizada en otros archivos
return $pdo;

?>
