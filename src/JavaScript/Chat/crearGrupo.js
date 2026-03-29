
import {ref} from 'vue';
// AJAX
export function creacionGrupo() {
    const crearGrupo = async () => {
        const datosGrupo = {
            "nombre_grupo": document.getElementById("inputNombreGrupo").value,
            "fecha_creacion": new Date().toISOString().slice(0, 19).replace('T', ' '),
            "usuario_id": localStorage.getItem("user_id")
        };

        const token = localStorage.getItem("jwt_token");
        try {
            //Usamos await para la respuesta
            const respuesta = await fetch('http://localhost:8081/api/grupo/crear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(datosGrupo)
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
    
    
    return { crearGrupo };
}
