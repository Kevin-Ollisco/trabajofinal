<?php
// verificarsesion.php

// Verificar si la sesión no está iniciada antes de iniciarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

// Verificar si el usuario está activo (opcional)
if (isset($_SESSION['activo']) && $_SESSION['activo'] != 1) {
    header("Location: login.html?error=cuenta_inactiva");
    exit();
}
?>