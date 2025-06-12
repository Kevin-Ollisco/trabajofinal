<?php 
session_start();
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];
    
    // Validación básica
    if (empty($correo) || empty($password)) {
        header("Location: login.html?error=Por favor complete todos los campos");
        exit();
    }
    
    // Consulta preparada para seguridad
    $stmt = $con->prepare('SELECT id_usuario, email, password, nombre_rol 
                          FROM usuarios 
                          JOIN roles ON usuarios.id_rol = roles.id_rol 
                          WHERE email = ?');
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        
        // Verificar contraseña con SHA-256 (consistente con la base de datos)
        if (hash('sha256', $password) === $usuario['password']) {
            // Autenticación exitosa
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['rol'] = $usuario['nombre_rol'];
            
            // Recordar usuario si marcó la opción
            if (isset($_POST['remember'])) {
                setcookie('remember_email', $usuario['email'], time() + 86400 * 30, "/");
            }
            
            // Redirección según rol
            if ($_SESSION['rol'] === 'Administrador') {
                header("Location: admin/pagina_admin.php");
            } elseif ($_SESSION['rol'] === 'Cajero') {
                header("Location: pagina_cajero.php");
            } else {
                header("Location: pagina_usuario.php"); // Clientes van aquí
            }
            exit();
        }
    }
    
    // Autenticación fallida
    header("Location: login.html?error=Correo o contraseña incorrectos");
    exit();
} else {
    header("Location: login.html");
    exit();
}
?>
