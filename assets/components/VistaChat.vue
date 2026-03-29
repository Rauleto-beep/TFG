<template>
    <div id="root" class="flex w-full min-h-screen bg-bg text-zinc-100 selection:bg-brand selection:text-white">
        <Tab/>
        <Chat class="flex-1"/>
    </div>
</template>

<script setup>
import Tab from './Tab.vue'
import Chat from './Chat.vue';
</script>