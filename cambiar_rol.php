<?php
require("verificarsesion.php");
require("verificarrol.php");
include("conexion.php");

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $rol = $_POST['rol'];
    
    $sql = "UPDATE usuarios SET id_rol = ? WHERE id_usuario = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $rol, $id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Rol actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el rol']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>