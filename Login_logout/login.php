<?php
session_start();
require_once '../database/conexion.php'; // Asegúrate de que este archivo esté en la misma carpeta o ajusta la ruta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    if (!empty($correo) && !empty($contrasena)) {
        try {
            // Consultar al usuario por su correo
            $stmt = $pdo->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE correo = :correo");
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validar usuario y contraseña (sin encriptación)
            if ($usuario && $usuario['contrasena'] === $contrasena) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];

                // Verificar si el usuario es administrador
                $stmtAdmin = $pdo->prepare("SELECT id FROM administradores WHERE usuario_id = :usuario_id");
                $stmtAdmin->bindParam(':usuario_id', $usuario['id']);
                $stmtAdmin->execute();
                if ($stmtAdmin->fetch()) {
                    $_SESSION['es_admin'] = true;
                } else {
                    $_SESSION['es_admin'] = false;
                }

                header('Location: ../index.php');
                exit;
            } else {
                $error = "Credenciales incorrectas.";
            }
        } catch (PDOException $e) {
            $error = "Error al intentar iniciar sesión: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="../styyles/style_login_client.css">

</head>
<body>
  <div>
    <h2>Iniciar Sesión</h2>
    <?php if (isset($error)): ?>
      <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <div>
        <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" required>
      </div>
      <div>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>
      </div>
      <button type="submit">Ingresar</button>
    </form>
    <div style="margin-top: 10px;">
      <a href="registro.php">¿No tienes cuenta? Regístrate aquí</a>
    </div>
  </div>
</body>
</html>
