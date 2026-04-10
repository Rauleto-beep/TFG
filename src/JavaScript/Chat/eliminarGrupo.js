
import {ref} from 'vue';
// AJAX
export function eliminacionGrupo() {
    const eliminarGrupo = async (id) => {
        const idGrupo = {
            "id": id,
        };

        const token = localStorage.getItem("jwt_token");
        try {
            //Usamos await para la respuesta
            const respuesta = await fetch('https://localhost:8081/api/grupo/eliminar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(idGrupo)
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
    
    
    return { eliminarGrupo };
}
