<?php

require_once __DIR__ . '/../Controllers/config.php';

class Reservacion
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function crear($usuario_id, $destino, $fecha_salida, $fecha_regreso, $cantidad_personas)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservaciones (usuario_id, destino, fecha_salida, fecha_regreso, cantidad_personas)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$usuario_id, $destino, $fecha_salida, $fecha_regreso, $cantidad_personas]);

        return ["success" => true, "message" => "Reserva creada correctamente."];
    }

    public function obtenerTodas($rol, $usuario_id)
    {
        if ($rol === 'admin') {
            $stmt = $this->pdo->query("
                SELECT r.id, u.nombre AS usuario, r.destino, r.fecha_salida, r.fecha_regreso, 
                       r.cantidad_personas, r.estado, r.fecha_creacion
                FROM reservaciones r
                INNER JOIN usuarios u ON r.usuario_id = u.id
                ORDER BY r.id DESC
            ");
        } elseif ($rol === 'agente') {
            $stmt = $this->pdo->query("
                SELECT r.id, u.nombre AS usuario, r.destino, r.fecha_salida, r.fecha_regreso, 
                       r.cantidad_personas, r.estado, r.fecha_creacion
                FROM reservaciones r
                INNER JOIN usuarios u ON r.usuario_id = u.id
                ORDER BY r.id DESC
            ");
        } else {
            $stmt = $this->pdo->prepare("
                SELECT * FROM reservaciones WHERE usuario_id = ? ORDER BY id DESC
            ");
            $stmt->execute([$usuario_id]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $destino, $fecha_salida, $fecha_regreso, $cantidad_personas, $estado)
    {
        $stmt = $this->pdo->prepare("
            UPDATE reservaciones 
            SET destino = ?, fecha_salida = ?, fecha_regreso = ?, cantidad_personas = ?, estado = ?
            WHERE id = ?
        ");
        $stmt->execute([$destino, $fecha_salida, $fecha_regreso, $cantidad_personas, $estado, $id]);

        return ["success" => true, "message" => "Reserva actualizada correctamente."];
    }

    public function eliminar($id, $rol, $usuario_id)
    {
        if ($rol === 'cliente') {
            $stmt = $this->pdo->prepare("DELETE FROM reservaciones WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $usuario_id]);
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM reservaciones WHERE id = ?");
            $stmt->execute([$id]);
        }

        return ["success" => true, "message" => "Reserva eliminada correctamente."];
    }
}
?>
