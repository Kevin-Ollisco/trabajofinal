<?php
include("conexion.php");

if(isset($_GET['id_categoria'])) {
    $id_categoria = intval($_GET['id_categoria']);
    
    $query = "SELECT p.id_producto, p.codigo_producto, p.nombre_producto, p.descripcion, 
                     p.precio_unitario, p.imagen, c.nombre_categoria 
              FROM productos p
              JOIN categorias c ON p.id_categoria = c.id_categoria
              WHERE p.id_categoria = ? AND p.activo = 1
              ORDER BY p.nombre_producto";
    
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_categoria);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        echo '<div class="row">';
        
        while($producto = mysqli_fetch_assoc($result)) {
            echo '
            <div class="col-md-4 mb-4">
                <div class="card h-100 product-card">
                    <div class="card-img-container">
                        <img src="img/productos/'.htmlspecialchars($producto['imagen']).'" 
                             class="card-img-top p-3" 
                             alt="'.htmlspecialchars($producto['nombre_producto']).'"
                             onerror="this.src=\'img/productos/default.jpg\'">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">'.htmlspecialchars($producto['nombre_producto']).'</h5>
                        <p class="card-text text-muted">'.htmlspecialchars($producto['descripcion']).'</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-success">Bs. '.number_format($producto['precio_unitario'], 2).'</span>
                            <span class="badge bg-info">'.htmlspecialchars($producto['nombre_categoria']).'</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="number" id="cantidad-'.$producto['id_producto'].'" 
                                   value="1" min="1" class="form-control w-25">
                            <button onclick="verDetalleProducto('.$producto['id_producto'].')" 
                                    class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i> Detalles
                            </button>
                            <button onclick="agregarAlCarrito('.$producto['id_producto'].')" 
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
                <p class="lead">No hay productos disponibles en esta categoría</p>
              </div>';
    }
}
?>