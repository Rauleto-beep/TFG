<template>
    <div style="opacity: 1; transform: none;">
        <div class="grid grid-cols-12 gap-12">
            <div class="col-span-4 space-y-4">
                <h3 class="text-lg font-bold">Seguridad de la cuenta</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Cambia la contraseña de tu cuenta</p>
            </div>
            <div class="col-span-8">
                <div class="card space-y-8">
                    <div class="space-y-6">
                        <h4 class="text-sm font-bold border-b border-border pb-2">Cambiar contraseña</h4>
                        <!-- INPUTS  -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Nueva contraseña</label>
                                    <input id="nuevaContraseña" placeholder="••••••••" class="input w-full" type="password">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">confirmar nueva contraseña</label>
                                    <input id="confirmarNuevaContraseña" placeholder="••••••••" class="input w-full" type="password">
                                </div>
                            </div>
                        </div>
                        <!-- MENSAJES OCULTOS  -->
                         <div class="h-6 mt-1 overflow-hidden"> <transition name="fade-slide">
                            <p v-if="mensajeExito" class="text-green-500 text-xs font-medium flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    Contraseña actualizada correctamente
                                </p>
                            <p v-else-if="noCoincide" class="text-red-400 text-xs font-medium flex items-center gap-1.5">
                                <span class="text-xs">❌</span> Las contraseñas no coinciden o estan en blanco
                                </p>
                            </transition>
                        </div>
                        <!-- BOTON  -->
                         <div class="flex justify-end">
                            <button id="actualizarBoton" class="btn-primary px-8">Actualizar Contraseña</button>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref,onMounted } from 'vue';
import { actualizarContraseña } from '../../src/JavaScript/Ajustes/actualizarContraseña';

const { actualizarContraseñaUsuario } = actualizarContraseña();
const mensajeExito = ref(false); // Controla la visibilidad
const noCoincide = ref(false);

onMounted( () => {
    const actualizarBoton = document.getElementById("actualizarBoton");

    actualizarBoton.addEventListener("click",async ()=>{
        //Cojo los elementos del DOM
        const contraseñaCambiada = document.getElementById("nuevaContraseña").value;
        const contraseñaCambiadaConfirm = document.getElementById("confirmarNuevaContraseña").value;

        if (contraseñaCambiada == contraseñaCambiadaConfirm && contraseñaCambiada != "" && contraseñaCambiadaConfirm != ""){
            const resultado = await actualizarContraseñaUsuario(localStorage.getItem("user_id"),contraseñaCambiada);
            if (resultado){
                //MOSTRAMOS EL MENSAJE
                mensajeExito.value = true;

                //Ocultar el mensaje después de 3 segundos
                setTimeout(() => {
                    mensajeExito.value = false;
                }, 3000);
            }
        }else{
            //MOSTRAMOS EL MENSAJE
            noCoincide.value = true;

            //Ocultar el mensaje después de 3 segundos
            setTimeout(() => {
                noCoincide.value = false;
            }, 3000);
        }
    });
});
</script>