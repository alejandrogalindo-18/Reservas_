<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($u && password_verify($pass, $u['password'])) {
        // iniciar sesión
        $_SESSION['usuario'] = $u['nombre'];
        $_SESSION['id'] = $u['id'];
        $_SESSION['rol'] = $u['rol'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>

<main class="container mx-auto px-4 py-12">
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Iniciar sesión</h2>
    <?php if(!empty($error)): ?>
      <div class="mb-3 text-red-600"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post">
      <input name="email" type="email" required placeholder="Correo" class="w-full p-2 border rounded mb-3">
      <input name="password" type="password" required placeholder="Contraseña" class="w-full p-2 border rounded mb-3">
      <button class="w-full bg-blue-700 hover:bg-blue-600 text-white py-2 rounded">Entrar</button>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
