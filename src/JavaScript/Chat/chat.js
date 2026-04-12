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
        const url = new URL('http://127.0.0.1:3001/.well-known/mercure');
        url.searchParams.append('topic', `https://localhost:8081/chat/grupo/${grupoId}`);

        // Importante: withCredentials permite enviar la cookie de autorización
        eventSource = new EventSource(url);

        eventSource.onmessage = (event) => {
            const data = JSON.parse(event.data);
    
            // Simplemente metemos el mensaje tal cual llega (cifrado) al array.
            mensajes.value.push({
                id: data.id || Date.now(), // ID temporal si no viene del hub
                mensaje: data.mensaje || data.contenido, // Ajusta según tu backend
                autor: data.autor,
                grupo_id: data.grupo_id
            });
        };
    };

//
const enviarMensaje = async (grupoId, contenidoExterno = null) => {
    
    // Si hay contenidoExterno (tarea), usamos ese. Si no, usamos el input.
    const textoACrear = contenidoExterno || nuevoMensaje.value;

    if (!textoACrear || !textoACrear.trim()) return;
    
    const autorId = localStorage.getItem("user_id");

    // Si viene de fuera (tarea), ya viene cifrado. 
    // Si viene del input, hay que cifrarlo.
    const textoCifrado = contenidoExterno ? contenidoExterno : cifrar(textoACrear);

    const payload = {
        "contenido": textoCifrado,
        "fecha_envio": new Date().toISOString().slice(0, 19).replace('T', ' '),
        "grupo_id": grupoId,
        "autor_id": autorId
    };

    try {
        await fetch('https://localhost:8081/api/chat/enviar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(payload)
        });

        // Solo limpiamos el input si no es una tarea externa
        if (!contenidoExterno) {
            nuevoMensaje.value = ""; 
        }
    } catch (error) {
        console.error("Error al enviar mensaje:", error);
    }
};

    return { nuevoMensaje, mensajes, enviarMensaje, conectarMercure, eventSource };
}