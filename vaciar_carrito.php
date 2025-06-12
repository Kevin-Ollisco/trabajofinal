<?php
session_start();
include("conexion.php");

$response = ['success' => false];

if(isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    
    $query = "DELETE FROM carrito WHERE id_usuario = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    
    if(mysqli_stmt_execute($stmt)) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>