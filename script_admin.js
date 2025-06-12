// script_admin.js

// Función para cargar contenido dinámico
function cargarContenido(url) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar el contenido');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById("contenido").innerHTML = data;
            // Resaltar el elemento activo en el menú
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(url)) {
                    link.classList.add('active');
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("contenido").innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar el contenido: ${error.message}
                </div>
            `;
        });
}

// Funciones para usuarios
function editarUsuario(id) {
    cargarContenido('formedit_usuarios.php?id=' + id);
}


// Función para mostrar alertas
function showAlert(type, message) {
    // Crear elemento de alerta
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insertar al inicio del contenido
    const content = document.getElementById('contenido');
    if(content) {
        content.insertBefore(alertDiv, content.firstChild);
        
        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    } else {
        console.error('No se encontró el elemento con ID "contenido"');
    }
}

// Función modificada para eliminar usuarios
function eliminarUsuario(id, event = null) {
    // Obtener el botón que disparó el evento
    const btn = event ? event.currentTarget : null;
    
    if (!confirm('¿Está seguro de eliminar este usuario permanentemente?')) {
        return;
    }

    // Mostrar feedback visual
    if(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
        btn.disabled = true;
    }

    fetch(`delete_usuarios.php?id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                // Recargar después de 1.5 segundos
                setTimeout(() => cargarContenido('admin_usuarios.php'), 1500);
            } else {
                throw new Error(data.message || 'Error al eliminar usuario');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', error.message);
        })
        .finally(() => {
            if(btn) {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        });
}


function volverAdmin(id) {
    if(confirm('¿Convertir este usuario en Administrador?')) {
        fetch('cambiar_rol.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id + '&rol=1' // 1 es el ID de Administrador en tu DB
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                cargarContenido('admin_usuarios.php');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cambiar el rol');
        });
    }
}

function volverUser(id) {
    if(confirm('¿Convertir este usuario en Cliente?')) {
        fetch('cambiar_rol.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id + '&rol=2' // 2 es el ID de Cliente en tu DB
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                cargarContenido('admin_usuarios.php');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cambiar el rol');
        });
    }
}
// Manejo de los modales
var modal = document.getElementById("modal-admin");
var closeBtn = document.getElementsByClassName("cerrar")[0];

// Funciones para mostrar/ocultar modal
function mostrarModal() {
    modal.style.display = "block";
}

closeBtn.onclick = function() {
    modal.style.display = "none";
};

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

// Cargar funciones al iniciar
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clics en el menú
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});