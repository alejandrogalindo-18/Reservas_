<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->query("SELECT * FROM viajes WHERE disponibilidad > 0");
$viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="flex-grow container mx-auto py-10">
  <h2 class="text-3xl font-bold text-blue-700 mb-6">Reservar un vuelo</h2>

  <form action="procesar_reserva.php" method="POST" class="space-y-6">
    <select name="viaje_id" required class="w-full p-3 border rounded">
      <option value="">Seleccione un destino</option>
      <?php foreach ($viajes as $v): ?>
        <option value="<?php echo $v['id']; ?>">
          <?php echo $v['destino'] . " - $" . $v['precio']; ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
      Confirmar reserva
    </button>
  </form>
</main>

<?php include '../includes/footer.php'; ?>
