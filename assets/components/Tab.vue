<template>
    <div class="w-64 bg-sidebar border-r border-border h-screen flex flex-col p-6 sticky top-0">
        <div class="flex items-center gap-3 mb-10">
            <!-- Imagen aplicacion -->
            <div class="w-10 h-10 bg-brand rounded-xl flex items-center justify-center shadow-lg shadow-brand/20">
                <img class="img-logo" src="../imagenes/logo_bifrost.svg">
            </div>
            <!-- Titulo App  -->
            <div>
                <h1 class="font-bold text-lg tracking-tight">BIFROST</h1>
                <p class="text-[10px] text-brand font-semibold tracking-[0.2em] uppercase">Gestor de tareas</p>
            </div>
        </div>
        <!-- NAV -->
         <nav class="flex-1 space-y-1">
            <button class="sidebar-item w-full active">
                <figure class="figures-nav">
                    <img class="img-simbolos-nav" src="../imagenes/nav/simbolo_dashboard_nav.svg">
                    <figcaption class="font-medium">Dashboard</figcaption>
                </figure>
            </button>
            <button class="sidebar-item w-full">
                <figure class="figures-nav">
                    <img class="img-simbolos-nav" src="../imagenes/nav/simbolo_tareas_nav.svg">
                    <figcaption class="font-medium">Tareas</figcaption>
                </figure>
            </button>
            <button class="sidebar-item w-full">
                <figure class="figures-nav">
                    <img class="img-simbolos-nav" src="../imagenes/nav/simbolo_calendario_nav.svg">
                    <figcaption class="font-medium">Calendario</figcaption>
                </figure>
            </button>
            <button class="sidebar-item w-full">
                <figure class="figures-nav">
                    <img class="img-simbolos-nav" src="../imagenes/nav/simbolo_chat_nav.svg">
                    <figcaption class="font-medium">Chat</figcaption>
                </figure>
            </button>
            <button class="sidebar-item w-full">
                <figure class="figures-nav">
                    <img class="img-simbolos-nav" src="../imagenes/nav/simbolo_ajustes_nav.svg">
                    <figcaption class="font-medium">Ajustes</figcaption>
                </figure>
            </button>
         </nav>
         <div class="mt-auto pt-6 border-t border-border space-y-4">
            <button class="w-full btn-primary py-3 shadow-lg shadow-brand/20">
                <figure>
                    <img src="">
                    <figcaption class="font-medium">Nueva Tarea</figcaption>
                </figure>
            </button>
            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition-colors cursor-pointer group">
                <img src="">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{userName}}</p>
                </div>
                <img class="img-simbolos-nav" src="../imagenes/nav/simbolo_ajustes_nav.svg">
            </div>
         </div>
    </div>
</template>

<script setup>
    let userName = localStorage.getItem("user_nombre");
</script>