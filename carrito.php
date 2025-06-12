<?php
session_start();
include("conexion.php");

// Verificar si el usuario está logueado
if(!isset($_SESSION['id_usuario'])) {
    die("Debe iniciar sesión para ver el carrito");
}

// Obtener los productos del carrito desde la base de datos
$id_usuario = $_SESSION['id_usuario'];
$query = "SELECT p.*, c.cantidad 
          FROM carrito c
          JOIN productos p ON c.id_producto = p.id_producto
          WHERE c.id_usuario = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$carrito = [];
$total = 0;

while($row = mysqli_fetch_assoc($result)) {
    $carrito[$row['id_producto']] = $row;
    $total += $row['precio_unitario'] * $row['cantidad'];
}
?>

<div class="container">
    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Mi Carrito</h2>
    
    <?php if(empty($carrito)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Tu carrito está vacío. <a href="javascript:cargarContenido('productos.php')" class="alert-link">Explora nuestros productos</a> para comenzar a comprar.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 10%">Imagen</th>
                        <th scope="col" style="width: 30%">Producto</th>
                        <th scope="col" style="width: 15%">Precio</th>
                        <th scope="col" style="width: 20%">Cantidad</th>
                        <th scope="col" style="width: 15%">Subtotal</th>
                        <th scope="col" style="width: 10%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($carrito as $id => $item): 
                        $subtotal = $item['precio_unitario'] * $item['cantidad'];
                    ?>
                    <tr id="producto-<?php echo $id; ?>">
                        <td>
                            <img src="img/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>" class="img-thumbnail" style="max-width: 80px;">
                        </td>
                        <td>
                            <h5 class="mb-1"><?php echo htmlspecialchars($item['nombre_producto']); ?></h5>
                            <small class="text-muted">Código: <?php echo htmlspecialchars($item['codigo_producto']); ?></small>
                        </td>
                        <td>
                            <span class="precio">Bs. <?php echo number_format($item['precio_unitario'], 2); ?></span>
                        </td>
                        <td>
                            <div class="input-group" style="max-width: 120px;">
                                <button class="btn btn-outline-secondary btn-sm" onclick="actualizarCantidad(<?php echo $id; ?>, -1)">-</button>
                                <input type="number" id="cantidad-<?php echo $id; ?>" class="form-control form-control-sm text-center" value="<?php echo $item['cantidad']; ?>" min="1" onchange="actualizarCantidadInput(<?php echo $id; ?>)">
                                <button class="btn btn-outline-secondary btn-sm" onclick="actualizarCantidad(<?php echo $id; ?>, 1)">+</button>
                            </div>
                        </td>
                        <td>
                            <span class="subtotal fw-bold">Bs. <?php echo number_format($subtotal, 2); ?></span>
                        </td>
                        <td>
                           <button class="btn btn-outline-danger btn-sm btn-eliminar" data-id="<?php echo $id; ?>">
                               <i class="fas fa-trash-alt"></i>
                           </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total:</td>
                        <td colspan="2" class="fw-bold text-primary">Bs. <?php echo number_format($total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="javascript:cargarContenido('productos.php')" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Seguir comprando
            </a>
            <div>
               <button class="btn btn-outline-danger me-2 btn-vaciar-carrito">
                 <i class="fas fa-trash me-2"></i>Vaciar carrito
               </button>
                <a href="javascript:procederPago()" class="btn btn-success">
                    <i class="fas fa-credit-card me-2"></i>Proceder al pago
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>


