<?php
// config/config.php
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "sistema_reservas";

try {
    $conn = new PDO("mysql:host=$host;dbname=$base_datos;charset=utf8mb4", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
