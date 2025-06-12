<?php
session_start(); // Iniciar sesión al principio
include("conexion.php");

// Verificar si el usuario está logueado
if(!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermark - Tu supermercado de confianza</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Nuestro CSS personalizado -->
    <link rel="stylesheet" href="estilos_usuario.css">
</head>
<body>
    <div class="contenedor-principal container-fluid px-0">
        <!-- Cabecera -->
        <header class="cabecera container py-3">
            <div class="row align-items-center">
                <div class="col-md-6 logo">
                    <img src="img/logo_supermark.png" alt="Supermark" class="img-fluid">
                    <h1 class="d-inline-block ms-3">Supermark</h1>
                </div>
                <div class="col-md-6 text-end info-usuario">
                    <span><i class="fas fa-user me-2"></i>Bienvenido, <?php echo htmlspecialchars($_SESSION['email']); ?></span>
                    <a href="cerrar_sesion.php" class="btn btn-outline-success btn-sm ms-3"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                </div>
            </div>
        </header>
        
        <!-- Menú de navegación -->
        <nav class="menu container mb-4">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:cargarContenido('inicio.php')"><i class="fas fa-home me-2"></i>INICIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:cargarContenido('productos.php')"><i class="fas fa-shopping-basket me-2"></i>PRODUCTOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:cargarContenido('nosotros.php')"><i class="fas fa-info-circle me-2"></i>SOBRE NOSOTROS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:cargarContenido('contacto.php')"><i class="fas fa-envelope me-2"></i>CONTACTO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:cargarContenido('carrito.php')">
                        <i class="fas fa-shopping-cart me-2"></i>MI CARRITO
                        <span id="contador-carrito" class="badge bg-danger rounded-pill ms-1">0</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Contenido principal -->
        <main class="container">
            <div id="contenido" class="bg-white p-4 rounded shadow-sm">
                <h2 class="mb-4"><i class="fas fa-store me-2"></i>Bienvenido a Supermark</h2>
                <p class="lead">Tu supermercado de confianza con los mejores precios.</p>
                <div class="row mt-4" id="contenido-dinamico">
                    <!-- El contenido se cargará aquí dinámicamente -->
                </div>
            </div>
        </main>
        
        <!-- Modal para productos -->
        <div id="modal-producto" class="modal">
            <div class="modal-contenido">
                <span class="cerrar">&times;</span>
                <h2 id="titulo-modal" class="mb-4"></h2>
                <div id="contenido-modal"></div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="text-white mb-3"><i class="fas fa-store me-2"></i>Supermark</h5>
                        <p>Tu supermercado de confianza con los mejores productos al mejor precio.</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-white mb-3">Contacto</h5>
                        <p><i class="fas fa-map-marker-alt me-2"></i> Av. Comercio 1234, Ciudad</p>
                        <p><i class="fas fa-phone me-2"></i> 777-12345</p>
                        <p><i class="fas fa-envelope me-2"></i> info@supermark.com</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-white mb-3">Horario</h5>
                        <p>Lunes a Viernes: 8:00 - 22:00</p>
                        <p>Sábados y Domingos: 9:00 - 21:00</p>
                    </div>
                </div>
                <hr class="bg-light">
                <p class="text-center mb-0">&copy; <?php echo date('Y'); ?> Supermark. Todos los derechos reservados.</p>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Nuestro JS personalizado -->
    <script src="script_usuario.js"></script>
</body>
</html>