<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$reservas = $conn->query("SELECT * FROM reservas ORDER BY fecha_reserva DESC")->fetchAll(PDO::FETCH_ASSOC);
$viajes = $conn->query("SELECT * FROM viajes")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="flex-grow container mx-auto py-10">
    <h2 class="text-3xl font-bold text-blue-700 mb-6">Panel de Administración</h2>

    <h3 class="text-xl font-semibold mb-3">Viajes disponibles</h3>
    <table class="w-full mb-8 border">
        <tr class="bg-blue-600 text-white">
            <th class="p-2">Destino</th>
            <th>Precio</th>
            <th>Disponibilidad</th>
            <th>Fecha salida</th>
        </tr>
        <?php foreach ($viajes as $v): ?>
        <tr class="text-center bg-white border-b">
            <td class="p-2"><?php echo $v['destino']; ?></td>
            <td>$<?php echo $v['precio']; ?></td>
            <td><?php echo $v['disponibilidad']; ?></td>
            <td><?php echo $v['fecha_salida']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3 class="text-xl font-semibold mb-3">Reservas registradas</h3>
    <table class="w-full border">
        <tr class="bg-blue-600 text-white">
            <th class="p-2">Usuario ID</th>
            <th>Destino</th>
            <th>Fecha viaje</th>
            <th>Estado</th>
            <th>Precio</th>
        </tr>
        <?php foreach ($reservas as $r): ?>
        <tr class="text-center bg-white border-b">
            <td class="p-2"><?php echo $r['usuario_id']; ?></td>
            <td><?php echo $r['destino']; ?></td>
            <td><?php echo $r['fecha_viaje']; ?></td>
            <td><?php echo $r['estado']; ?></td>
            <td>$<?php echo $r['precio']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include '../includes/footer.php'; ?>
