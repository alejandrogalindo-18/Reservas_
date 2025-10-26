<?php
include '../config/config.php';
include '../includes/header.php';
include '../includes/navbar.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM reservas WHERE usuario_id = ?");
$stmt->execute([$_SESSION['id']]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="flex-grow container mx-auto py-10">
    <h2 class="text-3xl font-bold text-blue-700 mb-6">Mis reservas</h2>

    <?php if (empty($reservas)): ?>
        <p class="text-gray-600">Aún no has realizado ninguna reserva.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($reservas as $r): ?>
            <div class="bg-white shadow-md rounded-lg p-5">
                <h3 class="text-xl font-semibold text-blue-700"><?php echo $r['destino']; ?></h3>
                <p class="text-gray-600">Fecha de viaje: <?php echo $r['fecha_viaje']; ?></p>
                <p class="text-gray-600">Precio: $<?php echo $r['precio']; ?></p>
                <p class="text-gray-600">Estado: <?php echo $r['estado']; ?></p>
                <p class="text-sm text-gray-400">Reservado el: <?php echo $r['fecha_reserva']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
