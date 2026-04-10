<template>
  <div class="min-h-screen flex items-center justify-center bg-[#09090b] p-4 text-zinc-100 font-sans">
    <div class="w-full max-w-lg bg-[#12121a] rounded-2xl border border-zinc-800 p-8 shadow-2xl">
      
      <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-tight">Crea tu cuenta</h2>
        <p class="text-zinc-500 text-sm mt-1">Únete a Bifrost para empezar a gestionar tus tareas de forma inteligente</p>
      </div>

      <form @submit.prevent="enviarRegistro" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 ml-1">Nombre de usuario</label>
            <input 
              v-model="form.nombre_usuario" 
              type="text" 
              placeholder="Usuario" 
              required 
              class="w-full bg-[#09090b] border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#8b5cf6] focus:ring-1 focus:ring-[#8b5cf6] transition-all placeholder:text-zinc-700"
            />
          </div>

          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 ml-1">Correo electrónico</label>
            <input 
              v-model="form.correo" 
              type="email" 
              placeholder="ejemplo@gmail.com" 
              required 
              class="w-full bg-[#09090b] border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#8b5cf6] focus:ring-1 focus:ring-[#8b5cf6] transition-all placeholder:text-zinc-700"
            />
          </div>
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 ml-1">Contraseña</label>
          <input 
            v-model="form.password" 
            type="password" 
            placeholder="••••••••" 
            required 
            class="w-full bg-[#09090b] border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#8b5cf6] focus:ring-1 focus:ring-[#8b5cf6] transition-all placeholder:text-zinc-700"
          />
          <p class="text-[10px] text-zinc-600 px-1">Usa una combinación de letras, números y símbolos.</p>
        </div>

        <button 
          type="submit" 
          :disabled="cargando"
          class="w-full bg-[#8b5cf6] hover:bg-[#7c3aed] disabled:bg-zinc-800 disabled:text-zinc-500 disabled:cursor-not-allowed text-white font-bold py-3 rounded-lg transition-all shadow-lg shadow-purple-900/20 mt-2"
        >
          <span v-if="!cargando">Registrarme en Bifrost</span>
          <span v-else class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Creando cuenta...
          </span>
        </button>
      </form>

      <div class="mt-8 pt-6 border-t border-zinc-800/50 text-center">
        <p class="text-zinc-500 text-sm">
          ¿Ya tienes cuenta? 
          <router-link to="/login" class="text-[#8b5cf6] font-medium hover:underline ml-1 italic">
            Inicia sesión aquí
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const cargando = ref(false);

const form = ref({
  nombre_usuario: '',
  correo: '',
  password: ''
});

const enviarRegistro = async () => {
  cargando.value = true; // Corregido .ref por .value
  try {
    const response = await fetch('https://localhost:8081/index.php/usuario/crear', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    });

    if (response.ok) {
      alert("¡Cuenta creada con éxito! Ahora puedes iniciar sesión.");
      router.push('/login');
    } else {
      const errorData = await response.json();
      alert("Error: " + (errorData.message || "No se pudo crear la cuenta"));
    }
  } catch (error) {
    alert("Error de conexión con el servidor");
  } finally {
    cargando.value = false;
  }
};
</script>