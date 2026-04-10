import { ref } from 'vue';
import { cifrar, descifrar } from './cryptoService.js';

export function enviarMensajesGrupos() {
    const nuevoMensaje = ref("");
    const mensajes = ref([]);
    const token = localStorage.getItem("jwt_token");
    let eventSource = null;

    const conectarMercure = async(grupoId) => {
        if (eventSource) eventSource.close();

        // 1. CARGAR HISTORIAL (Descifrando cada mensaje que viene de la BBDD)
        try {
            // Cambiado a HTTPS para cumplir con la Fase 1
            const res = await fetch(`https://localhost:8081/api/chat/ver_historial/${grupoId}`, {
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const historial = await res.json();
            
            // Mapeamos el historial para descifrar el contenido antes de mostrarlo
            mensajes.value = historial.map(m => ({
                ...m,
                mensaje: descifrar(m.mensaje) // Desciframos lo que viene de MySQL
            }));
        } catch (e) {
            console.error("Error cargando historial", e);
        }

        // 2. CONFIGURAR MERCURE (Fase 2: HTTPS y Credenciales)
        const url = new URL('https://localhost:3000/.well-known/mercure');
        url.searchParams.append('topic', `https://localhost:8081/chat/grupo/${grupoId}`);

        // Importante: withCredentials permite enviar la cookie de autorización
        eventSource = new EventSource(url, {
            withCredentials: true 
        });

        eventSource.onmessage = (event) => {
            const data = JSON.parse(event.data);
            
            // Fase 3: Descifrar el mensaje en tiempo real cuando llega del Hub
            const textoLimpio = descifrar(data.mensaje);

            mensajes.value.push({
                mensaje: textoLimpio, 
                autor: data.autor,
                grupo_id: data.grupo_id
            });
        };
    };

    const enviarMensaje = async (grupoId) => {
        if (!nuevoMensaje.value.trim()) return;
        
        const autorId = localStorage.getItem("user_id");

        // Fase 3: Cifrar el texto antes de enviarlo al servidor
        // Así el servidor solo recibe y guarda el código ilegible
        const textoCifrado = cifrar(nuevoMensaje.value);

        const payload = {
            "contenido": textoCifrado, // <--- Enviamos el mensaje blindado
            "fecha_envio": new Date().toISOString().slice(0, 19).replace('T', ' '),
            "grupo_id": grupoId,
            "autor_id": autorId
        };

        try {
            // Cambiado a HTTPS
            await fetch('https://localhost:8081/api/chat/enviar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(payload)
            });
            nuevoMensaje.value = ""; 
        } catch (error) {
            console.error("Error al enviar mensaje:", error);
        }
    };

    return { nuevoMensaje, mensajes, enviarMensaje, conectarMercure, eventSource };
}