// AJAX
export function obtenerCategoriasNombre() {
    // 1. Añadimos 'async' aquí
    const infoCategoriaNombre = async () => {
        const nombre_categoria = {
            "nombre_categoria" : document.getElementById("inputCategoria").value
        };
        const token = localStorage.getItem("jwt_token");

        try {
            // 2. Usamos await para la respuesta
            const respuesta = await fetch('https://localhost:8081/api/categoria/ver/nombre', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(nombre_categoria)
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
    
    return { infoCategoriaNombre };
}