<?php
require("verificarsesion.php");
require("verificarrol.php");
?>

<h3><i class="fas fa-chart-bar me-2"></i>Reportes</h3>
<hr>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chart-line me-2"></i>Ventas por Mes</h5>
                <div class="text-center py-4">
                    <img src="img/grafico_ventas.png" alt="Ventas por Mes" class="img-fluid">
                </div>
                <button onclick="generarReporteVentas()" class="btn btn-primary w-100">
                    <i class="fas fa-download me-1"></i>Descargar Reporte
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-boxes me-2"></i>Productos más Vendidos</h5>
                <div class="text-center py-4">
                    <img src="img/grafico_productos.png" alt="Productos más Vendidos" class="img-fluid">
                </div>
                <button onclick="generarReporteProductos()" class="btn btn-primary w-100">
                    <i class="fas fa-download me-1"></i>Descargar Reporte
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="fas fa-cog me-2"></i>Reporte Personalizado</h5>
        <form id="form-reporte">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="fecha-inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha-inicio" required>
                </div>
                <div class="col-md-4">
                    <label for="fecha-fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha-fin" required>
                </div>
                <div class="col-md-4">
                    <label for="tipo-reporte" class="form-label">Tipo de Reporte</label>
                    <select class="form-select" id="tipo-reporte" required>
                        <option value="ventas">Ventas</option>
                        <option value="productos">Productos</option>
                        <option value="clientes">Clientes</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-file-excel me-1"></i>Generar Reporte
            </button>
        </form>
    </div>
</div>

<script>
function generarReporteVentas() {
    alert("Generando reporte de ventas...");
    // Aquí iría la lógica para generar el reporte
}

function generarReporteProductos() {
    alert("Generando reporte de productos...");
    // Aquí iría la lógica para generar el reporte
}

document.getElementById('form-reporte').addEventListener('submit', function(e) {
    e.preventDefault();
    const fechaInicio = document.getElementById('fecha-inicio').value;
    const fechaFin = document.getElementById('fecha-fin').value;
    const tipoReporte = document.getElementById('tipo-reporte').value;
    
    alert(`Generando reporte ${tipoReporte} desde ${fechaInicio} hasta ${fechaFin}`);
    // Aquí iría la lógica para generar el reporte personalizado
});
</script>