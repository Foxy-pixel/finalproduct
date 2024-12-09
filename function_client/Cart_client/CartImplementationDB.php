<?php

require_once __DIR__ . '/CartImplementationInterface.php';

class CartImplementationDB implements CartImplementationInterface {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addItem($productId, $quantity) {
        if (!isset($_SESSION['carrito'][$productId])) {
            $_SESSION['carrito'][$productId] = 0;
        }
        $_SESSION['carrito'][$productId] += $quantity;
    }

    public function clearCart() {
        $_SESSION['carrito'] = [];
    }

    public function getItems() {
        $items = [];
        foreach ($_SESSION['carrito'] as $productId => $quantity) {
            $stmt = $this->pdo->prepare("SELECT nombre, precio FROM productos WHERE id = :id");
            $stmt->bindParam(':id', $productId);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $subtotal = (float)$product['precio'] * (int)$quantity;
                $items[] = [
                    'nombre' => $product['nombre'],
                    'cantidad' => $quantity,
                    'precio' => (float)$product['precio'],
                    'subtotal' => $subtotal,
                ];
            }
        }
        return $items;
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
}
