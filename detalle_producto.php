<?php
include("conexion.php");

if(isset($_GET['id'])) {
    $id_producto = intval($_GET['id']);
    
    $query = "SELECT p.*, c.nombre_categoria 
              FROM productos p
              JOIN categorias c ON p.id_categoria = c.id_categoria
              WHERE p.id_producto = ?";
    
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_producto);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $producto = mysqli_fetch_assoc($result);
    
    if($producto) {
        echo '
        <div class="row">
            <div class="col-md-6">
                <img src="img/productos/'.htmlspecialchars($producto['imagen']).'" 
                     class="img-fluid rounded"
                     alt="'.htmlspecialchars($producto['nombre_producto']).'"
                     onerror="this.src=\'img/productos/default.jpg\'">
            </div>
            <div class="col-md-6">
                <h3>'.htmlspecialchars($producto['nombre_producto']).'</h3>
                <p class="text-muted">'.htmlspecialchars($producto['descripcion']).'</p>
                
                <div class="mb-3">
                    <span class="badge bg-info">'.htmlspecialchars($producto['nombre_categoria']).'</span>
                    <span class="badge bg-secondary ms-2">Código: '.htmlspecialchars($producto['codigo_producto']).'</span>
                </div>
                
                <h4 class="text-success">Bs. '.number_format($producto['precio_unitario'], 2).'</h4>
                <p class="text-muted">Stock disponible: '.$producto['stock'].' unidades</p>
                
                <div class="d-flex align-items-center mt-4">
                    <input type="number" id="modal-cantidad-'.$producto['id_producto'].'" 
                           value="1" min="1" max="'.$producto['stock'].'" class="form-control w-25 me-3">
                    <button onclick="agregarAlCarrito('.$producto['id_producto'].', true)" 
                            class="btn btn-success">
                        <i class="fas fa-cart-plus me-2"></i>Añadir al carrito
                    </button>
                </div>
            </div>
        </div>';
    } else {
        echo '<div class="alert alert-danger">Producto no encontrado</div>';
    }
}
?>