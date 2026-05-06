<template>
    <main class="flex-1 flex flex-col relative">
        <div class="flex-1 flex flex-col" style="opacity: 1; transform: none;">
            <div class="flex-1 p-12 overflow-y-auto">
                <!-- Titulo -->
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h2 class="text-4xl font-bold tracking-tight mb-2">Gestión de tareas</h2>
                        <p class="text-zinc-400">Potenciado por el moto Amatista AI</p>
                    </div>
                </div>
                <!-- Info tareas y creacion -->
                <div class="grid grid-cols-12 gap-12">
                    <div class="col-span-4 space-y-6">
                        <div class="flex items-center">
                            <h3 class="text-lg font-bold">Existentes </h3>
                            <span class="h-5 w-5 animate-spin rounded-full border-2 border-gray-200 border-t-blue-500" v-if="cantidad === null"></span>
                            <span class="ml-2 text-zinc-400 text-sm" v-else>{{ cantidad }}</span>
                        </div>
                        <div class="space-y-4">
    <div v-for="tarea in tareas" :key="tarea.id" 
         class="card p-5 border-brand/40 bg-brand/5 rounded-xl shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-brand/20 text-brand"
                :class="{
                    'bg-green-500/20 text-green-400': tarea.estado === 'Completada',
                    'bg-red-500/20 text-red-400': tarea.estado === 'No completada',
                    }">{{ tarea.estado }}</span>
            <span class="text-[10px] text-zinc-500 font-medium">{{ tarea.fecha_publicacion.split(" ")[0] }}</span>
        </div>

        <h4 class="font-bold text-lg mb-1 group-hover:text-brand transition-colors text-white">{{ tarea.nombre_tarea }}</h4>
        <p class="text-xs text-zinc-400 line-clamp-2 mb-6 leading-relaxed">{{ tarea.descripcion }}</p>

        <div class="flex items-center gap-4">
            
            <div class="flex-1 min-w-[80px]">
                <span class="text-[10px] font-bold text-zinc-500 uppercase block truncate"></span>
                <span class="text-[11px] text-zinc-300 truncate block">{{ tarea.nombre_categoria }}</span>
            </div>

            <div class="flex items-center gap-2">
                <button @click="manejarTareaEliminada(tarea.id)" class="w-11 h-11 flex items-center justify-center bg-[#7b4fff] rounded-lg transition-all duration-200 hover:shadow-lg hover:brightness-110 active:scale-90 cursor-pointer group/btn">
                    <img src="../imagenes/simbolo_eliminar_tarea.svg" style="max-width:90px;">
                </button>
                <button @click="manejarTareaCompletada(tarea.id,tarea.estado)" class="w-11 h-11 flex items-center justify-center bg-[#7b4fff] rounded-lg transition-all duration-200 hover:shadow-lg hover:brightness-110 active:scale-90 cursor-pointer group/btn">
                    <img src="../imagenes/simbolo_completarTarea.svg" style="max-width:90px;">
                </button>
                <button @click="manejarTareaEditada(tarea.id,tarea.nombre_tarea,tarea.descripcion,tarea.fecha_vencimiento.split(' ')[0],tarea.prioridad,tarea.nombre_categoria)" class="w-11 h-11 flex items-center justify-center bg-[#7b4fff] rounded-lg transition-all duration-200 hover:shadow-lg hover:brightness-110 active:scale-90 cursor-pointer group/btn">
                    <img src="../imagenes/simbolo_editarTarea.svg" style="max-width:90px;">
                </button>
            </div>

            <div class="flex flex-col items-end flex-1 shrink-0">
                <span class="text-[10px] font-bold text-zinc-500 uppercase">Prioridad</span>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <div class="w-2 h-2 rounded-full shadow-[0_0_8px_rgba(0,0,0,0.5)]"
                        :class="{
                            'bg-green-500': tarea.prioridad === 'Baja',
                            'bg-orange-500': tarea.prioridad === 'Media',
                            'bg-red-500': tarea.prioridad === 'Alta'
                        }">
                    </div>
                    <span class="text-[10px] font-bold text-zinc-200 uppercase">{{ tarea.prioridad }}</span>
                </div>
            </div>

        </div>
    </div>
</div>
                    </div>
                    <!-- Creacion tareas -->
                    <div class="col-span-8">
                        <div class="card p-10 space-y-10">
                            <!-- Logo + titulo -->
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 bg-brand/10 rounded-2xl flex items-center justify-center">
                                    <img src="../imagenes/simbolo_+.svg">
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold">
                                        {{ editar ? 'Editar tarea' : 'Nueva tarea' }}
                                    </h3>
                                    <p class="text-zinc-500">Define los parámetros y el motor Amatista optimizará el flujo.</p>
                                </div>
                            </div>
                            <!-- Datos tarea -->
                            <div class="space-y-8">
                                <input type="hidden" name="idTarea" id="idTarea" value="">
                                <div class="space-y-3">
                                    <label class="pl-5 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Titulo de la tarea</label>
                                    <input id="inputNombreTarea" class="input w-full text-lg py-4" type="text" placeholder="Nombre de la tarea">
                                </div>
                                <div class="space-y-3">
                                    <label class="pl-5 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Descripcion de la tarea</label>
                                    <textarea id="inputDescripcionTarea" class="resize-none input w-full min-h-[150px] py-4" maxlength="250" placeholder="Escribe la descripcion de la tarea..." name="descripcionTextArea"></textarea>
                                </div>
                                <div class="grid grid-cols-3 gap-8">
                                    <!-- Calendario -->
                                    <div class="space-y-3">
                                        <label class="pl-5 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Fecha limite</label>
                                        <div class="relative">
                                            <input id="inputDate" class="input w-full inputDate" type="date">
                                        </div>
                                    </div>
                                    <!-- Prioridad -->
                                    <div class="space-y-3">
                                        <label class="pl-5 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Prioridad</label>
                                        <div class="relative">
                                            <select id="inputPrioridad" class="input w-full appearance-none" name="selectPrioridadTarea">
                                                <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;">Baja</option>
                                                <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;">Media</option>
                                                <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;">Alta</option>
                                            </select>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 pointer-events-none" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
                                        </div>
                                    </div>
                                    <!-- Categoria -->
                                     <div class="space-y-3">
                                        <label class="pl-5 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Categoria</label>
                                            <div class="relative">
                                                <select id="inputCategoria" class="input w-full appearance-none" name="selectCategoriaTarea">
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="7" selected>Ninguna</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="1">Trabajo</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="2">Personal</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="3">Urgente</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="4">Ocio</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="5">Aprendizaje</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="6">Eventos</option>
                                                </select>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 pointer-events-none" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <!-- Boton creacion y boton ia -->
                            <div class="flex gap-4 pt-6">
                                <button v-if="editar" @click="confirmarEdicion" id="botonEditarTarea" class="flex-1 btn-primary py-4 text-lg">
                                    <img src="../imagenes/simbolo_+_enblanco.svg" style="width:50px; height:50px;">
                                    <span>EDITAR TAREA</span>
                                </button>
                                <button v-else @click="manejarCreacion" id="botonCrearTarea" class="flex-1 btn-primary py-4 text-lg">
                                    <img src="../imagenes/simbolo_+_enblanco.svg" style="width:50px; height:50px;">
                                    <span>CREAR TAREA</span>
                                </button>
                            </div>
                            <!-- Descripcion ia -->
                            <div class="p-6 bg-white/5 border border-white/10 rounded-2xl flex gap-4">
                                <div class="w-10 h-10 bg-brand/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <img src="../imagenes/simbolo_info_ia_tareas.svg">
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold mb-1">Optimizador Amatista v3.2</h4>
                                    <p class="text-xs text-zinc-500 leading-relaxed">El motor de inteligencia Amatista 
                                        analizará la descripción para sugerir etiquetas,
                                        estimar tiempos de desarrollo y vincular documentación relevante de forma
                                        automática.</p>
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
.inputDate::-webkit-datetime-edit {
    color: white !important;
}

.inputDate::-webkit-datetime-edit-fields-wrapper,
.inputDate::-webkit-datetime-edit-text,
.inputDate::-webkit-datetime-edit-month-field,
.inputDate::-webkit-datetime-edit-day-field,
.inputDate::-webkit-datetime-edit-year-field {
    color: white !important;
}

.inputDate::-webkit-calendar-picker-indicator {
    filter: invert(100%) brightness(100%);
    cursor: pointer;
}

#botonCrearTarea:hover{
    background-color: #8c66ff; 
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(123, 79, 255, 0.4);
    cursor: pointer;
}

#botonCrearTarea:active {
    transform: translateY(0);
    box-shadow: 0 2px 6px rgba(123, 79, 255, 0.3);
}

#botonEditarTarea:hover{
    background-color: #8c66ff; 
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(123, 79, 255, 0.4);
    cursor: pointer;
}

#botonEditarTarea:active {
    transform: translateY(0);
    box-shadow: 0 2px 6px rgba(123, 79, 255, 0.3);
}
</style>

<script setup>
    import { onMounted } from 'vue';
    import { useCantidadTareas } from '../../src/JavaScript/Contadores/cantidadTareas.js';
    import { obtenerTareasUsuario } from '../../src/JavaScript/CRUDtareas/obtenerTareas.js';
    import { crearTareasUsuario } from '../../src/JavaScript/CRUDtareas/crearTarea.js';
    import { eliminarTareasUsuario } from '../../src/JavaScript/CRUDtareas/eliminarTarea.js'
    import { completarTareasUsuario } from '../../src/JavaScript/CRUDtareas/manejarTareaCompletada.js';
    import { editarTareasUsuario } from '../../src/JavaScript/CRUDtareas/editarTarea.js';
    import { confirmarEdicionTareasUsuario } from '../../src/JavaScript/CRUDtareas/confirmarEdicion.js';

    const { cantidad, cantidadTareas } = useCantidadTareas();
    const { tareas,infoTareas } = obtenerTareasUsuario();
    const { crearTarea } = crearTareasUsuario();
    const { eliminarTarea } = eliminarTareasUsuario();
    const { completarTarea } = completarTareasUsuario();
    let { editarTarea,editar, datosTarea } = editarTareasUsuario();
    const { editarTareaConfirmada } = confirmarEdicionTareasUsuario();

    //Se encarga de crear las tareas
    const manejarCreacion = async () => {
        await crearTarea(); //Crea la tarea
        
        await infoTareas(); //Una vez creada actualizamos el tablero para que se cree una 'card'
        await cantidadTareas(); //Actualizamos tambien el numero de "Tarea existente"
        
        //Limpiamos los campos en el DOM una vez creada la tarea
        document.getElementById("inputNombreTarea").value = "";
        document.getElementById("inputDescripcionTarea").value = "";
    };

    const manejarTareaEliminada= async (tarea) =>{
        await eliminarTarea(tarea);

        await infoTareas(); 
        await cantidadTareas();
    }

    const manejarTareaCompletada = async (id,estado) => {
        await completarTarea(id,estado);

        await infoTareas(); 
        await cantidadTareas();
    };

    const manejarTareaEditada = async (id,nombre,descripcion,fechaVencimiento,prioridad,cat) => {
        await editarTarea(id,nombre,descripcion,fechaVencimiento,prioridad,cat);
    };

    const confirmarEdicion = async () => {
        await editarTareaConfirmada();
        await infoTareas()
        editar.value = false; 
        document.getElementById("inputNombreTarea").value = "";
        document.getElementById("inputDescripcionTarea").value = "";
    };

    onMounted(async()=>{
        await infoTareas();
        await cantidadTareas();
        editar.value = false;
        
    });
</script>