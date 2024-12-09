<?php
function editarNombre($pdo, $usuario_id, $nombre_nuevo) {
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nombre WHERE id = :id");
    $stmt->bindParam(':nombre', $nombre_nuevo);
    $stmt->bindParam(':id', $usuario_id);
    $stmt->execute();
    $_SESSION['nombre'] = $nombre_nuevo;
}
