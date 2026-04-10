<template>
  <main class="flex overflow-hidden bg-bg h-screen w-full">
    <aside class="w-80 border-r border-border flex flex-col bg-sidebar/50 backdrop-blur-md">
      <div class="p-6 border-b border-border flex items-center justify-between">
        <h2 class="text-xl font-bold tracking-tight">Chats</h2>
        <button @click="mostrarInput = !mostrarInput" 
                class="p-2 hover:bg-white/10 rounded-full transition-all active:scale-95 text-zinc-400 hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
        </button>
      </div>

      <div v-if="mostrarInput" class="p-4 animate-in fade-in slide-in-from-top-2">
        <div class="relative">
          <input 
            id = "inputNombreGrupo"
            @keyup.enter="confirmarCreacionGrupo" 
            v-model="nombreNuevoGrupo"
            placeholder="Nombre del grupo..." 
            class="w-full bg-black/20 border border-border rounded-lg py-2 px-4 text-sm focus:ring-1 focus:ring-brand outline-none" 
            type="text"
            autofocus
          >
        </div>
      </div>
      
      <!-- TAB grupos  -->
      <div class="flex-1 overflow-y-auto p-2 space-y-1">
        <div v-for="grupo in grupos" :key="grupo.id"  
             @click="seleccionarGrupo(grupo.id, grupo.nombre_grupo)" 
             :class="['p-3 flex items-center gap-3 rounded-xl cursor-pointer transition-all group', 
                      grupoActivoId === grupo.id ? 'bg-brand/10 border border-brand/20' : 'hover:bg-white/5 border border-transparent']">
          
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand/40 to-indigo-600/40 flex items-center justify-center font-bold text-xs uppercase">
            {{ grupo.nombre_grupo?.substring(0,2) }}
          </div>
          <div class="flex-1">
            <h4 :class="['text-sm font-semibold truncate', grupoActivoId === grupo.id ? 'text-brand' : 'text-zinc-300 group-hover:text-white']">
              {{ grupo.nombre_grupo }}
            </h4>
          </div>
          <div class="dropdown">
            <button class="dropbtn" @click.stop="toggleMenu(grupo.id)">⋮</button>
            <div :class="['dropdown-content', { 'show': menuAbiertoId === grupo.id }]">
              <a href="#" @click.prevent="eliminarElemento(grupo.id)">Eliminar</a>
            </div>
          </div>
        </div>
      </div>

      <div class="p-4 border-t border-border bg-black/10">
        <div class="flex items-center gap-3 p-2 rounded-xl bg-white/5 border border-white/5">
          <div class="w-8 h-8 rounded-full bg-zinc-700 flex items-center justify-center text-[10px] font-bold">
            {{ userName?.substring(0,1).toUpperCase() }}
          </div>
          <p class="text-xs font-bold text-zinc-400">{{ userName }}</p>
        </div>
      </div>
    </aside>

    <section class="flex-1 flex flex-col relative overflow-hidden">
      
      <header v-if="nombreGrupo" class="h-16 border-b border-border flex items-center justify-between px-6 bg-bg/80 backdrop-blur-md z-20">
        <div class="flex items-center gap-3">
          <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
          <h3 class="font-bold text-sm">{{ nombreGrupo }}</h3>
        </div>
        <button class="p-2 hover:bg-white/5 rounded-lg text-zinc-400 hover:text-brand transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" x2="19" y1="8" y2="14"></line><line x1="22" x2="16" y1="11" y2="11"></line></svg>
        </button>
      </header>

      <div v-if="nombreGrupo" class="flex-1 overflow-y-auto p-6 space-y-6 flex flex-col" ref="chatContainer">
        <div v-for="m in mensajes" :key="m.id" 
             :class="['flex flex-col max-w-[80%]', m.autor === userName ? 'ml-auto items-end' : 'mr-auto items-start']">
          
          <span class="text-[10px] font-medium text-zinc-500 mb-1 px-1 italic">
            {{ m.autor === userName ? 'Tú' : m.autor }}
          </span>
          
          <div :class="['px-4 py-2 rounded-2xl text-sm shadow-sm', 
                       m.autor === userName ? 'bg-brand text-white rounded-tr-none' : 'bg-sidebar border border-border text-zinc-200 rounded-tl-none']">
            {{ descifrar(m.mensaje) }}
          </div>
        </div>
        <div ref="scrollEnd"></div>
      </div>

      <div v-else class="flex-1 flex flex-col items-center justify-center text-zinc-500 p-10 text-center">
        <div class="w-16 h-16 bg-white/5 rounded-3xl flex items-center justify-center mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg>
        </div>
        <h3 class="text-white font-medium">Selecciona un chat</h3>
        <p class="text-sm max-w-xs mt-1">Elige un grupo de la izquierda o crea uno nuevo para empezar a hablar.</p>
      </div>

      <footer v-if="nombreGrupo" class="p-4 bg-bg border-t border-border">
        <form @submit.prevent="enviarMensajeEvent" class="relative flex items-center mx-auto">
          <input 
            v-model="nuevoMensaje" 
            placeholder="Escribe un mensaje..." 
            class="w-full bg-sidebar/50 border border-border rounded-2xl py-3 pl-4 pr-14 text-white placeholder:text-zinc-500 focus:outline-none focus:ring-1 focus:ring-brand/50 transition-all"
          />
          <button type="submit" 
                  :disabled="!nuevoMensaje.trim()"
                  class="absolute right-2 p-2 bg-brand rounded-xl text-white hover:opacity-90 transition-all disabled:opacity-50 disabled:grayscale">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
          </button>
        </form>
      </footer>
    </section>
  </main>
</template>
<style>
.dropdown {
  position: relative;
  display: inline-block;
}

.dropbtn {
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  background-color: #2d2d44;
  min-width: 100px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  border-radius: 4px;
}

.dropdown-content a {
  color: white;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover { background-color: #3e3e5e; }

.show { display: block; }
</style>
<script setup>
    import { onUnmounted, ref, onMounted, nextTick, watch } from 'vue';
    import { creacionGrupo } from '../../src/JavaScript/Chat/crearGrupo.js';
    import { obtenerGruposUsuario } from '../../src/JavaScript/Chat/obtenerGrupos.js';
    import { enviarMensajesGrupos } from '../../src/JavaScript/Chat/chat.js';
    import { eliminacionGrupo } from '../../src/JavaScript/Chat/eliminarGrupo.js';
    import { cifrar, descifrar } from '../../src/JavaScript/Chat/cryptoService.js';

    const { crearGrupo } = creacionGrupo();
    const { grupos, infoGrupos } = obtenerGruposUsuario();
    const { nuevoMensaje, mensajes, enviarMensaje, conectarMercure, eventSource } = enviarMensajesGrupos();
    const { eliminarGrupo } = eliminacionGrupo();

    const mostrarInput = ref(false);
    const nombreNuevoGrupo = ref(''); // Vinculado al v-model del input de creación
    const grupoActivoId = ref(null);
    const nombreGrupo = ref(null);
    const scrollEnd = ref(null);
    const menuAbiertoId = ref(null);

    let userName = localStorage.getItem("user_nombre");

    const confirmarCreacionGrupo = async () => {
        if (!nombreNuevoGrupo.value.trim()) return;
        mostrarInput.value = false;
        await crearGrupo(nombreNuevoGrupo.value);
        nombreNuevoGrupo.value = '';
        await infoGrupos();
    };

    const seleccionarGrupo = async (id, nombre) => {
        grupoActivoId.value = id;
        nombreGrupo.value = nombre;
        await conectarMercure(id);
    };

    const enviarMensajeEvent = async () => {
        if (grupoActivoId.value && nuevoMensaje.value.trim()) {
            await enviarMensaje(grupoActivoId.value);
            scrollToBottom();
        }
    };
  //Menu 3 puntos
    const toggleMenu = (id) => {
      if (menuAbiertoId.value === id) {
        menuAbiertoId.value = null;
      } else {
        menuAbiertoId.value = id;
      }
    };

    const eliminarElemento = async (id)=>{
      await eliminarGrupo(id);

      // Elimino la pantalla de un chat inexistente
      if (grupoActivoId.value === id) {
        grupoActivoId.value = null;
        nombreGrupo.value = null; 
    
        // Si hay una conexión Mercure activa para ese grupo,la  cierro
        if (eventSource) {
          eventSource.close();
        }
      }

      menuAbiertoId.value = null;
      await infoGrupos();
    }

    // Función para bajar el scroll automáticamente
    const scrollToBottom = async () => {
        await nextTick();
        if (scrollEnd.value) {
            scrollEnd.value.scrollIntoView({ behavior: 'smooth' });
        }
    };

    watch(mensajes, () => {
        scrollToBottom();
    }, { deep: true });

    onMounted(async () => {
        await infoGrupos();

        // Cerrar el menú si el usuario hace clic fuera de él
        window.onclick = function(event) {
          if (!event.target.matches('.dropbtn')) {
            document.querySelectorAll('.dropdown-content').forEach(menu => {
            menu.classList.remove('show');
          });
        }
      }
    });

    onUnmounted(() => {
        if (eventSource) eventSource.close();
    });
</script>