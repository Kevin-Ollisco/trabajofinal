<?php

include("conexion.php");

// Verificar conexión
if(!$con) {
    die("<div class='alert alert-danger'>Error de conexión a la base de datos</div>");
}

// Obtener categorías (sin filtro activo)
$query = "SELECT id_categoria, nombre_categoria FROM categorias"; // Eliminado WHERE activo = 1
$result = mysqli_query($con, $query);

if(!$result) {
    die("<div class='alert alert-danger'>Error en la consulta: ".mysqli_error($con)."</div>");
}
?>

<!-- El resto del código permanece igual -->

<div class="container">
    <h2 class="mb-4"><i class="fas fa-shopping-basket me-2"></i>Nuestros Productos</h2>
    
    <!-- Menú de categorías -->
    <div class="mb-4">
        <h4>Categorías</h4>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <div class="d-flex flex-wrap gap-2" id="categorias-container">
                <?php while($categoria = mysqli_fetch_assoc($result)): ?>
                    <button onclick="mostrarProductos(<?php echo $categoria['id_categoria']; ?>)" 
                            class="btn btn-outline-primary categoria-btn">
                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                    </button>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No se encontraron categorías</div>
        <?php endif; ?>
    </div>
    
    <!-- Contenedor para productos -->
    <div id="lista-productos" class="row">
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <p class="lead">Selecciona una categoría para ver los productos disponibles</p>
        </div>
    </div>
</div>