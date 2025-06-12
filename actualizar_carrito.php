<?php
session_start();
include("conexion.php");

$response = ['success' => false];

if(isset($_SESSION['id_usuario']) && isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);
    $id_usuario = $_SESSION['id_usuario'];
    
    // Verificar si el producto existe en el carrito
    $query = "UPDATE carrito SET cantidad = ? 
              WHERE id_usuario = ? AND id_producto = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "iii", $cantidad, $id_usuario, $id_producto);
    
    if(mysqli_stmt_execute($stmt)) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>