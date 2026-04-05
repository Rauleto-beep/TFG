<template>
  <div class="login-container">
    <h2>Iniciar Sesión</h2>
    
    <input v-model="form.nombre_usuario" type="text" placeholder="Nombre de usuario" />
    <input v-model="form.correo" type="email" placeholder="Correo electrónico" />
    <input v-model="form.password" type="password" placeholder="Contraseña" />
    
    <button @click="hacerLogin">Entrar</button>

    <p>¿No tienes cuenta? <router-link to="/registro">Regístrate aquí</router-link></p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const cargando = ref(false);
const errorMsg = ref(''); // Variable para el mensaje de error

const form = ref({
  nombre_usuario: '',
  correo: '',
  password: ''
});

const hacerLogin = async () => {
  errorMsg.value = ''; // Limpiamos errores anteriores
  cargando.value = true;

  const datosLogin = {
    username: form.value.correo, // LexikJWT espera 'username'
    password: form.value.password
  };

  try {
    const response = await fetch('http://localhost:8081/index.php/api/login_check', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(datosLogin)
    });

    const data = await response.json();

    if (response.ok) {
      // 1. Guardamos el Token JWT
      localStorage.setItem('jwt_token', data.token);

      // 2. Guardamos los datos del usuario que vienen en el nuevo objeto 'user'
      // Estos son los que configuramos en el JWTCreatedListener de Symfony
      localStorage.setItem('user_id', data.user.id);
      localStorage.setItem('user_correo', data.user.correo);
      localStorage.setItem('user_nombre', data.user.nombre);

      // 3. Redirigimos
      router.push('/inicio');
    } else if (response.status === 401) {
      // Si las credenciales fallan, ejecutamos la comprobación detallada
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
    const res = await fetch('http://localhost:8081/index.php/api/comprobar-usuario', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    });

    const data = await res.json();

    if (res.status === 404) {
      alert( "Este correo no está registrado.");
    } else {
      // Si el código es 401 o 400 (usuario/pass mal)
      alert("Credenciales incorrectas (usuario o contraseña mal escritos).");
    }
  } catch (err) {
    alert( "Error al verificar credenciales.");
  }
};
</script>