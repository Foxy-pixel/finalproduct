<?php
require_once dirname(__DIR__, 2) . '/database/conexion.php';

function eliminarUsuario($id)
{
    global $pdo;

    // Verificar si es administrador
    $sql = "SELECT * FROM administradores WHERE usuario_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $isAdmin = $stmt->fetch();

    if ($isAdmin) {
        return "No puedes eliminar a un administrador.";
    }

    // Eliminar usuario
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return "Usuario eliminado correctamente.";
}

?>