<template>
  <div class="flex h-screen bg-[#0f0a15] text-gray-300 font-sans overflow-hidden">
    <main class="flex-1 overflow-y-auto p-8 space-y-8">
      <header class="flex justify-between items-start">
        <div>
          <h2 class="text-3xl font-bold text-white mb-2">Vista del calendario</h2>
        </div>
        <button class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-purple-900/20">
          <span class="text-xl">+</span> Nueva tarea
        </button>
      </header>

      <section class="bg-gray-900/30 border border-gray-800 p-6 rounded-3xl">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-white">Marzo 2026</h3>
          <div class="flex bg-black/40 rounded-lg p-1">
            <button class="px-4 py-1 text-xs bg-purple-600 text-white rounded-md">Month</button>
            <button class="px-4 py-1 text-xs text-gray-400">Week</button>
            <button class="px-4 py-1 text-xs text-gray-400">Day</button>
          </div>
        </div>
        <!-- CALENDARIO  -->
        <FullCalendar class="grid gap-2" :options="calendarOptions" />
      </section>

      <section class="bg-gray-900/30 border border-gray-800 p-8 rounded-3xl relative overflow-hidden">
        <div class="flex gap-4 items-start">
          <div class="bg-purple-900/30 p-3 rounded-2xl text-purple-400">🤖</div>
          <div class="space-y-4">
            <h3 class="text-xl font-bold text-white">Mensaje del agente inteligente</h3>
            <p class="text-sm leading-relaxed max-w-2xl text-gray-400">
              System analysis complete. I recommend prioritizing the <span class="text-purple-400 font-bold">JWT Refresh Token logic</span> refactoring. 
              Detected high complexity in the current auth middleware that may cause latency spikes.
            </p>
            <div class="flex gap-3">
              <button class="text-xs border border-gray-700 px-4 py-2 rounded-full hover:bg-gray-800">Optimize Code</button>
              <button class="text-xs border border-gray-700 px-4 py-2 rounded-full hover:bg-gray-800">View Logs</button>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- CHAT IA -->
    <aside class="w-80 border-l border-gray-800 p-6 flex flex-col space-y-6">
      <div class="flex justify-between items-center">
        <h3 class="font-bold text-white">Asistente inteligente</h3>
      </div>

      <div class="bg-gray-900/40 border border-purple-500/20 p-5 rounded-2xl space-y-4">
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-purple-500"></div>
            <p class="text-sm font-semibold text-white">Finalize Backend Schema</p>
          </div>
          <span class="text-xs text-gray-600">^</span>
        </div>
        <p class="text-[11px] text-gray-500">Finalize the API endpoints for task CRUD operations and ensure Postgres indexes are correctly applied.</p>
        <div class="flex justify-between items-center pt-2">
          <span class="text-[10px] text-gray-600 uppercase">Due: Today, 18:00</span>
          <button class="text-[10px] bg-purple-600/10 text-purple-400 border border-purple-600/20 px-3 py-1 rounded-md">Complete</button>
        </div>
      </div>

      <div class="mt-auto bg-gray-900/50 border border-gray-800 p-4 rounded-2xl">
        <div class="relative">
          <input type="text" placeholder="Analyze system status..." class="w-full bg-black/40 border border-gray-800 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-purple-500">
          <button class="absolute right-3 top-2.5 text-purple-500 text-lg">➤</button>
        </div>
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
import { obtenerTareasUsuario } from '../../src/JavaScript/CRUDtareas/obtenerTareas.js';

const { tareas,infoTareas } = obtenerTareasUsuario();



 const calendarOptions = ref({
  plugins: [dayGridPlugin],
  initialView: 'dayGridMonth',
  headerToolbar: false, // Desactivamos el suyo para usar el de Tailwind
  themeSystem: 'standard',
  eventBackgroundColor: '#9333ea',
  eventBorderColor: 'transparent',
  contentHeight: 'auto',
  eventDidMount: function(info) {
    // Esto crea el tooltip básico del navegador al pasar el cursor
    const title = info.event.title;

    // Ponemos el tooltip tanto en el elemento principal como en el título
    info.el.setAttribute('title', `${title}`);
  },
  events: []
})

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

  if (tareas.value && tareas.value.length > 0) {
    const eventosProcesados = tareas.value.map(tarea => {
      const colorHex = mapaColores[tarea.color] || '#9333ea';

      // Creo un array de clases CSS
      const clasesEfecto = [(tarea.color || 'morado').toLowerCase().trim()];
      
      // Si esta completada, le añado la clase 'tarea-completada'
      if (tarea.estado === 'Completada') {
        clasesEfecto.push('tarea-completada');
      }else{
        clasesEfecto.push('tarea-no-completada');
      }
      return {
        title: tarea.nombre_tarea,
        start: formatearFecha(tarea.fecha_vencimiento),
        //añadir color basado en la prioridad o categoría
        backgroundColor: colorHex,
        borderColor: colorHex,
        classNames: clasesEfecto,
        extendedProps: {
          descripcion: tarea.descripcion,
          categoria: tarea.nombre_categoria
        }
      };
    });

    calendarOptions.value.events = eventosProcesados;
  }
});
</script>

