<template>
    <main class="flex-1 flex flex-col relative">
        <div class="flex-1 flex flex-col" style="opacity: 1; transform: none;">
            <div class="flex-1 p-12 overflow-y-auto max-w-5xl mx-auto w-full">
                <!-- TITULO  -->
                <div class="mb-12">
                    <h2 class="text-4xl font-bold tracking-tight mb-2">Ajustes de cuenta</h2>
                    <p class="text-zinc-400">Gestiona tu perfil y tus datos</p>
                </div>
                <!-- NAV ajustes  -->
                <div class="flex gap-8 border-b border-border mb-12">
                    <!-- PERFIL  -->
                    <button @click="tabActiva = 'perfil'" 
                        :class="[
                            'flex items-center gap-2 pb-4 text-sm font-bold transition-all border-b-2',
                            tabActiva === 'perfil' 
                            ? 'text-[#a855f7] border-[#a855f7]' 
                            : 'text-zinc-500 border-transparent hover:text-zinc-300'
                        ]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user" 
                        aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle></svg>Perfil
                    </button>
                    <!-- SEGURIDAD  -->
                     <button @click="tabActiva = 'seguridad'"
                        :class="[
                            'flex items-center gap-2 pb-4 text-sm font-bold transition-all border-b-2',
                            tabActiva === 'seguridad' 
                            ? 'text-[#a855f7] border-[#a855f7]' 
                            : 'text-zinc-500 border-transparent hover:text-zinc-300'
                        ]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                        stroke-linejoin="round" class="lucide lucide-shield" aria-hidden="true">
                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>
                        Seguridad
                     </button>
                </div>
                <!-- OPCION DE NAV  -->
                <transition name="fade" mode="out-in">
                    <component :is="tabs[tabActiva]" />
                </transition>
            </div>
        </div>
    </main>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

<script setup>
import { ref } from 'vue';
import AjustesPerfil from './AjustesPerfil.vue';
import AjustesSeguridad from './AjustesSeguridad.vue';

const tabActiva = ref('perfil');

const tabs = {
    perfil: AjustesPerfil,
    seguridad: AjustesSeguridad
};
</script>