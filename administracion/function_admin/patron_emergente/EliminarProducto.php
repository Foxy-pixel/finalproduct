<?php
namespace FunctionAdmin\PatronEmergente;

use PDO;

class EliminarProductoCommand implements Command {
    private $pdo;
    private $id;

    public function __construct(PDO $pdo, $id) {
        $this->pdo = $pdo;
        $this->id = $id;
    }

    public function execute() {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $this->id]);
        echo "Producto eliminado correctamente.\n";
    }
}
?>
