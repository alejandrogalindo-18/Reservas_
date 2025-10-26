<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['id'];
    $viaje_id = $_POST['viaje_id'];
    $fecha_reserva = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("SELECT * FROM viajes WHERE id = ?");
    $stmt->execute([$viaje_id]);
    $viaje = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($viaje && $viaje['disponibilidad'] > 0) {
        $conn->prepare("INSERT INTO reservas (usuario_id, destino, fecha_viaje, fecha_reserva, estado, precio) VALUES (?, ?, ?, ?, 'Confirmada', ?)")
             ->execute([$usuario_id, $viaje['destino'], $viaje['fecha_salida'], $fecha_reserva, $viaje['precio']]);

        $conn->prepare("UPDATE viajes SET disponibilidad = disponibilidad - 1 WHERE id = ?")->execute([$viaje_id]);
    }

    header("Location: mis_reservas.php");
    exit;
}
?>
