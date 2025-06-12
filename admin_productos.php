<?php
require("verificarsesion.php");
require("verificarrol.php");
include("conexion.php");

$sql = "SELECT p.id_producto, p.codigo_producto, p.nombre_producto, p.precio_unitario, p.stock, 
               c.nombre_categoria as categoria, p.activo
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria";
$resultado = $con->query($sql);
?>

<h3><i class="fas fa-boxes me-2"></i>Gestión de Productos</h3>
<hr>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Categoría</th>
            <th>Estado</th>
            <th>Operaciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_array($resultado)) { ?>
        <tr>
            <td><?php echo $row['codigo_producto']; ?></td>
            <td><?php echo $row['nombre_producto']; ?></td>
            <td>Bs. <?php echo number_format($row['precio_unitario'], 2); ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td><?php echo $row['categoria']; ?></td>
            <td><?php echo $row['activo'] ? 'Activo' : 'Inactivo'; ?></td>
            <td>
                <button onclick="editarProducto('<?php echo $row['id_producto']; ?>')" class="btn btn-editar">
                    <i class="fas fa-edit me-1"></i>Editar
                </button>
                <button onclick="eliminarProducto('<?php echo $row['id_producto']; ?>')" class="btn btn-eliminar">
                    <i class="fas fa-trash me-1"></i>Eliminar
                </button>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<button onclick="cargarContenido('formcreate_productos.php')" class="btn btn-insertar">
    <i class="fas fa-plus-circle me-1"></i>Nuevo Producto
</button>

<script>
function editarProducto(id) {
    fetch('formedit_productos.php?id=' + id)
        .then(response => response.text())
        .then(data => {
            document.getElementById("titulo-modal").innerHTML = "Editar Producto";
            document.getElementById("contenido-modal").innerHTML = data;
            document.getElementById("modal-admin").style.display = "block";
        })
        .catch(error => console.error('Error:', error));
}

function eliminarProducto(id) {
    if(confirm('¿Está seguro de eliminar este producto?')) {
        fetch('delete_productos.php?id=' + id)
            .then(response => response.text())
            .then(data => {
                alert(data);
                cargarContenido('admin_productos.php');
            })
            .catch(error => console.error('Error:', error));
    }
}
</script>