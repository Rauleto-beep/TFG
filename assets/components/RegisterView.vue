<template>
  <div class="registro-container">
    <div class="registro-card">
      <h2>Crea tu cuenta</h2>
      <p class="subtitle">Regístrate para gestionar tus tareas</p>

      <form @submit.prevent="enviarRegistro">
        <div >
          <label>Nombre de usuario</label>
          <input v-model="form.nombre_usuario" type="text" placeholder="Ej: Juanito" required />
        </div>

        <div>
          <label>Correo electrónico</label>
          <input v-model="form.correo" type="email" placeholder="ejemplo@gmail.com" required />
        </div>

        <div >
          <label>Contraseña</label>
          <input v-model="form.password" type="password" placeholder="••••••••" required />
        </div>

        <button type="submit" :disabled="cargando">
          {{ cargando ? 'Registrando...' : 'Registrarme' }}
        </button>
      </form>

      <p>
        ¿Ya tienes cuenta? <router-link to="/login">Inicia sesión aquí</router-link>
      </p>
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
  cargando.ref = true;
  try {
    const response = await fetch('http://localhost:8081/index.php/usuario/crear', {
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
