<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = limpiarDato($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];

        if ($usuario['rol'] === 'admin') header("Location: panel_admin.php");
        elseif ($usuario['rol'] === 'empleado') header("Location: panel_empleado.php");
        else header("Location: gestionar_reservas.php");
        exit;
    }
}
?>

<main class="flex-grow flex items-center justify-center py-10">
    <form method="POST" class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-blue-700">Iniciar sesión</h2>

        <input type="email" name="email" placeholder="Correo electrónico" required class="w-full p-2 border rounded mb-4">
        <input type="password" name="password" placeholder="Contraseña" required class="w-full p-2 border rounded mb-4">

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Entrar</button>
        <p class="text-center text-sm mt-4">¿No tienes cuenta? <a href="registro.php" class="text-blue-600 hover:underline">Regístrate</a></p>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
