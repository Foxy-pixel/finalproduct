<?php
require_once dirname(__DIR__, 2) . '/database/conexion.php';

function buscarUsuarios($busqueda)
{
    global $pdo;

    if ($busqueda) {
        $sql = "SELECT * FROM usuarios WHERE nombre LIKE :busqueda";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['busqueda' => "%$busqueda%"]);
    } else {
        $sql = "SELECT * FROM usuarios";
        $stmt = $pdo->query($sql);
    }

    return $stmt->fetchAll();
}

?>