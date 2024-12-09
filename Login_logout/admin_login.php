<?php
session_start();
include('../database/conexion.php'); // Conexión a la base de datos

// Manejo del inicio de sesión
if (isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Consulta para verificar credenciales de administrador
    $sql = "
        SELECT u.id, u.nombre, u.contrasena 
        FROM usuarios u
        INNER JOIN administradores a ON u.id = a.usuario_id
        WHERE u.correo = :correo
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['correo' => $correo]);
    $usuario = $stmt->fetch();

    // Validar que el usuario exista y que la contraseña coincida
    if ($usuario && $contrasena === $usuario['contrasena']) {
        // Credenciales válidas, establecer sesión y redirigir a admin.php
        $_SESSION['admin'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        header("Location: ../administracion/admin.php");
        exit();
    } else {
        // Credenciales inválidas
        $error = "Correo o contraseña incorrectos, o no tiene acceso como administrador.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Administrador</title>
    <link rel="stylesheet" href="../styyles/style_login_admin.css">
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit" name="login">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>

