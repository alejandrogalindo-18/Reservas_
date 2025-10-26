<nav class="bg-blue-600 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-3 px-5">
        <a href="index.php" class="text-lg font-semibold hover:text-gray-200">Reservas</a>
        <div class="space-x-4">
            <?php if(isset($_SESSION['usuario'])): ?>
                <?php if($_SESSION['rol'] === 'admin'): ?>
                    <a href="panel_admin.php" class="hover:text-gray-200">Panel Admin</a>
                <?php elseif($_SESSION['rol'] === 'empleado'): ?>
                    <a href="panel_empleado.php" class="hover:text-gray-200">Panel Empleado</a>
                <?php else: ?>
                    <a href="gestionar_reservas.php" class="hover:text-gray-200">Reservar</a>
                    <a href="mis_reservas.php" class="hover:text-gray-200">Mis Reservas</a>
                <?php endif; ?>
                <a href="cerrar_sesion.php" class="hover:text-gray-200">Cerrar sesión</a>
            <?php else: ?>
                <a href="login.php" class="hover:text-gray-200">Iniciar sesión</a>
                <a href="registro.php" class="hover:text-gray-200">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
