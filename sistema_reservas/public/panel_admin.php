<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header("Location: login.php");
  exit;
}

// acciones: aprobar, eliminar, crear
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['accion']) && $_POST['accion'] === 'aprobar') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("UPDATE viajes SET aprobado = 1 WHERE id = ?");
    $stmt->execute([$id]);
  }
  if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM viajes WHERE id = ?");
    $stmt->execute([$id]);
  }
  if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $destino = trim($_POST['destino']);
    $descripcion = trim($_POST['descripcion']);
    $precio = is_numeric($_POST['precio']) ? floatval($_POST['precio']) : 0;
    $disponibilidad = is_numeric($_POST['disponibilidad']) ? intval($_POST['disponibilidad']) : 0;
    $fecha_salida = !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null;
    $stmt = $conn->prepare("INSERT INTO viajes (destino, descripcion, precio, disponibilidad, fecha_salida, aprobado, creado_por) VALUES (?, ?, ?, ?, ?, 1, ?)");
    $stmt->execute([$destino, $descripcion, $precio, $disponibilidad, $fecha_salida, $_SESSION['id']]);
  }
  header("Location: panel_admin.php");
  exit;
}

// leer viajes y reservas
$stmt = $conn->prepare("SELECT v.*, u.nombre AS creador FROM viajes v LEFT JOIN usuarios u ON v.creado_por = u.id ORDER BY v.fecha_creacion DESC");
$stmt->execute();
$viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $conn->prepare("SELECT r.*, u.nombre AS cliente, v.destino FROM reservas r JOIN usuarios u ON r.usuario_id = u.id JOIN viajes v ON r.viaje_id = v.id ORDER BY r.fecha_reserva DESC");
$stmt2->execute();
$reservas = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-bold mb-4">Panel Administrador</h2>

  <div class="bg-white p-4 rounded shadow mb-6">
    <h3 class="font-semibold mb-3">Crear destino (aprobado)</h3>
    <form method="post" class="grid grid-cols-1 md:grid-cols-3 gap-2">
      <input type="hidden" name="accion" value="crear">
      <input name="destino" placeholder="Destino" class="p-2 border rounded">
      <input name="precio" placeholder="Precio" class="p-2 border rounded">
      <input name="disponibilidad" placeholder="Cupos" class="p-2 border rounded">
      <input name="fecha_salida" type="date" class="p-2 border rounded md:col-span-3">
      <textarea name="descripcion" placeholder="Descripción" class="p-2 border rounded md:col-span-3"></textarea>
      <button class="bg-blue-700 text-white py-2 rounded md:col-span-3">Crear destino</button>
    </form>
  </div>

  <div class="mb-6">
    <h3 class="font-semibold mb-2">Sugerencias / Viajes</h3>
    <table class="w-full bg-white rounded shadow">
      <thead class="bg-black text-white">
        <tr><th class="p-2">Destino</th><th>Creador</th><th>Aprobado</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php foreach($viajes as $v): ?>
          <tr class="border-b">
            <td class="p-2"><?php echo htmlspecialchars($v['destino']); ?></td>
            <td class="p-2"><?php echo htmlspecialchars($v['creador'] ?? 'Sistema'); ?></td>
            <td class="p-2"><?php echo $v['aprobado'] ? 'Sí' : 'No'; ?></td>
            <td class="p-2">
              <?php if(!$v['aprobado']): ?>
                <form method="post" class="inline-block">
                  <input type="hidden" name="accion" value="aprobar">
                  <input type="hidden" name="id" value="<?php echo $v['id']; ?>">
                  <button class="bg-green-600 text-white px-2 py-1 rounded">Aprobar</button>
                </form>
              <?php endif; ?>
              <form method="post" class="inline-block ml-2">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="id" value="<?php echo $v['id']; ?>">
                <button class="bg-red-600 text-white px-2 py-1 rounded" onclick="return confirm('Eliminar destino?')">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div>
    <h3 class="font-semibold mb-2">Reservas</h3>
    <table class="w-full bg-white rounded shadow">
      <thead class="bg-black text-white"><tr><th class="p-2">Cliente</th><th>Destino</th><th>Fecha</th><th>Estado</th><th>Precio</th></tr></thead>
      <tbody>
        <?php foreach($reservas as $r): ?>
          <tr class="border-b">
            <td class="p-2"><?php echo htmlspecialchars($r['cliente']); ?></td>
            <td class="p-2"><?php echo htmlspecialchars($r['destino']); ?></td>
            <td class="p-2"><?php echo htmlspecialchars($r['fecha_reserva']); ?></td>
            <td class="p-2"><?php echo htmlspecialchars($r['estado']); ?></td>
            <td class="p-2">$<?php echo number_format($r['precio'],2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
