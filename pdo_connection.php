<?php
function pdo_connect() {
    $host = 'localhost';
    $port = '5432';
    $dbname = 'woodvibe';
    $user = 'postgres';
    $password = 'postgres';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    try {
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("Conexión fallida: " . $e->getMessage());
    }
}

function encryptData($data) {
    $key = 'your_secret_key';
    $iv = 'your_iv';
    return openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
}
?>