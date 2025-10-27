<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = in_array($_POST['rol'], ['admin','empleado','cliente']) ? $_POST['rol'] : 'cliente';

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $password, $rol]);

    header("Location: login.php");
    exit;
}
?>

<main class="container mx-auto px-4 py-12">
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Crear cuenta</h2>
    <form method="post">
      <input name="nombre" required placeholder="Nombre completo" class="w-full p-2 border rounded mb-3">
      <input name="email" type="email" required placeholder="Correo" class="w-full p-2 border rounded mb-3">
      <input name="password" type="password" required placeholder="Contraseña" class="w-full p-2 border rounded mb-3">
      <select name="rol" class="w-full p-2 border rounded mb-3">
        <option value="cliente">Cliente</option>
        <option value="empleado">Empleado</option>
        <option value="admin">Administrador</option>
      </select>
      <button class="w-full bg-red-600 hover:bg-red-500 text-white py-2 rounded">Registrar</button>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
