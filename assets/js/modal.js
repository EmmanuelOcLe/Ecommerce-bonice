//js de las funcionalidades de los modales en gestionar productos 

const modalCrear = document.getElementById("modalCrear");
const abrirCrear = document.getElementById("abrirModal");
const cerrarCrear = document.getElementById("cerrarModal");

abrirCrear.addEventListener("click", () => {
    modalCrear.style.display = "block";
});

cerrarCrear.addEventListener("click", () => {
    modalCrear.style.display = "none";
});

window.addEventListener("click", (e) => {
    if (e.target === modalCrear) {
    modalCrear.style.display = "none";
    }
});

// Modal editar
document.querySelectorAll('.btn-editar').forEach(boton => {
    boton.addEventListener('click', () => {
    document.getElementById('id_editar').value = boton.dataset.id;
    document.getElementById('nombre_editar').value = boton.dataset.nombre;
    document.getElementById('precio_editar').value = boton.dataset.precio;
    document.getElementById('stock_editar').value = boton.dataset.stock;
    document.getElementById('descripcion_editar').value = boton.dataset.descripcion;
    document.getElementById('modalEditar').style.display = 'block';
    });
});

document.getElementById('cerrarEditar').addEventListener('click', () => {
    document.getElementById('modalEditar').style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === document.getElementById('modalEditar')) {
    document.getElementById('modalEditar').style.display = 'none';
    }
});


    // Modal Eliminar
const modalEliminar = document.getElementById("modalEliminar");
const cerrarEliminar = document.getElementById("cerrarEliminar");
const cancelarEliminar = document.getElementById("cancelarEliminar");
const mensajeEliminar = document.getElementById("mensajeEliminar");
const idEliminarInput = document.getElementById("id_eliminar");

document.querySelectorAll('.btn-eliminar').forEach(boton => {
boton.addEventListener('click', () => {
    const id = boton.dataset.id;
    const nombre = boton.dataset.nombre;

    mensajeEliminar.textContent = `¿Estás segura de eliminar el producto "${nombre}"?`;
    idEliminarInput.value = id;
    modalEliminar.style.display = "block";
});
});

cerrarEliminar.addEventListener("click", () => {
modalEliminar.style.display = "none";
});

cancelarEliminar.addEventListener("click", () => {
modalEliminar.style.display = "none";
});

window.addEventListener("click", (e) => {
if (e.target === modalEliminar) {
    modalEliminar.style.display = "none";
}
});




