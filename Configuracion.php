<?php
// Detalles de la base de datos
$dbHost = 'localhost';
$dbPort = '5432'; // Puerto predeterminado de PostgreSQL
$dbUsername = 'postgres'; // Cambiar al nombre de usuario de PostgreSQL
$dbPassword = 'postgres';
$dbName = 'woodvibe';

try {
    // Crear una instancia de PDO
    $dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName;sslmode=disable";
    $db = new PDO($dsn, $dbUsername, $dbPassword);

    // Configurar PDO para que lance excepciones en caso de error
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("No hay Conexion con la base de datos: " . $e->getMessage());
}

// Devolver la conexión PDO para que pueda ser utilizada en otros archivos
return $db;