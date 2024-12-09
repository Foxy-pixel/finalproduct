<?php
namespace FunctionAdmin\PatronEmergente;

use PDO;

class ModificarProductoCommand implements Command {
    private $pdo;
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $categoria_id;

    public function __construct(PDO $pdo, $id, $nombre, $descripcion, $precio, $categoria_id) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->categoria_id = $categoria_id;
    }

    public function execute() {
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, categoria_id = :categoria_id 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $this->id,
            ':nombre' => $this->nombre,
            ':descripcion' => $this->descripcion,
            ':precio' => $this->precio,
            ':categoria_id' => $this->categoria_id,
        ]);
        echo "Producto modificado correctamente.\n";
    }
}
?>
