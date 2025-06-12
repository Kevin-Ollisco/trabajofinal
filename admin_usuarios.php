<?php
require("verificarsesion.php");
require("verificarrol.php"); // Asegurar que solo administradores accedan
include("conexion.php");

$sql = "SELECT u.id_usuario as id, u.email as correo, r.nombre_rol as rol 
        FROM usuarios u 
        JOIN roles r ON u.id_rol = r.id_rol";
$resultado = $con->query($sql);
?>

<h3><i class="fas fa-users me-2"></i>Gestión de Usuarios</h3>
<hr>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Operaciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_array($resultado)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['correo']; ?></td>
            <td><?php echo $row['rol']; ?></td>
            <td>
                <a href="javascript:cargarContenido('formedit_usuarios.php?id=<?php echo $row['id']; ?>')" class="btn btn-editar">
                    <i class="fas fa-edit me-1"></i>Editar
                </a>
                <a href="javascript:void(0)" onclick="eliminarUsuario('<?php echo $row['id']; ?>', event)" class="btn btn-eliminar">
                   <i class="fas fa-trash me-1"></i>Eliminar
                </a>

                <?php if($row['rol'] != 'Administrador') { ?>
                <a href="javascript:volverAdmin('<?php echo $row['id']; ?>')" class="btn btn-ascender">
                    <i class="fas fa-user-shield me-1"></i>Hacer Admin
                </a>
                <?php } else { ?>
                <a href="javascript:volverUser('<?php echo $row['id']; ?>')" class="btn btn-descender">
                    <i class="fas fa-user me-1"></i>Hacer Cliente
                </a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<a href="javascript:cargarContenido('formcreate_usuarios.php')" class="btn btn-insertar">
    <i class="fas fa-plus-circle me-1"></i>Nuevo Usuario
</a>