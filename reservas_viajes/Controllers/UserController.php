<?php
require_once "config.php";
require_once "controllers.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    listUsers($pdo);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    deleteUser($pdo);
}

function listUsers($pdo) {
    if ($_SESSION['rol'] !== 'admin') {
        jsonResponse(false, "No tienes permiso para ver esta información");
    }

    $stmt = $pdo->query("SELECT id, nombre, correo, rol, fecha_registro FROM usuarios");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    jsonResponse(true, "Lista de usuarios obtenida", $users);
}

function deleteUser($pdo) {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'] ?? null;

    if ($_SESSION['rol'] !== 'admin') {
        jsonResponse(false, "No tienes permiso para eliminar usuarios");
    }

    if (!$id) {
        jsonResponse(false, "ID de usuario no válido");
    }

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    jsonResponse(true, "Usuario eliminado correctamente");
}
?>
