// Manejo de los modales
var modal = document.getElementById("modal-producto");
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

// Función para ver detalles del producto
function verDetalleProducto(idProducto) {
    fetch('detalle_producto.php?id=' + idProducto)
        .then(response => response.text())
        .then(data => {
            document.getElementById("titulo-modal").innerHTML = "Detalles del Producto";
            document.getElementById("contenido-modal").innerHTML = data;
            mostrarModal();
        })
        .catch(error => console.error('Error:', error));
}

// Función para agregar al carrito
function agregarAlCarrito(idProducto) {
    const cantidad = document.getElementById('cantidad-' + idProducto)?.value || 1;
    
    fetch('agregar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_producto=' + idProducto + '&cantidad=' + cantidad
    })
    .then(response => response.text())
    .then(data => {
        mostrarNotificacion(data, 'success');
        actualizarContadorCarrito();
    })
    .catch(error => {
        mostrarNotificacion('Error al agregar al carrito', 'danger');
        console.error('Error:', error);
    });
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    const notificacion = document.createElement('div');
    notificacion.className = `alert alert-${tipo} position-fixed top-0 end-0 m-3`;
    notificacion.style.zIndex = '1000';
    notificacion.innerHTML = `<i class="fas fa-${tipo === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>${mensaje}`;
    document.body.appendChild(notificacion);
    
    setTimeout(() => notificacion.remove(), 3000);
}

// Mostrar productos por categoría
function mostrarProductos(idCategoria) {
    // Mostrar loader
    document.getElementById("lista-productos").innerHTML = `
        <div class="col-12 text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando productos...</p>
        </div>
    `;
    
    fetch('productos_categoria.php?id=' + idCategoria)
        .then(response => response.text())
        .then(data => {
            document.getElementById("lista-productos").innerHTML = data;
            // Resaltar la categoría seleccionada
            document.querySelectorAll('.categoria-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('onclick').includes(idCategoria)) {
                    btn.classList.add('active');
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("lista-productos").innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar los productos. Por favor intenta nuevamente.
                </div>
            `;
        });
}

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

// Actualizar contador de items en el carrito
function actualizarContadorCarrito() {
    fetch('contador_carrito.php')
        .then(response => response.text())
        .then(data => {
            const contador = document.getElementById('contador-carrito');
            if(contador) {
                contador.textContent = data;
            }
        });
}

// Cargar contador al iniciar
document.addEventListener('DOMContentLoaded', function() {
    actualizarContadorCarrito();
    
    // Manejar clics en el menú
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
// script_usuario.js

// ... (código existente)

// Funciones para el carrito (mover desde carrito.php a este archivo)
function actualizarCantidad(id, cambio) {
    const input = document.getElementById('cantidad-' + id);
    let nuevaCantidad = parseInt(input.value) + cambio;
    
    if(nuevaCantidad < 1) nuevaCantidad = 1;
    
    input.value = nuevaCantidad;
    actualizarItemCarrito(id, nuevaCantidad);
}

function actualizarCantidadInput(id) {
    const input = document.getElementById('cantidad-' + id);
    let nuevaCantidad = parseInt(input.value);
    
    if(isNaN(nuevaCantidad)) nuevaCantidad = 1;
    if(nuevaCantidad < 1) nuevaCantidad = 1;
    
    input.value = nuevaCantidad;
    actualizarItemCarrito(id, nuevaCantidad);
}

function actualizarItemCarrito(id, cantidad) {
    fetch('actualizar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_producto=' + id + '&cantidad=' + cantidad
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const precio = parseFloat(document.querySelector(`#producto-${id} .precio`).textContent.replace('Bs. ', ''));
            const subtotal = precio * cantidad;
            document.querySelector(`#producto-${id} .subtotal`).textContent = 'Bs. ' + subtotal.toFixed(2);
            
            actualizarTotal();
            actualizarContadorCarrito();
            mostrarNotificacion('Carrito actualizado', 'success');
        } else {
            mostrarNotificacion('Error al actualizar el carrito', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al actualizar el carrito', 'danger');
    });
}

// Al inicio del archivo, declara las funciones como propiedades de window
window.eliminarDelCarrito = function(id) {
    if(confirm('¿Estás seguro de eliminar este producto de tu carrito?')) {
        fetch('eliminar_carrito.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id_producto=' + id
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('producto-' + id).remove();
                actualizarTotal();
                actualizarContadorCarrito();
                mostrarNotificacion('Producto eliminado', 'success');
                
                if(document.querySelectorAll('tbody tr').length === 0) {
                    cargarContenido('carrito.php');
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
};

window.vaciarCarrito = function() {
    if(confirm('¿Estás seguro de vaciar completamente tu carrito?')) {
        fetch('vaciar_carrito.php')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                mostrarNotificacion('Carrito vaciado', 'success');
                cargarContenido('carrito.php');
                actualizarContadorCarrito();
            }
        })
        .catch(error => console.error('Error:', error));
    }
};


window.actualizarTotal = function() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(el => {
        total += parseFloat(el.textContent.replace('Bs. ', ''));
    });
    document.querySelector('tfoot .text-primary').textContent = 'Bs. ' + total.toFixed(2);
};

function procederPago() {
    if(document.querySelectorAll('tbody tr').length === 0) {
        mostrarNotificacion('No hay productos en el carrito', 'warning');
        return;
    }
    cargarContenido('pago.php');
}

function actualizarTotal() {
    let nuevoTotal = 0;
    
    document.querySelectorAll('tbody tr').forEach(row => {
        const subtotalText = row.querySelector('.subtotal').textContent.replace('Bs. ', '');
        nuevoTotal += parseFloat(subtotalText);
    });
    
    document.querySelector('tfoot .text-primary').textContent = 'Bs. ' + nuevoTotal.toFixed(2);
}
// Agrega esto al final del archivo
document.addEventListener('click', function(e) {
    // Para eliminar productos
    if(e.target.closest('.btn-eliminar')) {
        const id = e.target.closest('.btn-eliminar').dataset.id;
        window.eliminarDelCarrito(id);
    }
    
    // Para vaciar carrito
    if(e.target.closest('.btn-vaciar-carrito')) {
        window.vaciarCarrito();
    }
});