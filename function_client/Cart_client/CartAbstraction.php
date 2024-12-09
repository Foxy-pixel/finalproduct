<?php

class CartAbstraction {
    private $implementation;

    public function __construct(CartImplementationInterface $implementation) {
        $this->implementation = $implementation;
    }

    public function addItem($productId, $quantity) {
        $this->implementation->addItem($productId, $quantity);
    }

    public function clearCart() {
        $this->implementation->clearCart();
    }

    public function getItems() {
        return $this->implementation->getItems();
    }

    public function getTotal() {
        return $this->implementation->getTotal();
    }
}
