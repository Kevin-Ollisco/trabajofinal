<?php 
$con = mysqli_connect("localhost","root","","supermercado_xander");
if(mysqli_connect_errno()) {
    throw new Exception("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($con, "utf8");
?>