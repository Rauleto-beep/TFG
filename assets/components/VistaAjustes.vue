<template>
    <div id="root" class="flex w-full min-h-screen bg-bg text-zinc-100 selection:bg-brand selection:text-white">
        <Tab/>
        <Ajustes class="flex-1 h-full"/>
    </div>
</template>

<script setup>
    import Tab from './Tab.vue'
    import Ajustes from './Ajustes.vue';
</script>