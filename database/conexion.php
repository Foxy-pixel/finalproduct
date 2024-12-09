<?php
$host = 'localhost';
$dbname = 'tienda';
$username = 'root';  // O el nombre de usuario de tu base de datos
$password = '';      // O la contraseña de tu base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

