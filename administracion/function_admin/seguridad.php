<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../Login_logout/admin_login.php");
    exit();
}
?>
