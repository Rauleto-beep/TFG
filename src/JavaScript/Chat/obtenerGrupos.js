import { ref } from 'vue';

export function obtenerGruposUsuario() {
    const grupos = ref([]); 

    // Añadimos async para poder esperar la respuesta
    const infoGrupos = async () => {
        const user_id = { "user_id" : localStorage.getItem("user_id") };
        const token = localStorage.getItem("jwt_token");

        try {
            const respuesta = await fetch('https://localhost:8081/api/grupo/ver', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(user_id)
            });

            const datos = await respuesta.json();
            grupos.value = datos; // Vue detecta este cambio y actualiza el v-for
            
        } catch (error) {
            console.error("Error al obtener tareas:", error);
        }
    };
    
    return { grupos, infoGrupos };
}