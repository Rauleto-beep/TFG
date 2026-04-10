
import {ref} from 'vue';
// AJAX
export function confirmarEdicionTareasUsuario() {
    let editar = ref(false);
    const editarTareaConfirmada = async () => {
        const datosTarea = {
            "id_tarea" : document.getElementById("idTarea").value,
            "nombre": document.getElementById("inputNombreTarea").value,
            "descripcion": document.getElementById("inputDescripcionTarea").value,
            "fechaVencimiento": document.getElementById("inputDate").value,
            "prioridad": document.getElementById("inputPrioridad").value,
            "categoria_id": parseInt(document.getElementById("inputCategoria").value)
        };

        const token = localStorage.getItem("jwt_token");
        try {
            //Usamos await para la respuesta
            const respuesta = await fetch('https://localhost:8081/api/tarea/actualizar_tarea', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(datosTarea)
            });

            // 3. Convertimos a JSON y lo guardamos en una variable
            const datos = await respuesta.json();

            // 4. DEVOLVEMOS los datos hacia afuera de la función
            return datos; 

        } catch (error) {
            console.error("Error en la petición:", error);
            return null; // Devolvemos null para que el otro archivo sepa que falló
        }
    };
    
    
    return { editarTareaConfirmada };
}
