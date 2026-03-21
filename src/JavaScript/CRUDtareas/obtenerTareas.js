import { ref } from 'vue';

export function obtenerTareasUsuario() {
    const tareas = ref([]); 

    // Añadimos async para poder esperar la respuesta
    const infoTareas = async () => {
        const user_id = { "user_id" : localStorage.getItem("user_id") };
        const token = localStorage.getItem("jwt_token");

        try {
            const respuesta = await fetch('http://localhost:8081/api/tarea/ver_todas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(user_id)
            });

            const datos = await respuesta.json();
            tareas.value = datos; // Vue detecta este cambio y actualiza el v-for
            
        } catch (error) {
            console.error("Error al obtener tareas:", error);
        }
    };
    
    return { tareas, infoTareas };
}