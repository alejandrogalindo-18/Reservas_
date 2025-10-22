<?php

$host = "localhost";
$dbname = "reservas_viajes";
$user = "root"; 
$pass = "";    

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode([
        "success" => false,
        "message" => "Error de conexión: " . $e->getMessage()
    ]));
}
?>
