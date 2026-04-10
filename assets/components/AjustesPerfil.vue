<template>
    <div style="opacity: 1; transform: none;">
        <div class="grid grid-cols-12 gap-12">
            <div class="col-span-4 space-y-4">
                <h3 class="text-lg font-bold">Información Personal</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Actualiza tu información de contacto.</p>
            </div>
            <div class="col-span-8">
                <div class="card space-y-8">
                    <div class="flex items-center gap-6">
                        <h4 class="text-lg font-bold">{{ userName }}</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Nombre Usuario</label>
                            <input id="nombre" class="input w-full" type="text" :value="userName">
                            
                            <div class="h-6 mt-1 overflow-hidden"> <transition name="fade-slide">
                                    <p v-if="mensajeExito" class="text-green-500 text-xs font-medium flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                        Perfil actualizado correctamente
                                    </p>
                                </transition>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Correo Electronico</label>
                            <input id="correo" class="input w-full" type="email" :value="userCorreo">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button id="confirmarCambios" class="btn-primary px-8">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-slide-enter-active, .fade-slide-leave-active {
  transition: all 0.4s ease-out;
}

.fade-slide-enter-from, .fade-slide-leave-to {
  opacity: 0;
  transform: translateY(3px);
}
</style>

<script setup>
import { ref,onMounted } from 'vue';

import { actualizarUsuario } from '../../src/JavaScript/Ajustes/ActualizarNombreyCorreo';
const { actualizarInfoUsuario } = actualizarUsuario();

let userName = ref(localStorage.getItem("user_nombre"));
let userCorreo = ref(localStorage.getItem("user_correo"));
const mensajeExito = ref(false); // Controla la visibilidad

onMounted( () => {
    const actualizar = document.getElementById("confirmarCambios");
    actualizar.addEventListener("click",async ()=>{
        //Coger valor de los input
        const nombre = document.getElementById("nombre").value;
        const correo = document.getElementById("correo").value;

        const resultado = await actualizarInfoUsuario(localStorage.getItem("user_id"),nombre,correo); 
        
        if (resultado && resultado.token){
            localStorage.setItem("jwt_token", resultado.token); //Actualizar token para no perder la autorizacion
            localStorage.setItem("user_nombre",nombre);
            localStorage.setItem("user_correo",correo);

            //MOSTRAMOS EL MENSAJE
            mensajeExito.value = true;
            userName.value = nombre
            userCorreo.value = correo;
            //Ocultar el mensaje después de 3 segundos
            setTimeout(() => {
                mensajeExito.value = false;
            }, 3000);
            }
        });
});
</script>