<?php
require_once '../Controllers/config.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT r.id, u.nombre AS cliente, r.destino, r.fecha, r.precio 
                         FROM reservas r
                         JOIN usuarios u ON r.cliente_id = u.id
                         ORDER BY r.fecha DESC");
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "data" => $reservas]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al listar: " . $e->getMessage()]);
}
?>
