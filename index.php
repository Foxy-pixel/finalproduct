<?php
session_start();
require_once 'database/conexion.php';
require_once 'function_client/auth.php';
require_once 'function_client/product.php';
require_once 'function_client/cart.php';
require_once 'function_client/user.php';

// Verificar si el usuario está logueado
checkUserLoggedIn();

// Variables necesarias
$usuario_id = $_SESSION['usuario_id'];
$buscar = $_GET['buscar'] ?? '';
$categoria_id = $_GET['categoria_id'] ?? '';

// Obtener categorías y productos
$categorias = getCategorias($pdo);
$productos = getProductos($pdo, $buscar, $categoria_id);

// Manejar acciones del carrito
handleCartActions($pdo);

// Manejar edición de nombre
if (isset($_POST['editar_nombre'])) {
    editarNombre($pdo, $usuario_id, $_POST['nombre']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta</title>
    <link rel="stylesheet" href="styyles/style_index.css">
</head>
<body>
<div class="punto-venta-container">
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h2>
    <form method="POST" action="Login_logout/logout.php" style="display:inline;">
        <button type="submit" name="cerrar_sesion">Cerrar sesión</button>
    </form>
    <button type="button" onclick="mostrarFormulario()">Editar Nombre</button>
    <form method="POST" id="form-editar-nombre" style="display:none; margin-top:10px;">
        <input type="text" name="nombre" placeholder="Nuevo nombre" required>
        <button type="submit" name="editar_nombre">Guardar</button>
    </form>

    <h3>Buscar Productos</h3>
    <form method="GET" action="index.php">
        <input type="text" name="buscar" value="<?php echo htmlspecialchars($buscar); ?>" placeholder="Buscar">
        <select name="categoria_id">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id']; ?>" <?php echo ($categoria['id'] == $categoria_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Buscar</button>
    </form>

    <h3>Productos</h3>
    <div class="productos">
        <?php foreach ($productos as $producto): ?>
            <div class="product-card">
                <h4><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                <p>Precio: $<?php echo htmlspecialchars($producto['precio']); ?></p>
                <form method="POST">
                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                    <input type="number" name="cantidad" value="1" min="1">
                    <button type="submit" name="agregar_carrito">Agregar</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Carrito Fijo -->
<div class="cart-section">
    <h3>Carrito</h3>
    <?php displayCart($pdo); ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>
function realizarCompra() {
    const productos = <?php echo json_encode($_SESSION['carrito_items']); ?>;
    const total = <?php echo $_SESSION['carrito_total']; ?>;
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    let y = 10;
    doc.text("Ticket de Compra", 10, y);
    y += 10;

    productos.forEach((item, index) => {
        doc.text(`${index + 1}. ${item.nombre}`, 10, y);
        doc.text(`Cantidad: ${item.cantidad}`, 10, y + 10);
        doc.text(`Precio: $${item.precio}`, 10, y + 20);
        doc.text(`Subtotal: $${item.subtotal}`, 10, y + 30);
        y += 40;
    });

    doc.text(`Total: $${total}`, 10, y);
    doc.save("ticket_compra.pdf");
    alert("Compra realizada con éxito. Descargando ticket...");
}

function mostrarFormulario() {
    const form = document.getElementById("form-editar-nombre");
    form.style.display = form.style.display === "none" ? "block" : "none";
}
</script>
</body>
</html>

