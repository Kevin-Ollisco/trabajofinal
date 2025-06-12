<?php
require("verificarsesion.php");
require("verificarrol.php");
include("conexion.php");

$sql = "SELECT f.id_factura, f.numero_factura, c.razon_social as cliente, 
               u.email as usuario, f.fecha_emision, f.importe_total, f.estado
        FROM facturas f
        JOIN clientes c ON f.id_cliente = c.id_cliente
        JOIN usuarios u ON f.id_usuario = u.id_usuario
        ORDER BY f.fecha_emision DESC";
$resultado = $con->query($sql);
?>

<h3><i class="fas fa-file-invoice-dollar me-2"></i>Gestión de Facturas</h3>
<hr>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>N° Factura</th>
            <th>Cliente</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Operaciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_array($resultado)) { ?>
        <tr>
            <td><?php echo $row['numero_factura']; ?></td>
            <td><?php echo $row['cliente']; ?></td>
            <td><?php echo $row['usuario']; ?></td>
            <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_emision'])); ?></td>
            <td>Bs. <?php echo number_format($row['importe_total'], 2); ?></td>
            <td><?php echo $row['estado']; ?></td>
            <td>
                <button onclick="verDetalleFactura('<?php echo $row['id_factura']; ?>')" class="btn btn-info">
                    <i class="fas fa-eye me-1"></i>Ver
                </button>
                <button onclick="anularFactura('<?php echo $row['id_factura']; ?>')" class="btn btn-danger">
                    <i class="fas fa-ban me-1"></i>Anular
                </button>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<script>
function verDetalleFactura(id) {
    fetch('detalle_factura_admin.php?id=' + id)
        .then(response => response.text())
        .then(data => {
            document.getElementById("titulo-modal").innerHTML = "Detalle de Factura";
            document.getElementById("contenido-modal").innerHTML = data;
            document.getElementById("modal-admin").style.display = "block";
        })
        .catch(error => console.error('Error:', error));
}

function anularFactura(id) {
    if(confirm('¿Está seguro de anular esta factura?')) {
        fetch('anular_factura.php?id=' + id)
            .then(response => response.text())
            .then(data => {
                alert(data);
                cargarContenido('admin_facturas.php');
            })
            .catch(error => console.error('Error:', error));
    }
}
</script>