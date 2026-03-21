<template>
    <div id="root" class="flex min-h-screen bg-bg text-zinc-100 selection:bg-brand selection:text-white">
        <Tab/>
        <CrudTareas/>
    </div>
</template>

<script setup>
import Tab from './Tab.vue'
import CrudTareas from './CRUDtareas.vue';
</script>