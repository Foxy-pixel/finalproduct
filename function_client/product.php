<?php
function getCategorias($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM categorias");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductos($pdo, $buscar, $categoria_id) {
    if ($buscar && $categoria_id) {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE (nombre LIKE :buscar OR descripcion LIKE :buscar) AND categoria_id = :categoria_id");
        $stmt->bindValue(':buscar', '%' . $buscar . '%');
        $stmt->bindParam(':categoria_id', $categoria_id);
    } elseif ($buscar) {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE nombre LIKE :buscar OR descripcion LIKE :buscar");
        $stmt->bindValue(':buscar', '%' . $buscar . '%');
    } elseif ($categoria_id) {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria_id = :categoria_id");
        $stmt->bindParam(':categoria_id', $categoria_id);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM productos");
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
