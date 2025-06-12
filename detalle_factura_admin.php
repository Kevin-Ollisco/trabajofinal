<?php
require("verificarsesion.php");
require("verificarrol.php");
include("conexion.php");

$id_factura = $_GET['id'];

// Obtener información de la factura
$sql_factura = "SELECT f.*, c.razon_social as cliente, c.nit, u.email as usuario
                FROM facturas f
                JOIN clientes c ON f.id_cliente = c.id_cliente
                JOIN usuarios u ON f.id_usuario = u.id_usuario
                WHERE f.id_factura = $id_factura";
$result_factura = $con->query($sql_factura);
$factura = $result_factura->fetch_assoc();

// Obtener detalle de la factura
$sql_detalle = "SELECT d.*, p.nombre_producto, p.codigo_producto
                 FROM detalle_factura d
                 JOIN productos p ON d.id_producto = p.id_producto
                 WHERE d.id_factura = $id_factura";
$result_detalle = $con->query($sql_detalle);
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4>Factura: <?php echo $factura['numero_factura']; ?></h4>
            <p><strong>Cliente:</strong> <?php echo $factura['cliente']; ?></p>
            <p><strong>NIT:</strong> <?php echo $factura['nit']; ?></p>
        </div>
        <div class="col-md-6 text-end">
            <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($factura['fecha_emision'])); ?></p>
            <p><strong>Usuario:</strong> <?php echo $factura['usuario']; ?></p>
            <p><strong>Estado:</strong> <?php echo $factura['estado']; ?></p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result_detalle->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['codigo_producto']; ?></td>
                <td><?php echo $row['nombre_producto']; ?></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td>Bs. <?php echo number_format($row['precio_unitario'], 2); ?></td>
                <td>Bs. <?php echo number_format($row['subtotal'], 2); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Subtotal:</th>
                <td>Bs. <?php echo number_format($factura['subtotal'], 2); ?></td>
            </tr>
            <tr>
                <th colspan="4" class="text-end">Descuento:</th>
                <td>Bs. <?php echo number_format($factura['descuento'], 2); ?></td>
            </tr>
            <tr>
                <th colspan="4" class="text-end">Total:</th>
                <td class="fw-bold">Bs. <?php echo number_format($factura['importe_total'], 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="alert alert-info">
        <strong>Leyenda:</strong> <?php echo $factura['leyenda']; ?>
    </div>

    <div class="text-center mt-3">
        <button onclick="imprimirFactura('<?php echo $id_factura; ?>')" class="btn btn-primary me-2">
            <i class="fas fa-print me-1"></i>Imprimir
        </button>
        <button onclick="document.getElementById('modal-admin').style.display='none'" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>Cerrar
        </button>
    </div>
</div>

<script>
function imprimirFactura(id) {
    window.open('imprimir_factura.php?id=' + id, '_blank');
}
</script>