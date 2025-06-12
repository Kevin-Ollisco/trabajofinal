<?php
session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario'])) {
    die("Debe iniciar sesión para agregar productos al carrito");
}

if(isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);
    $id_usuario = $_SESSION['id_usuario'];
    
    // Verificar si el producto ya está en el carrito
    $query = "SELECT * FROM carrito 
              WHERE id_usuario = ? AND id_producto = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        // Actualizar cantidad
        $query = "UPDATE carrito SET cantidad = cantidad + ? 
                  WHERE id_usuario = ? AND id_producto = ?";
    } else {
        // Insertar nuevo registro
        $query = "INSERT INTO carrito (id_usuario, id_producto, cantidad) 
                  VALUES (?, ?, ?)";
    }
    
    $stmt = mysqli_prepare($con, $query);
    if(mysqli_num_rows($result) > 0) {
        mysqli_stmt_bind_param($stmt, "iii", $cantidad, $id_usuario, $id_producto);
    } else {
        mysqli_stmt_bind_param($stmt, "iii", $id_usuario, $id_producto, $cantidad);
    }
    
    if(mysqli_stmt_execute($stmt)) {
        echo "Producto agregado al carrito";
    } else {
        echo "Error al agregar al carrito";
    }
} else {
    echo "Datos incompletos";
}
?>