<?php
require_once "config.php";
require_once "controllers.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    createReservation($pdo);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    listReservations($pdo);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    deleteReservation($pdo);
}

function createReservation($pdo) {
    $destino = cleanInput($_POST['destino'] ?? '');
    $fecha_salida = $_POST['fecha_salida'] ?? '';
    $fecha_regreso = $_POST['fecha_regreso'] ?? '';
    $personas = intval($_POST['personas'] ?? 1);
    $usuario_id = $_SESSION['user_id'] ?? null;

    if (!$usuario_id) {
        jsonResponse(false, "No has iniciado sesión");
    }

    $stmt = $pdo->prepare("INSERT INTO reservaciones (usuario_id, destino, fecha_salida, fecha_regreso, cantidad_personas)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $destino, $fecha_salida, $fecha_regreso, $personas]);

    jsonResponse(true, "Reserva creada correctamente");
}

function listReservations($pdo) {
    $rol = $_SESSION['rol'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if ($rol === 'admin') {
        $stmt = $pdo->query("SELECT * FROM reservaciones");
    } elseif ($rol === 'agente') {
        $stmt = $pdo->prepare("SELECT * FROM reservaciones");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM reservaciones WHERE usuario_id = ?");
        $stmt->execute([$user_id]);
    }

    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    jsonResponse(true, "Reservas obtenidas", $reservas);
}

function deleteReservation($pdo) {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'] ?? null;
    $rol = $_SESSION['rol'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$id) jsonResponse(false, "ID no válido");

    if ($rol === 'cliente') {
        $stmt = $pdo->prepare("DELETE FROM reservaciones WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $user_id]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM reservaciones WHERE id = ?");
        $stmt->execute([$id]);
    }

    jsonResponse(true, "Reserva eliminada correctamente");
}
?>
