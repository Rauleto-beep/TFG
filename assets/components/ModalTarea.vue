<template>
  <div class="bg-[#18181b] p-8 rounded-3xl border border-white/5 space-y-8 max-w-2xl mx-auto shadow-2xl">
    
    <input type="hidden" name="idTarea" id="idTarea" value="">

    <div class="space-y-3">
      <label class="pl-1 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500">Titulo de la tarea</label>
      <input 
        v-model="nuevaTarea.titulo"
        id="inputNombreTarea" 
        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-lg focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all placeholder:text-zinc-600" 
        type="text" 
        placeholder="Nombre de la tarea">
    </div>

    <div class="space-y-3">
      <label class="pl-1 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500">Descripcion de la tarea</label>
      <textarea 
        v-model="nuevaTarea.descripcion"
        id="inputDescripcionTarea" 
        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white min-h-[150px] resize-none focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all placeholder:text-zinc-600" 
        maxlength="250" 
        placeholder="Escribe la descripcion de la tarea..."></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="space-y-3">
        <label class="pl-1 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500">Fecha limite</label>
        <div class="relative">
          <input 
            v-model="nuevaTarea.fechaLimite"
            id="inputDate" 
            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-zinc-300 focus:outline-none focus:ring-1 focus:ring-brand appearance-none" 
            type="date">
        </div>
      </div>

      <div class="space-y-3">
        <label class="pl-1 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500">Prioridad</label>
        <div class="relative">
          <select 
            v-model="nuevaTarea.prioridad"
            id="inputPrioridad" 
            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white appearance-none outline-none focus:ring-1 focus:ring-brand">
            <option value="Baja" class="bg-[#1e1f22]">Baja</option>
            <option value="Media" class="bg-[#1e1f22]">Media</option>
            <option value="Alta" class="bg-[#1e1f22]">Alta</option>
          </select>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 pointer-events-none"><path d="m6 9 6 6 6-6"></path></svg>
        </div>
      </div>

      <div class="space-y-3">
        <label class="pl-1 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500">Categoria</label>
        <div class="relative">
          <select 
            v-model="nuevaTarea.categoria"
            id="inputCategoria" 
            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white appearance-none outline-none focus:ring-1 focus:ring-brand">
            <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="" selected>Ninguna</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="1">Trabajo</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="2">Personal</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="3">Urgente</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="4">Ocio</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="5">Aprendizaje</option>
                                                    <option class="text-[10px] font-bold uppercase tracking-widest border-brand/50 appearance-none" style="background-color: #1e1f22;" value="6">Eventos</option>
          </select>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 pointer-events-none"><path d="m6 9 6 6 6-6"></path></svg>
        </div>
      </div>
    </div>

    <div class="flex items-center justify-end gap-8 pt-6 border-t border-white/5">
      <button @click="$emit('cerrar')" class="text-[11px] font-bold text-zinc-500 hover:text-white transition-all">CANCELAR</button>
      <button id="botonCrearTarea" @click="confirmarEnvio" class="bg-brand px-8 py-4 rounded-2xl text-[11px] font-black text-white shadow-lg shadow-brand/20 transition-all active:scale-95">CREAR</button>
    </div>
  </div>
</template>

<style>
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
</style>

<script setup>
import { ref } from 'vue';

// Definimos los eventos que este hijo puede enviar al padre
const emit = defineEmits(['cerrar', 'crear']);

// La lógica de la tarea
const nuevaTarea = ref({
  titulo: '',
  descripcion: '',
  fechaLimite: '',
  prioridad: 'Media',
  categoria: ''
});

const confirmarEnvio = () => {
  if (!nuevaTarea.value.titulo) return;
  // Enviamos los datos al padre para que él use la función 'enviarMensaje'
  emit('crear', { ...nuevaTarea.value });
  // Limpiamos
  nuevaTarea.value = { titulo: '', descripcion: '', fechaLimite: '', prioridad: 'Media', categoria: '' };
};
</script>