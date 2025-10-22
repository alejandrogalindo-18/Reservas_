<?php
require_once '../Controllers/config.php';
require_once '../Controllers/controllers.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['cliente_id'], $data['destino'], $data['fecha'], $data['precio'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos."]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO reservas (cliente_id, destino, fecha, precio) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        htmlspecialchars($data['cliente_id']),
        htmlspecialchars($data['destino']),
        htmlspecialchars($data['fecha']),
        htmlspecialchars($data['precio'])
    ]);

    echo json_encode(["success" => true, "message" => "Reserva creada correctamente."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al crear: " . $e->getMessage()]);
}
?>
