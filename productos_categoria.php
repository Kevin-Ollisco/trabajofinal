<?php
include("conexion.php");

// Verificar conexión primero
if (!$con) {
    die("<div class='alert alert-danger'>Error de conexión a la base de datos</div>");
}

if (isset($_GET['id'])) {
    $id_categoria = intval($_GET['id']);
    
    // Consulta corregida - asegúrate que los nombres de columnas coincidan con tu BD
    $query = "SELECT p.id_producto, p.nombre_producto as nombre, p.descripcion, 
                     p.precio_unitario as precio, p.imagen 
              FROM productos p 
              WHERE p.id_categoria = ?";
    
    // Preparar la consulta con verificación de error
    $stmt = mysqli_prepare($con, $query);
    
    if (!$stmt) {
        die("<div class='alert alert-danger'>Error al preparar la consulta: " . mysqli_error($con) . "</div>");
    }
    
    // Bind parameters
    if (!mysqli_stmt_bind_param($stmt, "i", $id_categoria)) {
        die("<div class='alert alert-danger'>Error al vincular parámetros: " . mysqli_stmt_error($stmt) . "</div>");
    }
    
    // Ejecutar consulta
    if (!mysqli_stmt_execute($stmt)) {
        die("<div class='alert alert-danger'>Error al ejecutar la consulta: " . mysqli_stmt_error($stmt) . "</div>");
    }
    
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        die("<div class='alert alert-danger'>Error al obtener resultados: " . mysqli_error($con) . "</div>");
    }
    
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="row">';
        while ($producto = mysqli_fetch_assoc($result)) {
            echo '
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="img/productos/' . htmlspecialchars($producto['imagen']) . '" 
                         class="card-img-top" 
                         alt="' . htmlspecialchars($producto['nombre']) . '"
                         onerror="this.src=\'img/productos/default.jpg\'">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($producto['nombre']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($producto['descripcion']) . '</p>
                        <p class="h5 text-success">Bs. ' . number_format($producto['precio'], 2) . '</p>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="number" id="cantidad-' . $producto['id_producto'] . '" 
                                   value="1" min="1" class="form-control w-25">
                            <button onclick="verDetalleProducto(' . $producto['id_producto'] . ')" 
                                    class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                            <button onclick="agregarAlCarrito(' . $producto['id_producto'] . ')" 
                                    class="btn btn-sm btn-success">
                                <i class="fas fa-cart-plus"></i> Añadir
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
        }
        echo '</div>';
    } else {
        echo '<div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <p class="lead">No hay productos en esta categoría</p>
              </div>';
    }
    
    // Cerrar el statement
    mysqli_stmt_close($stmt);
} else {
    echo '<div class="alert alert-warning">No se especificó una categoría</div>';
}

// No es necesario cerrar $con aquí ya que PHP lo hará automáticamente al final del script
?>