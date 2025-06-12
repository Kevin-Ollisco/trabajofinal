<?php
require("verificarsesion.php");
require("verificarrol.php");
include("conexion.php");

header('Content-Type: application/json');

// Validar que el ID sea numérico
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario inválido']);
    exit();
}

$id = (int)$_GET['id'];

try {
    // Verificar que no sea el último administrador
    $checkAdmin = $con->query("SELECT COUNT(*) as admins FROM usuarios WHERE id_rol = 1");
    $row = $checkAdmin->fetch_assoc();

    // Verificar si el usuario a eliminar es administrador
    $checkUser = $con->query("SELECT id_rol FROM usuarios WHERE id_usuario = $id");
    $user = $checkUser->fetch_assoc();
    
    if($user['id_rol'] == 1 && $row['admins'] <= 1) {
        echo json_encode(['success' => false, 'message' => 'No puedes eliminar al último administrador']);
        exit();
    }

    // Preparar y ejecutar la consulta de eliminación
    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
    $stmt = $con->prepare($sql);
    
    if(!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $con->error);
    }
    
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        if($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el usuario o ya fue eliminado']);
        }
    } else {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
    $stmt->close();
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>