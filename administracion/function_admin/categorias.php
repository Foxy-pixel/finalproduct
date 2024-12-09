<?php
include('../database/conexion.php');

// Clase CategoryFactory para manejar categorías
class CategoryFactory {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createCategory($nombre_categoria) {
        $sql = "INSERT INTO categorias (nombre) VALUES (:nombre)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nombre' => $nombre_categoria]);
        return "Categoría agregada correctamente.";
    }

    public function deleteCategory($id) {
        $sql = "DELETE FROM categorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return "Categoría eliminada correctamente.";
    }

    public function updateCategory($id, $nombre_categoria) {
        $sql = "UPDATE categorias SET nombre = :nombre WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nombre' => $nombre_categoria, 'id' => $id]);
        return "Categoría modificada correctamente.";
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categorias";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
