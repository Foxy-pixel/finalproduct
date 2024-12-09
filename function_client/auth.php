<?php
function checkUserLoggedIn() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: Login_logout/login.php');
        exit;
    }
}
