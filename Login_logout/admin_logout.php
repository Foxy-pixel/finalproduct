<?php
session_start();
session_destroy(); // Cierra la sesión actual
header("Location: admin_login.php"); // Redirige a la página de inicio de sesión
exit();
?>
