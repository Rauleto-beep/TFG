<template>
  <div class="min-h-screen flex items-center justify-center bg-[#09090b] p-4 text-zinc-100 font-sans">
    <div class="w-full max-w-md bg-[#12121a] rounded-2xl border border-zinc-800 p-8 shadow-2xl">
      
      <div class="text-center mb-8">
        <div class="flex justify-center mb-3">
          <div class="w-12 h-12 bg-[#8b5cf6] rounded-xl flex items-center justify-center shadow-[0_0_20px_rgba(139,92,246,0.3)]">
            <span class="text-white font-bold text-2xl">B</span>
          </div>
        </div>
        <h2 class="text-2xl font-bold tracking-tight">Bienvenido de nuevo</h2>
        <p class="text-zinc-500 text-sm mt-1">Ingresa tus credenciales para acceder</p>
      </div>

      <div class="space-y-5">
        
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 ml-1">Nombre de Usuario</label>
          <input 
            v-model="form.nombre_usuario" 
            type="text" 
            placeholder="Tu alias"
            class="w-full bg-[#09090b] border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#8b5cf6] focus:ring-1 focus:ring-[#8b5cf6] transition-all placeholder:text-zinc-700"
          />
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 ml-1">Correo Electrónico</label>
          <input 
            v-model="form.correo" 
            type="email" 
            placeholder="nombre@ejemplo.com"
            class="w-full bg-[#09090b] border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#8b5cf6] focus:ring-1 focus:ring-[#8b5cf6] transition-all placeholder:text-zinc-700"
          />
        </div>

        <div class="space-y-2">
          <div class="flex justify-between items-center px-1">
            <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Contraseña</label>
            <a href="#" class="text-[10px] text-[#8b5cf6] hover:underline">¿Olvidaste tu contraseña?</a>
          </div>
          <input 
            v-model="form.password" 
            type="password" 
            placeholder="••••••••"
            class="w-full bg-[#09090b] border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#8b5cf6] focus:ring-1 focus:ring-[#8b5cf6] transition-all placeholder:text-zinc-700"
          />
        </div>

        <p v-if="errorMsg" class="text-red-400 text-xs font-medium text-center bg-red-400/10 py-2 rounded border border-red-400/20">
          {{ errorMsg }}
        </p>

        <button 
          @click="hacerLogin"
          :disabled="cargando"
          class="w-full bg-[#8b5cf6] hover:bg-[#7c3aed] disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-3 rounded-lg transition-all shadow-lg shadow-purple-900/20 mt-2"
        >
          {{ cargando ? 'Cargando...' : 'Entrar a Bifrost' }}
        </button>
      </div>

      <div class="mt-8 text-center border-t border-zinc-800/50 pt-6">
        <p class="text-zinc-500 text-sm">
          ¿No tienes cuenta? 
          <router-link to="/registro" class="text-[#8b5cf6] font-medium hover:underline ml-1">Regístrate aquí</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
/* TU LÓGICA ORIGINAL INTACTA */
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const cargando = ref(false);
const errorMsg = ref(''); 

const form = ref({
  nombre_usuario: '',
  correo: '',
  password: ''
});

const hacerLogin = async () => {
  errorMsg.value = ''; 
  cargando.value = true;

  const datosLogin = {
    username: form.value.correo, 
    password: form.value.password
  };

  try {
    const response = await fetch('https://localhost:8081/index.php/api/login_check', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(datosLogin)
    });

    const data = await response.json();

    if (response.ok) {
      localStorage.setItem('jwt_token', data.token);
      localStorage.setItem('user_id', data.user.id);
      localStorage.setItem('user_correo', data.user.correo);
      localStorage.setItem('user_nombre', data.user.nombre);
      router.push('/inicio');
    } else if (response.status === 401) {
      await verificarSiExiste();
    } else {
      errorMsg.value = "Error en el servidor. Inténtalo más tarde.";
    }
  } catch (err) {
    console.error("Error de login:", err);
    errorMsg.value = "No se pudo conectar con el servidor.";
  } finally {
    cargando.value = false;
  }
};

const verificarSiExiste = async () => {
  try {
    const res = await fetch('https://localhost:8081/index.php/api/comprobar-usuario', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    });

    const data = await res.json();

    if (res.status === 404) {
      errorMsg.value = "Este correo no está registrado.";
    } else {
      errorMsg.value = "Credenciales incorrectas.";
    }
  } catch (err) {
    errorMsg.value = "Error al verificar credenciales.";
  }
};
</script>