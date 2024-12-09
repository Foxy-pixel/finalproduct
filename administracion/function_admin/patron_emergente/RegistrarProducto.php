<?php
namespace FunctionAdmin\PatronEmergente;

use PDO;

class RegistrarProductoCommand implements Command {
    private $pdo;
    private $nombre;
    private $descripcion;
    private $precio;
    private $categoria_id;

    public function __construct(PDO $pdo, $nombre, $descripcion, $precio, $categoria_id) {
        $this->pdo = $pdo;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->categoria_id = $categoria_id;
    }

    public function execute() {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id) 
                VALUES (:nombre, :descripcion, :precio, :categoria_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $this->nombre,
            ':descripcion' => $this->descripcion,
            ':precio' => $this->precio,
            ':categoria_id' => $this->categoria_id,
        ]);
        echo "Producto registrado correctamente.\n";
    }
}
?>
