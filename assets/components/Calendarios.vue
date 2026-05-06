<template>
    <main class="flex-1 flex flex-col relative">
        <div class="flex-1 flex flex-col" style="opacity: 1; transform: none;">
            <div class="flex-1 p-8 overflow-y-auto">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold tracking-tight mb-1">{{ tituloActual }}</h2>
                </div>
                <div class="grid grid-cols-12 gap-8">
                    <!-- CALENDARIOS  -->
                    <div class="col-span-9 space-y-8">
                        <FullCalendar :options="calendarOptions" />
                    </div>
                    <!-- DISPLAY DE TAREAS  -->
                     <div class="col-span-3 space-y-8">
                        <div class="space-y-6">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-brand"></div>
                                <h3 class="text-xs font-bold uppercase tracking-widest text-zinc-400">PROXIMAS TAREAS</h3>
                            </div>
                        <div class="space-y-0 ml-4 border-l border-zinc-800"> <div v-for="tarea in tareas" :key="tarea.id" 
         class="relative pl-8 pb-10 group">
        
        <div class="absolute -left-[13px] top-1 w-6 h-6 rounded-full bg-[#0f0a15] border-2 flex items-center justify-center transition-all shadow-lg"
             :style="{ borderColor: mapaColores[tarea.color] || '#52525b' }"> <div class="w-2 h-2 rounded-full" 
                 :style="{ backgroundColor: mapaColores[tarea.color] || '#52525b' }">
            </div>
        </div>

        <div class="flex flex-col">
            <span class="text-[11px] font-bold text-zinc-500 uppercase tracking-widest mb-1">
                {{ tarea.fecha_vencimiento ? tarea.fecha_vencimiento.split(" ")[0] : 'Sin fecha' }}
            </span>

            <h4 class="font-bold text-xl text-white group-hover:text-white transition-colors">
                {{ tarea.nombre_tarea }}
            </h4>

            <p class="text-sm text-zinc-500 italic mt-1 leading-relaxed max-w-md">
                {{ tarea.descripcion }}
            </p>

            <div class="flex items-center gap-3 mt-4">
                <span class="text-[10px] font-bold uppercase tracking-tighter"
                      :style="{ color: mapaColores[tarea.color] || '#71717a' }">
                    {{ tarea.nombre_categoria }}
                </span>
                
                <span v-if="tarea.estado === 'Completada'" class="flex items-center gap-1 text-[10px] font-bold text-emerald-500 uppercase">
                    <span class="text-xs">✓</span> HECHO
                </span>
                <span v-if="tarea.estado === 'No completada'" class="flex items-center gap-1 text-[10px] font-bold text-red-400 uppercase">
                    <span class="text-xs">❌</span> NO HECHO   
                </span>
            </div>
        </div>

    </div>
</div>
    </div>
                     </div>
                </div>
            </div>
        </div>
    </main>
</template>
<style>
.fc-theme-standard td, .fc-theme-standard th {
    border: 1px solid rgba(255, 255, 255, 0.05) !important;
}

.fc-theme-standard .fc-scrollgrid {
    border: none !important;
}

.fc-multimonth-month-title {
    font-size: 1.2rem !important;
    font-weight: 700 !important;
    color: #a1a1aa !important; 
    padding-bottom: 1rem !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.fc-multimonth-daygrid-header-cell {
    background: transparent !important;
    border: none !important;
}

.fc-multimonth-daygrid-header-cell .fc-col-header-cell-cushion {
    font-size: 0.65rem !important;
    font-weight: 800 !important;
    color: #52525b !important;
    text-decoration: none !important;
}

.fc-multimonth-daygrid-day {
    background-color: transparent !important;
}

.fc-multimonth-daygrid-day-number {
    font-size: 0.75rem !important;
    color: #71717a !important;
    padding: 4px !important;
}

.fc-bg-event {
    opacity: 0.4 !important;
    border-radius: 4px !important;
    margin: 2px !important;
}

.fc-day-disabled {
    background-color: transparent !important;
    opacity: 0.1;
}

.fc-multimonth-month {
    padding: 20px !important;
    background: rgba(255, 255, 255, 0.02); /* Fondo sutil para cada mes */
    border-radius: 16px;
}
</style>
<script setup>
import {ref, onMounted } from 'vue';
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import multiMonthPlugin from '@fullcalendar/multimonth'
import { obtenerTareasUsuario } from '../../src/JavaScript/CRUDtareas/obtenerTareas.js';


const { tareas,infoTareas } = obtenerTareasUsuario();
const tituloActual = ref('');

const mapaColores = {
  'Azul': '#3b82f6',      // Trabajo
  'Naranja': '#f97316',   // Personal
  'Rojo': '#ef4444',      // Urgente
  'Verde': '#22c55e',     // Ocio
  'Rosa': '#ec4899',      // Aprendizaje
  'Amarillo': '#eab308'   // Eventos
};

const formatearFecha = (fechaRaw) => {
  if (!fechaRaw) return '';
  const soloFecha = fechaRaw.split(' ')[0];
  const [dia, mes, anio] = soloFecha.split('-');
  return `${anio}-${mes}-${dia}`;
}

 const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, multiMonthPlugin], //Tipo de visualizacion
  initialView: 'multiMonthYear', //Visualizacion inicial
  headerToolbar: false, // Desactivamos el suyo para usar el de Tailwind
  themeSystem: 'standard',
  datesSet: (arg) => {
    tituloActual.value = arg.view.title; // Titulo calendario con el mes actual
  },
  //Estilos del calendario
  eventBackgroundColor: '#9333ea',
  eventBorderColor: 'transparent',
  contentHeight: 'auto',
  events: [] //Rellenar el calendario con las tareas,esto se hace dinamicamente
})


onMounted(async()=>{
    await infoTareas();
    if (tareas.value && tareas.value.length > 0) {
    const eventosProcesados = tareas.value.map(tarea => {
      // Obtenemos el color hexadecimal del mapeo de las categorias
      const colorHex = mapaColores[tarea.color] || '#9333ea';

      return {
        start: formatearFecha(tarea.fecha_vencimiento),
        display: 'background', 
        backgroundColor: colorHex,
        //Tooltips
        extendedProps: {
          descripcion: tarea.descripcion,
          categoria: tarea.nombre_categoria
        }
      };
    });
    //Meter las tareas que llegan de infoTareas despues de haberlas procesado con las opciones que quiero para el calendario
    calendarOptions.value.events = eventosProcesados;
  }
});
</script>