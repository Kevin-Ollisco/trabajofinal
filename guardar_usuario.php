<?php
require("verificarsesion.php");
require("verificarrol.php");
include("conexion.php");

header('Content-Type: application/json');

// Validar acción
if(!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Acción no especificada']);
    exit();
}

$action = $_POST['action'];

try {
    if($action === 'create') {
        // Validar datos para nuevo usuario
        if(empty($_POST['email']) || empty($_POST['rol'])) {
            throw new Exception('Todos los campos son obligatorios');
        }
        
        // Verificar si el correo ya existe
        $email = $con->real_escape_string($_POST['email']);
        $check = $con->query("SELECT id_usuario FROM usuarios WHERE email = '$email'");
        
        if($check->num_rows > 0) {
            throw new Exception('El correo electrónico ya está registrado');
        }
        
        // Validar contraseña
        if(empty($_POST['password'])) {
            throw new Exception('La contraseña es obligatoria');
        }
        
        if($_POST['password'] !== $_POST['confirm_password']) {
            throw new Exception('Las contraseñas no coinciden');
        }
        
        // Hash de la contraseña
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $rol = (int)$_POST['rol'];
        
        // Insertar nuevo usuario
        $stmt = $con->prepare("INSERT INTO usuarios (email, password, id_rol) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $email, $passwordHash, $rol);
        
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
        } else {
            throw new Exception('Error al crear el usuario: ' . $stmt->error);
        }
        
    } elseif($action === 'update') {
        // Validar datos para actualización
        if(empty($_POST['id']) || empty($_POST['email']) || empty($_POST['rol'])) {
            throw new Exception('Datos incompletos');
        }
        
        $id = (int)$_POST['id'];
        $email = $con->real_escape_string($_POST['email']);
        $rol = (int)$_POST['rol'];
        
        // Verificar si el correo ya existe (excluyendo al usuario actual)
        $check = $con->query("SELECT id_usuario FROM usuarios WHERE email = '$email' AND id_usuario != $id");
        
        if($check->num_rows > 0) {
            throw new Exception('El correo electrónico ya está registrado por otro usuario');
        }
        
        // Actualizar usuario (con o sin contraseña)
        if(!empty($_POST['password'])) {
            if($_POST['password'] !== $_POST['confirm_password']) {
                throw new Exception('Las contraseñas no coinciden');
            }
            
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $con->prepare("UPDATE usuarios SET email = ?, password = ?, id_rol = ? WHERE id_usuario = ?");
            $stmt->bind_param("ssii", $email, $passwordHash, $rol, $id);
        } else {
            $stmt = $con->prepare("UPDATE usuarios SET email = ?, id_rol = ? WHERE id_usuario = ?");
            $stmt->bind_param("sii", $email, $rol, $id);
        }
        
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
        } else {
            throw new Exception('Error al actualizar el usuario: ' . $stmt->error);
        }
    } else {
        throw new Exception('Acción no válida');
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if(isset($stmt)) $stmt->close();
    $con->close();
}
?>