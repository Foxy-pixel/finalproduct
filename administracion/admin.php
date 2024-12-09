<?php
// Inclusión de archivos necesarios
include('function_admin/seguridad.php'); // Verifica si el usuario tiene acceso
include('../database/conexion.php');    // Conexión a la base de datos
include('function_admin/productos.php'); // Funciones relacionadas con productos
include('function_admin/categorias.php'); // Funciones relacionadas con categorías

// Instanciar la clase CategoryFactory para gestionar categorías
$categoryFactory = new CategoryFactory($pdo);

// Manejo de solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agregar nueva categoría
    if (isset($_POST['agregar_categoria'])) {
        echo $categoryFactory->createCategory($_POST['nombre_categoria']);
    }
    // Modificar categoría existente
    if (isset($_POST['modificar_categoria'])) {
        echo $categoryFactory->updateCategory($_POST['id_categoria'], $_POST['nombre_categoria']);
    }
    // Agregar nuevo producto
    if (isset($_POST['agregar_producto'])) {
        registrarProducto(
            $pdo,
            $_POST['nombre_producto'],
            $_POST['descripcion_producto'],
            (float)$_POST['precio_producto'],
            (int)$_POST['categoria_producto']
        );
    }
    // Modificar producto existente
    if (isset($_POST['modificar_producto'])) {
        modificarProducto(
            $pdo,
            (int)$_POST['id_producto'],
            $_POST['nombre_producto'],
            $_POST['descripcion_producto'],
            (float)$_POST['precio_producto'],
            (int)$_POST['categoria_producto']
        );
    }
}

// Manejo de solicitudes GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Eliminar categoría
    if (isset($_GET['eliminar_categoria'])) {
        echo $categoryFactory->deleteCategory((int)$_GET['eliminar_categoria']);
    }
    // Eliminar producto
    if (isset($_GET['eliminar_producto'])) {
        eliminarProducto($pdo, (int)$_GET['eliminar_producto']);
    }
}

// Obtener datos para las vistas
$productos = obtenerProductos($pdo, $_POST['nombre_busqueda'] ?? "");
$categorias = $categoryFactory->getAllCategories();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Productos y Categorías</title>
    <link rel="stylesheet" href="../styyles/admin_style.css">
</head>
<body>
    <header>
        <h1>Administración de Productos y Categorías</h1>
        <a href="../Login_logout/admin_logout.php">
            <button class="logout-button">Cerrar Sesión</button>
        </a>
    </header>

    <main>
        <!-- Administración de Usuarios -->
        <section>
            <h2>Administración de Usuarios</h2>
            <a href="usuarios.php">
                <button class="button">Ir a Usuarios</button>
            </a>
        </section>

        <!-- Formulario para agregar producto -->
        <section>
            <h2>Agregar Producto</h2>
            <form method="POST">
                <label for="nombre_producto">Nombre:</label>
                <input type="text" name="nombre_producto" required>
                <label for="descripcion_producto">Descripción:</label>
                <textarea name="descripcion_producto" required></textarea>
                <label for="precio_producto">Precio:</label>
                <input type="number" name="precio_producto" step="0.01" required>
                <label for="categoria_producto">Categoría:</label>
                <select name="categoria_producto" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="agregar_producto" class="button">Agregar Producto</button>
            </form>
        </section>

        <!-- Lista de productos -->
        <section>
            <h3>Lista de Productos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <form method="POST">
                            <td><input type="text" name="nombre_producto" value="<?php echo $producto['nombre']; ?>" required></td>
                            <td><textarea name="descripcion_producto" required><?php echo $producto['descripcion']; ?></textarea></td>
                            <td><input type="number" name="precio_producto" value="<?php echo $producto['precio']; ?>" step="0.01" required></td>
                            <td>
                                <select name="categoria_producto" required>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria['id']; ?>" <?php echo $producto['categoria_id'] == $categoria['id'] ? 'selected' : ''; ?>>
                                            <?php echo $categoria['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                                <button type="submit" name="modificar_producto" class="button">Modificar</button>
                                <a href="?eliminar_producto=<?php echo $producto['id']; ?>" class="button eliminar-btn">Eliminar</a>
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Formulario para agregar categoría -->
        <section>
            <h2>Agregar Categoría</h2>
            <form method="POST">
                <label for="nombre_categoria">Nombre:</label>
                <input type="text" name="nombre_categoria" required>
                <button type="submit" name="agregar_categoria" class="button">Agregar Categoría</button>
            </form>
        </section>

        <!-- Lista de categorías -->
        <section>
            <h3>Lista de Categorías</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo $categoria['nombre']; ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id_categoria" value="<?php echo $categoria['id']; ?>">
                                <input type="text" name="nombre_categoria" placeholder="Nuevo Nombre" required>
                                <button type="submit" name="modificar_categoria" class="button">Modificar</button>
                            </form>
                            <a href="?eliminar_categoria=<?php echo $categoria['id']; ?>" class="button eliminar-btn">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
