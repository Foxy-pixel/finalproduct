<?php
namespace FunctionAdmin\PatronEmergente;

use PDO;

class ProductoQueryHandler {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodosLosProductos() {
        $sql = "SELECT * FROM productos";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarProductoPorNombre($nombre) {
        $sql = "SELECT * FROM productos WHERE nombre LIKE :nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nombre' => "%$nombre%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
