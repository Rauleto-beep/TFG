import { ref } from 'vue';

export function enviarMensajesGrupos() {
    const nuevoMensaje = ref("");
    const mensajes = ref([]);
    const token = localStorage.getItem("jwt_token");
    let eventSource = null;

    const conectarMercure = async(grupoId) => {
        if (eventSource) eventSource.close();

        try {
            const res = await fetch(`http://localhost:8081/api/chat/ver_historial/${grupoId}`, {
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const historial = await res.json();
            mensajes.value = historial; // Reemplazamos los mensajes con los de la BBDD
        } catch (e) {
            console.error("Error cargando historial", e);
        }

        const url = new URL('http://localhost:3000/.well-known/mercure');
        // El tópico debería ser único por grupo para no mezclar chats
        url.searchParams.append('topic', `http://localhost:8081/chat/grupo/${grupoId}`);

        eventSource = new EventSource(url);

        eventSource.onmessage = (event) => {
            console.log("¡Llegó algo de Mercure!", event.data); // Mira esto en la consola (F12)
            const data = JSON.parse(event.data);
    
            // Aseguramos que el objeto tenga las claves que tu HTML espera
            mensajes.value.push({
                mensaje: data.mensaje, 
                autor: data.autor,
                grupo_id: data.grupo_id
            });
        };
    };

    const enviarMensaje = async (grupoId) => {
        if (!nuevoMensaje.value.trim()) return;

        
        const autorId = localStorage.getItem("user_id");

        const payload = {
            "contenido": nuevoMensaje.value,
            "fecha_envio": new Date().toISOString().slice(0, 19).replace('T', ' '),
            "grupo_id": grupoId,
            "autor_id": autorId
        };

        try {
            await fetch('http://localhost:8081/api/chat/enviar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(payload)
            });
            nuevoMensaje.value = ""; // Limpiar input
        } catch (error) {
            console.error("Error al enviar mensaje:", error);
        }
    };

    return { nuevoMensaje, mensajes, enviarMensaje, conectarMercure, eventSource };
}