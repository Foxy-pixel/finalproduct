<?php
require '../database/conexion.php';
require_once __DIR__ . '/function_admin_users/buscar_usuarios.php';
require_once __DIR__ . '/function_admin_users/eliminar_usuario.php';
require_once __DIR__ . '/function_admin_users/lista_usuarios.php';

$busqueda = "";

// Procesar búsqueda de usuarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_usuario'])) {
    $busqueda = $_POST['nombre_busqueda'];
}

// Procesar eliminación de usuarios
$mensaje = "";
if (isset($_GET['eliminar_usuario'])) {
    $idUsuario = intval($_GET['eliminar_usuario']); // Aseguramos que el ID sea un entero
    $mensaje = eliminarUsuario($idUsuario);
    header("Location: usuarios.php?mensaje=" . urlencode($mensaje)); // Redirigir para evitar reenvíos
    exit();
}

// Obtener lista de usuarios
$usuarios = buscarUsuarios($busqueda);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link rel="stylesheet" href="../styyles/style_admin_users.css">
</head>
<body>
    <h1>Administración de Usuarios</h1>

    <h2>Regresar a Productos</h2>
    <a href="admin.php">
        <button>Ir a Productos</button>
    </a>

    <?php if (!empty($_GET['mensaje'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
    <?php endif; ?>

    <h2>Buscar Usuario</h2>
    <form method="POST">
        <input type="text" name="nombre_busqueda" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar usuario">
        <button type="submit" name="buscar_usuario">Buscar</button>
    </form>

    <h3>Lista de Usuarios</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>contrasena</th>
            <th>Fecha de Registro</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach ($usuarios as $usuario) {
            // Verificamos si es administrador antes de mostrar el botón de eliminación
            $isAdmin = false;
            $sql = "SELECT * FROM administradores WHERE usuario_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $usuario['id']]);
            $isAdmin = $stmt->fetch();

            echo "<tr id='usuario-{$usuario['id']}'>";
            echo "<td>{$usuario['id']}</td>";
            echo "<td>{$usuario['nombre']}</td>";
            echo "<td>{$usuario['correo']}</td>";
            echo "<td>{$usuario['contrasena']}</td>";
            echo "<td>{$usuario['fecha_registro']}</td>";
            
            echo "<td>";
            if (!$isAdmin) {
                // Si no es un administrador, mostramos el botón de eliminación
                echo "<a href='usuarios.php?eliminar_usuario={$usuario['id']}' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\");'>Eliminar</a>";
            } else {
                // Si es un administrador, mostramos el texto "Administrador"
                echo "Administrador";
            }
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
