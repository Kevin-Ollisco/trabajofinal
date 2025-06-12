<?php
session_start();
include("conexion.php");

$count = 0;

if(isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    
    $query = "SELECT SUM(cantidad) as total FROM carrito WHERE id_usuario = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        $count = $row['total'] ? $row['total'] : 0;
    }
}

echo $count;
?>