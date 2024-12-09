<?php
// Incluir archivo de conexión
require_once '../database/conexion.php'; // Asegúrate de que este archivo esté en la misma carpeta o ajusta la ruta
session_start(); // Iniciar sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';

    if (empty($nombre) || empty($correo) || empty($contrasena) || empty($confirmar_contrasena)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden.";
    } else {
        try {
            // Verificar si el correo ya está registrado
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo");
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();

            if ($stmt->fetch()) {
                $error = "El correo ya está registrado.";
            } else {
                // Insertar nuevo usuario
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (:nombre, :correo, :contrasena)");
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':contrasena', $contrasena); // Sin encriptar (recomendación: usar hash)
                $stmt->execute();

                // Obtener el ID del usuario registrado
                $usuario_id = $pdo->lastInsertId();

                // Iniciar sesión automáticamente
                $_SESSION['usuario_id'] = $usuario_id;
                $_SESSION['nombre'] = $nombre;

                // Redirigir al usuario a la página principal
                header("Location: ../index.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styyles/style_registrer.css">
  <title>Registro de Usuario</title>
</head>
<body>
  <div>
    <h2>Registro de Usuario</h2>
    <?php if (isset($error)): ?>
      <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <div>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
      </div>
      <div>
        <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" required>
      </div>
      <div>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>
      </div>
      <div>
        <label for="confirmar_contrasena">Confirmar Contraseña:</label>
        <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required>
      </div>
      <button type="submit">Registrarse</button>
    </form>
    <div style="margin-top: 15px;">
      <a href="login.php">¿Ya tienes cuenta? Inicia sesión aquí</a>
    </div>
  </div>
</body>
</html>
