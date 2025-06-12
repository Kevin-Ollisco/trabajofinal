<?php
session_start(); // Iniciar sesión al principio
include("conexion.php");
require("verificarsesion.php");
// Verificar si el usuario está logueado y es administrador

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermark - Panel de Administración</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Nuestro CSS personalizado -->
    <link rel="stylesheet" href="estilos_usuario.css">
    <link rel="stylesheet" href="botones.css">
    <link rel="stylesheet" href="tablas.css">
</head>
<body>
    <div class="contenedor-principal container-fluid px-0">
        <!-- Cabecera -->
        <header class="cabecera container py-3">
            <div class="row align-items-center">
                <div class="col-md-6 logo">
                    <img src="img/logo_supermark.png" alt="Supermark" class="img-fluid">
                    <h1 class="d-inline-block ms-3">Supermark <small class="text-muted">Admin</small></h1>
                </div>
                <div class="col-md-6 text-end info-usuario">
                    <span><i class="fas fa-user-shield me-2"></i>Administrador: <?php echo htmlspecialchars($_SESSION['email']); ?></span>
                    <a href="cerrar_sesion.php" class="btn btn-outline-danger btn-sm ms-3"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                </div>
            </div>
        </header>
        
        <!-- Menú de navegación -->
        <nav class="menu container mb-4">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:cargarContenido('admin_usuarios.php')"><i class="fas fa-users me-2"></i>USUARIOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:cargarContenido('admin_productos.php')"><i class="fas fa-boxes me-2"></i>PRODUCTOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:cargarContenido('admin_facturas.php')"><i class="fas fa-file-invoice-dollar me-2"></i>FACTURAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:cargarContenido('admin_reportes.php')"><i class="fas fa-chart-bar me-2"></i>REPORTES</a>
                </li>
            </ul>
        </nav>
        
        <!-- Contenido principal -->
        <main class="container">
            <div id="contenido" class="bg-white p-4 rounded shadow-sm">
                <h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Panel de Administración</h2>
                <p class="lead">Bienvenido al panel de administración de Supermark.</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Seleccione una opción del menú para comenzar.
                </div>
            </div>
        </main>
        
        <!-- Modal para contenido dinámico -->
        <div id="modal-admin" class="modal">
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
                        <p>Panel de administración del supermercado.</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-white mb-3">Contacto</h5>
                        <p><i class="fas fa-map-marker-alt me-2"></i> Av. Comercio 1234, Ciudad</p>
                        <p><i class="fas fa-phone me-2"></i> 777-12345</p>
                        <p><i class="fas fa-envelope me-2"></i> admin@supermark.com</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-white mb-3">Sistema</h5>
                        <p>Usuarios registrados: <?php 
                            $sql = "SELECT COUNT(*) as total FROM usuarios";
                            $result = $con->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                        ?></p>
                        <p>Productos en stock: <?php 
                            $sql = "SELECT COUNT(*) as total FROM productos WHERE activo = 1";
                            $result = $con->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                        ?></p>
                    </div>
                </div>
                <hr class="bg-light">
                <p class="text-center mb-0">&copy; <?php echo date('Y'); ?> Supermark. Panel de Administración.</p>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Nuestro JS personalizado -->
    <script src="script_admin.js"></script>
</body>
</html>