<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit;
}
$usuario_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT r.*, v.destino, v.fecha_salida FROM reservas r JOIN viajes v ON r.viaje_id = v.id WHERE r.usuario_id = ? ORDER BY r.fecha_reserva DESC");
$stmt->execute([$usuario_id]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-bold mb-4">Mis Reservas</h2>
  <?php if(empty($reservas)): ?>
    <p>No tienes reservas aún.</p>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <?php foreach($reservas as $r): ?>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="font-semibold"><?php echo htmlspecialchars($r['destino']); ?></h3>
          <p class="text-sm text-gray-600">Salida: <?php echo htmlspecialchars($r['fecha_salida']); ?></p>
          <p>Estado: <?php echo htmlspecialchars($r['estado']); ?></p>
          <p class="font-bold">$<?php echo number_format($r['precio'],2); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
