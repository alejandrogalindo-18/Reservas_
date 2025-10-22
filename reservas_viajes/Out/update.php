<?php
require_once '../Controllers/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'], $data['destino'], $data['fecha'], $data['precio'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos."]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE reservas SET destino=?, fecha=?, precio=? WHERE id=?");
    $stmt->execute([
        htmlspecialchars($data['destino']),
        htmlspecialchars($data['fecha']),
        htmlspecialchars($data['precio']),
        $data['id']
    ]);

    echo json_encode(["success" => true, "message" => "Reserva actualizada correctamente."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
