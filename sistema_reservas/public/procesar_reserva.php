<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['id']) || !isset($_POST['viaje_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['id'];
$viaje_id = intval($_POST['viaje_id']);

// verificar viaje aprobado y disponibilidad
$stmt = $conn->prepare("SELECT * FROM viajes WHERE id = ? AND aprobado = 1 LIMIT 1");
$stmt->execute([$viaje_id]);
$viaje = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$viaje) {
    header("Location: index.php");
    exit;
}

if ($viaje['disponibilidad'] <= 0) {
    header("Location: reservas.php");
    exit;
}

// insertar reserva
$precio = $viaje['precio'];
$stmt = $conn->prepare("INSERT INTO reservas (usuario_id, viaje_id, fecha_reserva, estado, precio) VALUES (?, ?, NOW(), 'pendiente', ?)");
$stmt->execute([$usuario_id, $viaje_id, $precio]);

// decrementar disponibilidad
$stmt = $conn->prepare("UPDATE viajes SET disponibilidad = disponibilidad - 1 WHERE id = ?");
$stmt->execute([$viaje_id]);

header("Location: reservas.php");
exit;
