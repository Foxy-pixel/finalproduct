<?php
function handleCartActions($pdo) {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Agregar al carrito
    if (isset($_POST['agregar_carrito'])) {
        $producto_id = $_POST['producto_id'];
        $cantidad = $_POST['cantidad'];
        if (isset($_SESSION['carrito'][$producto_id])) {
            $_SESSION['carrito'][$producto_id] += $cantidad;
        } else {
            $_SESSION['carrito'][$producto_id] = $cantidad;
        }
        header('Location: index.php');
        exit;
    }

    // Limpiar carrito
    if (isset($_POST['limpiar_carrito'])) {
        $_SESSION['carrito'] = [];
        $_SESSION['carrito_items'] = [];
        $_SESSION['carrito_total'] = 0;
        header('Location: index.php');
        exit;
    }

    // Realizar compra
    if (isset($_POST['realizar_compra'])) {
        if (!empty($_SESSION['carrito'])) {
            try {
                $pdo->beginTransaction();
                foreach ($_SESSION['carrito'] as $producto_id => $cantidad) {
                    $stmt = $pdo->prepare("
                        INSERT INTO ventas (producto_id, cantidad, fecha) 
                        VALUES (:producto_id, :cantidad, NOW())
                    ");
                    $stmt->bindParam(':producto_id', $producto_id);
                    $stmt->bindParam(':cantidad', $cantidad);
                    $stmt->execute();
                }
                $pdo->commit();
                
                // Vaciar carrito después de la compra
                $_SESSION['carrito'] = [];
                $_SESSION['carrito_items'] = [];
                $_SESSION['carrito_total'] = 0;

                echo "<p>Compra realizada con éxito.</p>";
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "<p>Error al realizar la compra: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p>El carrito está vacío. No se puede realizar la compra.</p>";
        }
        header('Location: index.php');
        exit;
    }
}

function displayCart($pdo) {
    $total = 0;
    $productos_carrito = [];
    foreach ($_SESSION['carrito'] as $producto_id => $cantidad) {
        $stmt = $pdo->prepare("SELECT nombre, precio FROM productos WHERE id = :id");
        $stmt->bindParam(':id', $producto_id);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        $subtotal = (float)$producto['precio'] * (int)$cantidad;
        $productos_carrito[] = [
            'nombre' => $producto['nombre'],
            'cantidad' => $cantidad,
            'precio' => (float)$producto['precio'],
            'subtotal' => $subtotal
        ];
        $total += $subtotal;
    }

    $_SESSION['carrito_items'] = $productos_carrito;
    $_SESSION['carrito_total'] = $total;

    if (!empty($productos_carrito)) {
        include 'cart_table.php';
    } else {
        echo "<p>Carrito vacío</p>";
    }
}
