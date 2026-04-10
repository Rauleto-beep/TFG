import { ref } from 'vue';

export function useCantidadTareas() {
    const cantidad = ref(localStorage.getItem('ultima_cantidad_tareas') || null);

    const cantidadTareas = async () => {
        const user_id = { "user_id" : localStorage.getItem("user_id") };
        const token = localStorage.getItem("jwt_token");

        try {
            const respuesta = await fetch('https://localhost:8081/api/tarea/cantidad', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(user_id)
            });

            const datos = await respuesta.json();
            cantidad.value = datos; 
            localStorage.setItem('ultima_cantidad_tareas', datos); 

        } catch (error) {
            console.error("Error al obtener cantidad:", error);
        }
    };
    
    return { cantidad, cantidadTareas };
}