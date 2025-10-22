<?php
require_once "config.php";
require_once "controllers.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'register':
            registerUser($pdo);
            break;

        case 'login':
            loginUser($pdo);
            break;

        case 'logout':
            logoutUser();
            break;

        default:
            jsonResponse(false, "Acción no válida");
    }
}

function registerUser($pdo) {
    $nombre = cleanInput($_POST['nombre'] ?? '');
    $correo = cleanInput($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $rol = cleanInput($_POST['rol'] ?? 'cliente');

    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        jsonResponse(false, "Todos los campos son obligatorios");
    }

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    if ($stmt->fetch()) {
        jsonResponse(false, "El correo ya está registrado");
    }

    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $rolSeguro = "cliente";

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $correo, $hash, $rolSeguro]);

    jsonResponse(true, "Registro exitoso. Ahora puedes iniciar sesión.");
}

function loginUser($pdo) {
    $correo = cleanInput($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($contrasena, $user['contrasena'])) {
        jsonResponse(false, "Credenciales incorrectas");
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['rol'] = $user['rol'];
    $_SESSION['nombre'] = $user['nombre'];

    jsonResponse(true, "Inicio de sesión exitoso", ["rol" => $user['rol']]);
}

function logoutUser() {
    session_destroy();
    jsonResponse(true, "Sesión cerrada correctamente");
}
?>
