<form method="post" action="">
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['carrito_items'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                    <td><?php echo (int)$item['cantidad']; ?></td>
                    <td><?php echo number_format($item['precio'], 2); ?> €</td>
                    <td><?php echo number_format($item['subtotal'], 2); ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td><?php echo number_format($_SESSION['carrito_total'], 2); ?> €</td>
            </tr>
            <tr>
                <td colspan="4">
                    <button type="submit" name="limpiar_carrito" style="background-color: red; color: white; padding: 10px; border: none; cursor: pointer;">
                        Limpiar Carrito
                    </button>
                    <button type="submit" name="realizar_compra" style="background-color: green; color: white; padding: 10px; border: none; cursor: pointer;">
                        Realizar Compra
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
</form>
