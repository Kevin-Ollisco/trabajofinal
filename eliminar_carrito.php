<?php
session_start();
include("conexion.php");

$response = ['success' => false];

if(isset($_SESSION['id_usuario']) && isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);
    $id_usuario = $_SESSION['id_usuario'];
    
    $query = "DELETE FROM carrito 
              WHERE id_usuario = ? AND id_producto = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
    
    if(mysqli_stmt_execute($stmt)) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>