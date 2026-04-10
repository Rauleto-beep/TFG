
// AJAX
export function actualizarContraseña() {
    const actualizarContraseñaUsuario = async (id,passw) => {
        const idUsuario = {
            "id": id,
            "password": passw,
        };

        const token = localStorage.getItem("jwt_token");
        try {
            //Usamos await para la respuesta
            const respuesta = await fetch('http://localhost:8081/api/usuario/actualizar_passw', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(idUsuario)
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
    
    
    return { actualizarContraseñaUsuario };
}
