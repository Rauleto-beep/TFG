// AJAX
export function eliminarTareasUsuario() {
    const eliminarTarea = async (tarea) => {
        const id_tarea = {
            "id_tarea" : tarea
        };
        const token = localStorage.getItem("jwt_token");

        try {
            //Usamos await para la respuesta
            const respuesta = await fetch('https://localhost:8081/api/tarea/eliminar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(id_tarea)
            });

            //Convertimos a JSON y lo guardamos en una variable
            const datos = await respuesta.json();

            //DEVOLVEMOS los datos hacia afuera de la función
            return datos; 

        } catch (error) {
            console.error("Error en la petición:", error);
            return null; // Devolvemos null para que el otro archivo sepa que falló
        }
    };
    
    return { eliminarTarea };
}