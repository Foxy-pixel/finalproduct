<?php
// productos.php
// Archivo para centralizar las funciones relacionadas con productos.

/**
 * Obtener todos los productos o buscar por nombre.
 * @param PDO $pdo Conexión a la base de datos.
 * @param string|null $nombreBusqueda Nombre del producto a buscar (opcional).
 * @return array Lista de productos.
 */
function obtenerProductos(PDO $pdo, string $nombreBusqueda = null): array {
    $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, c.nombre AS categoria, c.id AS categoria_id 
              FROM productos p
              INNER JOIN categorias c ON p.categoria_id = c.id";
    
    // Si hay un nombre de búsqueda, se filtran los resultados.
    if (!empty($nombreBusqueda)) {
        $query .= " WHERE p.nombre LIKE :nombreBusqueda";
    }

    $stmt = $pdo->prepare($query);
    if (!empty($nombreBusqueda)) {
        $stmt->bindValue(':nombreBusqueda', "%$nombreBusqueda%");
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Registrar un nuevo producto.
 * @param PDO $pdo Conexión a la base de datos.
 * @param string $nombre Nombre del producto.
 * @param string $descripcion Descripción del producto.
 * @param float $precio Precio del producto.
 * @param int $categoriaId ID de la categoría del producto.
 * @return void
 */
function registrarProducto(PDO $pdo, string $nombre, string $descripcion, float $precio, int $categoriaId): void {
    $query = "INSERT INTO productos (nombre, descripcion, precio, categoria_id) VALUES (:nombre, :descripcion, :precio, :categoria_id)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':precio' => $precio,
        ':categoria_id' => $categoriaId
    ]);
}

/**
 * Modificar un producto existente.
 * @param PDO $pdo Conexión a la base de datos.
 * @param int $id ID del producto.
 * @param string $nombre Nombre del producto.
 * @param string $descripcion Descripción del producto.
 * @param float $precio Precio del producto.
 * @param int $categoriaId ID de la categoría del producto.
 * @return void
 */
function modificarProducto(PDO $pdo, int $id, string $nombre, string $descripcion, float $precio, int $categoriaId): void {
    $query = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, categoria_id = :categoria_id WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id' => $id,
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':precio' => $precio,
        ':categoria_id' => $categoriaId
    ]);
}

/**
 * Eliminar un producto por su ID.
 * @param PDO $pdo Conexión a la base de datos.
 * @param int $id ID del producto a eliminar.
 * @return void
 */
function eliminarProducto(PDO $pdo, int $id): void {
    $query = "DELETE FROM productos WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);
}
