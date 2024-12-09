<?php
function mostrarUsuarios($usuarios)
{
    foreach ($usuarios as $usuario) {
        echo "<tr id='usuario-{$usuario['id']}'>";
        echo "<td>{$usuario['id']}</td>";
        echo "<td>{$usuario['nombre']}</td>";
        echo "<td>{$usuario['correo']}</td>";
        echo "<td>{$usuario['fecha_registro']}</td>";
        echo "<td>";
        echo "<a href='usuarios.php?eliminar_usuario={$usuario['id']}' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\");'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }
}

?>