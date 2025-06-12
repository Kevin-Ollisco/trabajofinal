<?php
// verificarrol.php

// Verificar si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el rol está definido en la sesión
if (!isset($_SESSION['rol'])) {
    header("Location: login.html?error=acceso_no_autorizado");
    exit();
}

// Opcional: Lista de roles permitidos para ciertas páginas
// Puedes personalizar esto según tus necesidades
$rolesPermitidos = ['Administrador']; // Por defecto solo admin

// Si se pasa un parámetro con roles permitidos
if (isset($rolesPermitidosCustom)) {
    $rolesPermitidos = $rolesPermitidosCustom;
}

// Verificar si el rol del usuario está entre los permitidos
if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("Location: pagina_usuario.php?error=permisos_insuficientes");
    exit();
}
?>