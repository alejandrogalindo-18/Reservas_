<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['estado'])) {
  $stmt = $conn->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
  $stmt->execute([$_POST['estado'], intval($_POST['id'])]);
  // auditoría (opcional)
  $stmt = $conn->prepare("INSERT INTO auditoria_reservas (reserva_id, usuario_id, accion) VALUES (?, ?, ?)");
  $stmt->execute([intval($_POST['id']), $_SESSION['id'], "Empleado cambió a ".$_POST['estado']]);
  header("Location: panel_empleado.php");
  exit;
}

$stmt = $conn->prepare("SELECT r.*, u.nombre AS cliente, v.destino FROM reservas r JOIN usuarios u ON r.usuario_id = u.id JOIN viajes v ON r.viaje_id = v.id ORDER BY r.fecha_reserva DESC");
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-bold mb-4">Panel Empleado - Reservas</h2>

  <table class="w-full bg-white rounded shadow">
    <thead class="bg-black text-white"><tr><th class="p-2">Cliente</th><th>Destino</th><th>Estado</th><th>Acción</th></tr></thead>
    <tbody>
      <?php foreach($reservas as $r): ?>
        <tr class="border-b">
          <td class="p-2"><?php echo htmlspecialchars($r['cliente']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($r['destino']); ?></td>
          <td class="p-2"><?php echo htmlspecialchars($r['estado']); ?></td>
          <td class="p-2">
            <form method="post" class="flex space-x-2">
              <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
              <select name="estado" class="p-1 border rounded">
                <option value="pendiente">Pendiente</option>
                <option value="confirmada">Confirmada</option>
                <option value="cancelada">Cancelada</option>
              </select>
              <button class="bg-blue-700 text-white px-2 py-1 rounded">Actualizar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

<?php include '../includes/footer.php'; ?>
