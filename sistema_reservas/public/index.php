<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';
?>
<main class="container mx-auto px-4 py-12">
  <div class="text-center py-8">
    <h1 class="text-4xl font-bold text-black">Bienvenido al sistema de reservas</h1>
    <p class="mt-3 text-gray-700">Registra tu cuenta, sugiere destinos o reserva los aprobados.</p>
  </div>

  <section class="mt-8">
    <h2 class="text-2xl font-semibold mb-4">Destinos disponibles (aprobados)</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php
      $stmt = $conn->prepare("SELECT * FROM viajes WHERE aprobado = 1 ORDER BY fecha_creacion DESC");
      $stmt->execute();
      $viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($viajes)): ?>
        <p class="text-gray-500 col-span-3">No hay destinos aprobados aún.</p>
      <?php else:
        foreach($viajes as $v): ?>
          <div class="bg-white rounded shadow p-4">
            <div class="h-40 bg-gray-100 flex items-center justify-center mb-3">
              <?php if(!empty($v['imagen'])): ?>
                <img src="../assets/<?php echo htmlspecialchars($v['imagen']); ?>" alt="" class="h-full object-cover w-full">
              <?php else: ?>
                <span class="text-gray-400">Sin imagen</span>
              <?php endif; ?>
            </div>
            <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($v['destino']); ?></h3>
            <p class="text-sm text-gray-600 mb-2"><?php echo nl2br(htmlspecialchars($v['descripcion'])); ?></p>
            <p class="font-bold text-blue-700 mb-3">$<?php echo number_format($v['precio'],2); ?></p>
            <?php if(isset($_SESSION['usuario'])): ?>
              <form action="procesar_reserva.php" method="post">
                <input type="hidden" name="viaje_id" value="<?php echo $v['id']; ?>">
                <button class="w-full bg-red-600 hover:bg-red-500 text-white py-2 rounded">Reservar</button>
              </form>
            <?php else: ?>
              <a href="login.php" class="w-full inline-block text-center bg-blue-700 hover:bg-blue-600 text-white py-2 rounded">Iniciar sesión para reservar</a>
            <?php endif; ?>
          </div>
      <?php endforeach; endif; ?>
    </div>
  </section>
</main>
<?php include '../includes/footer.php'; ?>
