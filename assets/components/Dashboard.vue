<template>
  <div class="flex h-screen bg-[#0f0a15] text-gray-300 font-sans overflow-hidden">
    <main class="flex-1 overflow-y-auto p-8 space-y-8">
      <header class="flex justify-between items-start">
        <div>
          <h2 class="text-3xl font-bold text-white mb-2">Vista del calendario</h2>
        </div>
        <router-link to="/tareas" active-class="is-active">
          <button class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-purple-900/20">
            <span class="text-xl">+</span> Nueva tarea
          </button>
        </router-link>
      </header>

      <section class="bg-gray-900/30 border border-gray-800 p-6 rounded-3xl">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-white">{{ tituloActual }}</h3>
          <div class="flex bg-black/40 rounded-lg p-1">
            <button @click="cambiarVista('dayGridMonth')" class="px-4 py-1 text-xs text-gray-400" :class="vistaActiva === 'dayGridMonth' ? 'bg-purple-600 text-white shadow-lg rounded-md' : 'text-gray-400 hover:text-white'">Mes</button>
            <button @click="cambiarVista('timeGridWeek')" class="px-4 py-1 text-xs text-gray-400" :class="vistaActiva === 'timeGridWeek' ? 'bg-purple-600 text-white shadow-lg rounded-md' : 'text-gray-400 hover:text-white'">Semana</button>
            <button @click="cambiarVista('timeGridDay')" class="px-4 py-1 text-xs text-gray-400" :class="vistaActiva === 'timeGridDay' ? 'bg-purple-600 text-white shadow-lg rounded-md' : 'text-gray-400 hover:text-white'">Dia</button>
          </div>
        </div>
        <!-- CALENDARIO  -->
        <FullCalendar class="grid gap-2" ref="fullCalendar" :options="calendarOptions" />
      </section>

      <section class="bg-gray-900/30 border border-gray-800 p-8 rounded-3xl relative overflow-hidden">
  <div class="flex gap-4 items-start">
    <div class="bg-purple-900/30 p-3 rounded-2xl text-purple-400">
      <span v-if="cargandoAnalisis" class="animate-spin block">⚙️</span>
      <span v-else>🤖</span>
    </div>
    <div class="space-y-4">
      <div class="flex items-center gap-3">
        <h3 class="text-xl font-bold text-white">Mensaje del agente inteligente</h3>
        <span v-if="cargandoAnalisis" class="text-[10px] text-purple-400 animate-pulse uppercase tracking-widest">Calculando estrategia...</span>
      </div>
      
      <p class="text-sm leading-relaxed max-w-2xl text-gray-400 whitespace-pre-line">
        {{ mensajeAgenteEstratega }}
      </p>
      
      <div v-if="!cargandoAnalisis" class="flex gap-3">
        <button 
          @click="ejecutarAnalisisEstrategico" 
          class="text-[10px] border border-purple-500/30 text-purple-400 px-3 py-1 rounded-full hover:bg-purple-500/10 transition-colors">
          Actualizar análisis
        </button>
      </div>
    </div>
  </div>
</section>
    </main>

    <!-- CHAT IA -->
    <aside class="w-80 border-l border-gray-800 p-6 flex flex-col space-y-6 bg-gray-900/20">
      <div class="flex justify-between items-center">
        <h3 class="font-bold text-white">Asistente inteligente</h3>
        <span v-if="cargandoIA" class="text-[10px] text-purple-400 animate-pulse">Escribiendo...</span>
      </div>

      <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scroll">
        <div v-for="(msg, index) in mensajesChat" :key="index" 
           :class="['p-4 rounded-2xl text-xs leading-relaxed', 
                    msg.role === 'user' ? 'bg-purple-600/20 border border-purple-500/30 ml-4 text-gray-200' : 'bg-gray-800/40 border border-gray-700/50 mr-4 text-gray-400']">
          <p>{{ msg.text }}</p>
        </div>
      </div>

      <div class="mt-auto bg-gray-900/50 border border-gray-800 p-4 rounded-2xl">
        <form @submit.prevent="manejarEnvioChat" class="relative">
          <input 
            v-model="preguntaUsuario"
            type="text" 
            placeholder="Dime: 'Anota reunión mañana'..." 
            class="w-full bg-black/40 border border-gray-800 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-purple-500 text-white"
            :disabled="cargandoIA">
          <button 
            type="submit"
            class="absolute right-3 top-2.5 text-purple-500 text-lg hover:scale-110 transition-transform"
            :class="{ 'opacity-50': cargandoIA }">➤</button>
        </form>
      </div>
    </aside>
  </div>
</template>
<style>
  .fc { 
    --fc-border-color: #1f2937; 
    --fc-page-bg-color: transparent;
  }
  .fc-daygrid-event {
    background: transparent !important;
    border: none !important;
    margin-top: 4px !important;
    padding: 0 !important;
  }
  .fc-daygrid-block-event {
    background-color: rgba(15, 10, 21, 0.9) !important;
    border-top: none !important;
    border-right: none !important;
    border-bottom: none !important;    
    border-radius: 4px !important;
    padding: 4px 8px !important;
    display: flex !important;
    align-items: center;

    /* ESTO EVITA QUE SE SALGA EL TEXTO DE LA CASILLA DEL CALENDARIO */
    overflow: hidden !important; 
    max-width: 100%;
  }
  .fc-event-title {
    color: white !important;
    font-weight: 600 !important;
    font-size: 0.75rem !important;

    /* ESTO CREA LOS TRES PUNTOS (...) */
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    width: 100%;
  }
  .fc-daygrid-event-dot {
    display: none !important;
  }

  .fc-event {
    cursor: pointer !important;
  }

  .fc-daygrid-block-event:hover {
    filter: brightness(1.2);
    transition: filter 0.2s;
  }
.custom-scroll::-webkit-scrollbar {
  width: 4px;
}
.custom-scroll::-webkit-scrollbar-thumb {
  background: #374151;
  border-radius: 10px;
}

  /* ========================================================================= 
       MAPEADO DE CLASES ESPECÍFICAS SEGÚN LISTADO DE COLORES DE LAS CATEGORIAS               
   ========================================================================= */

  /*Categoría Personal (Naranja) */
  .color-naranja {
    border-left: 4px solid #f97316 !important;
  }

  /*Categoría Trabajo (Azul) */
  .color-azul {
    border-left: 4px solid #3b82f6 !important;
  }

  /*Categoría Urgente (Rojo) */
  .color-rojo {
    border-left: 4px solid #ef4444 !important;
  }

  /*Categoría Ocio (Verde) */
  .color-verde {
    border-left: 4px solid #22c55e !important;
  }

  /* Categoría Aprendizaje (Rosa) */
  .color-rosa {
    border-left: 4px solid #ec4899 !important;
  }

  /*Categoría Eventos (Amarillo) */
  .color-amarillo {
    border-left: 4px solid #eab308 !important;
  }

  /* ====================================================
              CSS TAREAS COMPLETADAS
     ===================================================*/

  .tarea-completada {
    background-color: rgba(134, 239, 172, 0.2) !important; 
    border-left: 4px solid #4ade80 !important; 
    box-shadow: 0 0 10px rgba(74, 222, 128, 0.1);
  }

  .tarea-completada .fc-event-title {
    color: #bbf7d0 !important;
    text-decoration: line-through rgba(187, 247, 208, 0.5);
  }

  .tarea-completada:hover {
    background-color: rgba(134, 239, 172, 0.3) !important;
    filter: none !important;
  }

  /* ====================================================
              CSS TAREAS NO COMPLETADAS
     ===================================================*/

  .tarea-no-completada {
    background-color: rgba(239, 68, 68, 0.15) !important; 
    border-left: 4px solid #ef4444 !important; 
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.1);
  }

  .tarea-no-completada .fc-event-title {
    color: #fca5a5 !important;
    font-weight: 700 !important;
  }

  .tarea-no-completada:hover {
    background-color: rgba(239, 68, 68, 0.25) !important;
    transition: background-color 0.2s ease;
  }

</style>
<script setup>
import {ref, onMounted } from 'vue';
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import { obtenerTareasUsuario } from '../../src/JavaScript/CRUDtareas/obtenerTareas.js';


const { tareas,infoTareas } = obtenerTareasUsuario();
const tituloActual = ref('');
const fullCalendar = ref(null);
const vistaActiva = ref('dayGridMonth');
const eventosCalendario = ref([]);
//Instrucciones del agente
const mensajeAgenteEstratega = ref('Analizando tus tareas para optimizar tu flujo...');
const cargandoAnalisis = ref(true);

 const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin],
  initialView: 'dayGridMonth',
  headerToolbar: false, // Desactivamos el suyo para usar el de Tailwind
  themeSystem: 'standard',
  // Este callback se ejecuta cada vez que cambia la vista o el mes
  datesSet: (arg) => {
    tituloActual.value = arg.view.title;
    vistaActiva.value = arg.view.type; //Asegurar que el aspecto del boton cambie
  },
  eventBackgroundColor: '#9333ea',
  eventBorderColor: 'transparent',
  contentHeight: 'auto',
  eventDidMount: function(info) {
    // Esto crea el tooltip básico del navegador al pasar el cursor
    const title = info.event.title;

    // Ponemos el tooltip tanto en el elemento principal como en el título
    info.el.setAttribute('title', `${title}`);
  },
  events: eventosCalendario
})

// Función para cambiar de vista (Month, Week, Day)
const cambiarVista = (vista) => {
  const calendarApi = fullCalendar.value.getApi();
  calendarApi.changeView(vista);
  vistaActiva.value = vista;
};

const formatearFecha = (fechaRaw) => {
  if (!fechaRaw) return '';
  const soloFecha = fechaRaw.split(' ')[0];
  const [dia, mes, anio] = soloFecha.split('-');
  return `${anio}-${mes}-${dia}`;
}

const mapaColores = {
  'Azul': '#3b82f6',      // Trabajo
  'Naranja': '#f97316',   // Personal
  'Rojo': '#ef4444',      // Urgente
  'Verde': '#22c55e',     // Ocio
  'Rosa': '#ec4899',      // Aprendizaje
  'Amarillo': '#eab308'   // Eventos
};

onMounted(async () => {
  await infoTareas(); 
  await refrescarCalendario();
  await ejecutarAnalisisEstrategico(); // Lanzamos el análisis al cargar
});

//Actualizar calendario
const refrescarCalendario = async () => {
  await infoTareas(); // Llamamos a tu JS de obtener tareas

  if (tareas.value) {
    eventosCalendario.value = tareas.value.map(tarea => {
      const colorHex = mapaColores[tarea.color] || '#9333ea';
      const clasesEfecto = [(tarea.color || 'morado').toLowerCase().trim()];
      
      // Aplicamos la lógica de clases que ya definiste en tu CSS
      if (tarea.estado === 'Completada') {
        clasesEfecto.push('tarea-completada');
      } else {
        clasesEfecto.push('tarea-no-completada');
      }

      return {
        id: tarea.id, // Importante para que FullCalendar los identifique
        title: tarea.nombre_tarea,
        start: formatearFecha(tarea.fecha_vencimiento),
        backgroundColor: colorHex,
        borderColor: colorHex,
        classNames: clasesEfecto,
        extendedProps: {
          descripcion: tarea.descripcion,
          categoria: tarea.nombre_categoria,
          estado: tarea.estado
        }
      };
    });
  }
};

// --- LÓGICA DEL CHAT ---
const preguntaUsuario = ref('');
const mensajesChat = ref([
  { role: 'bot', text: '¡Hola! Soy tu asistente. ¿En qué puedo ayudarte hoy?' }
]);
const cargandoIA = ref(false);

const manejarEnvioChat = async () => {
  // CORREGIDO: .value en lugar de .ref
  if (!preguntaUsuario.value.trim() || cargandoIA.value) return;

  const texto = preguntaUsuario.value;
  preguntaUsuario.value = ''; 
  
  mensajesChat.value.push({ role: 'user', text: texto });
  cargandoIA.value = true;

  try {
    const res = await enviarMensajeAlBot(texto);
    
    // Flowise suele devolver el texto en 'text'
    const respuestaBot = res.text || res.prediction || "Acción procesada.";
    mensajesChat.value.push({ role: 'bot', text: respuestaBot });
    
    // Solo refrescamos si la respuesta fue exitosa
    await refrescarCalendario();
    
  } catch (error) {
    console.error("Error en manejarEnvioChat:", error);
    mensajesChat.value.push({ role: 'bot', text: 'Error al conectar con el asistente.' });
  } finally {
    cargandoIA.value = false;
  }
};
//Asistente
const enviarMensajeAlBot = async (textoUsuario) => {
  //enviamos al asistente que esta en flowise
  const URL_FLOWISE = "http://localhost:3005/api/v1/prediction/7f5736f5-91db-4341-a2c5-1a1ca1a20750";

  const response = await fetch(URL_FLOWISE, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ question: textoUsuario })
  });

  if (!response.ok) throw new Error("Error en la respuesta de Flowise");
  
  return await response.json();
};

const ejecutarAnalisisEstrategico = async () => {
  cargandoAnalisis.value = true;
  try {
    const response = await fetch("http://localhost:3005/api/v1/prediction/fcb20894-0a45-47a8-8966-e00ab0fd340f", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        // Enviamos un trigger que dispare el listar
        question: "Realiza el análisis estratégico de mis tareas actuales." 
      })
    });
    
    const data = await response.json();
    mensajeAgenteEstratega.value = data.text; //Hacer visible el mensaje del agente
  } catch (error) {
    mensajeAgenteEstratega.value = "Error al sincronizar con el estratega.";
  } finally {
    cargandoAnalisis.value = false;
  }
  };
</script>

