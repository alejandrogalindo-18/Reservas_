<?php

require_once __DIR__ . '/../Controllers/config.php';

class Usuario
{
    private $pdo;

    // Constructor: recibe la conexión PDO desde config.php
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function crearUsuario($nombre, $correo, $contrasena, $rol = 'cliente')

        $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);

        if ($stmt->fetch()) {
            return ["success" => false, "message" => "El correo ya está registrado."];
        }

        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $correo, $hash, $rol]);

        return ["success" => true, "message" => "Usuario creado correctamente."];
    }

    public function iniciarSesion($correo, $contrasena)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            return ["success" => false, "message" => "Usuario no encontrado."];
        }

        if (!password_verify($contrasena, $usuario['contrasena'])) {
            return ["success" => false, "message" => "Contraseña incorrecta."];
        }

        unset($usuario['contrasena']);
        return ["success" => true, "message" => "Inicio de sesión exitoso.", "data" => $usuario];
    }

    public function obtenerUsuarios()
    {
        $stmt = $this->pdo->query("SELECT id, nombre, correo, rol, fecha_registro FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarUsuario($id, $nombre, $correo, $rol)
    {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ? WHERE id = ?");
        $stmt->execute([$nombre, $correo, $rol, $id]);

        return ["success" => true, "message" => "Usuario actualizado correctamente."];
    }

    public function eliminarUsuario($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return ["success" => true, "message" => "Usuario eliminado correctamente."];
    }
}
?>
