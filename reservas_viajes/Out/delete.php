<?php
require_once '../Controllers/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "ID no recibido."]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Reserva eliminada correctamente."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al eliminar: " . $e->getMessage()]);
}
?>
