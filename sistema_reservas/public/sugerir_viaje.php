<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit;
}

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $destino = trim($_POST['destino']);
  $descripcion = trim($_POST['descripcion']);
  $precio = is_numeric($_POST['precio']) ? floatval($_POST['precio']) : 0;
  $disponibilidad = is_numeric($_POST['disponibilidad']) ? intval($_POST['disponibilidad']) : 0;
  $fecha_salida = !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null;

  $stmt = $conn->prepare("INSERT INTO viajes (destino, descripcion, precio, disponibilidad, fecha_salida, aprobado, creado_por) VALUES (?, ?, ?, ?, ?, 0, ?)");
  $stmt->execute([$destino, $descripcion, $precio, $disponibilidad, $fecha_salida, $_SESSION['id']]);

  $mensaje = "Destino sugerido correctamente. Espera aprobación del administrador.";
}
?>

<main class="container mx-auto px-4 py-12">
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Sugerir un destino</h2>
    <?php if($mensaje): ?>
      <div class="mb-3 text-green-600"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form method="post">
      <input name="destino" required placeholder="Destino (ej. Japón)" class="w-full p-2 border rounded mb-3">
      <textarea name="descripcion" placeholder="Descripción" class="w-full p-2 border rounded mb-3"></textarea>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <input name="precio" type="number" step="0.01" placeholder="Precio" class="p-2 border rounded">
        <input name="disponibilidad" type="number" placeholder="Cupos" class="p-2 border rounded">
      </div>
      <input name="fecha_salida" type="date" class="w-full p-2 border rounded mb-3">
      <button class="w-full bg-blue-700 hover:bg-blue-600 text-white py-2 rounded">Sugerir destino</button>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
