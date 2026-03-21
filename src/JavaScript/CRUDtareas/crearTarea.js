import { obtenerCategoriasNombre } from './obtenerCategoriasNombre';

export function crearTareasUsuario() {
    
    // La función principal debe ser ASYNC
    const crearTarea = async () => {
    const token = localStorage.getItem("jwt_token");

    // Capturamos los elementos
    const nombreTarea = document.getElementById("inputNombreTarea");
    const descripcionTarea = document.getElementById("inputDescripcionTarea");
    const fechaVencimientoTarea = document.getElementById("inputDate");
    const prioridadTarea = document.getElementById("inputPrioridad");
    const categoriaInput = document.getElementById("inputCategoria");

    try {

        const categoriaId = categoriaInput.value === "" ? null : parseInt(categoriaInput.value);
        const datosTarea = {
            "nombre_tarea": nombreTarea.value,
            "descripcion": descripcionTarea.value,
            "fecha_publicacion": new Date().toISOString().slice(0, 19).replace('T', ' '),
            "fecha_vencimiento": fechaVencimientoTarea.value,
            "ia": false,
            "estado": "No completada",
            "prioridad": prioridadTarea.value,
            "categoria_id": categoriaId,
            "grupo_id": null,
            "usuario_id": localStorage.getItem("user_id")
        };
        // Enviamos la tarea
        const respuesta = await fetch('http://localhost:8081/api/tarea/crear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(datosTarea)
        });

        const datos = await respuesta.json();
    } catch (error) {
        alert("Error: " + error.message);
    }
};

    return { crearTarea };
}