<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<nav class="bg-black text-white shadow">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <div class="flex items-center space-x-3">
      <img src="../assets/favicon.png" alt="logo" class="h-9 w-9 rounded">
      <a href="index.php" class="text-lg font-semibold text-red-600 hover:text-red-500">Aerolínea</a>
    </div>

    <div class="flex items-center space-x-4">
      <a href="index.php" class="hover:text-sky-300">Inicio</a>
      <?php if(isset($_SESSION['usuario'])): ?>
        <?php if($_SESSION['rol'] === 'cliente'): ?>
          <a href="reservas.php" class="hover:text-sky-300">Reservas</a>
          <a href="sugerir_viaje.php" class="hover:text-sky-300">Sugerir destino</a>
        <?php elseif($_SESSION['rol'] === 'empleado'): ?>
          <a href="panel_empleado.php" class="hover:text-sky-300">Panel Empleado</a>
        <?php elseif($_SESSION['rol'] === 'admin'): ?>
          <a href="panel_admin.php" class="hover:text-sky-300">Panel Admin</a>
        <?php endif; ?>
        <a href="cerrar_sesion.php" class="hover:text-sky-300">Salir</a>
      <?php else: ?>
        <a href="login.php" class="hover:text-sky-300">Iniciar sesión</a>
        <a href="registro.php" class="hover:text-sky-300">Registrarse</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
