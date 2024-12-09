<?php

interface CartImplementationInterface {
    public function addItem($productId, $quantity);
    public function clearCart();
    public function getItems();
    public function getTotal();
}
