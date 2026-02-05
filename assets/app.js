import { createApp, h } from 'vue';

const app = createApp({
    render() {
        return h('div', { style: 'text-align:center; margin-top:50px; font-family:sans-serif;' }, [
            h('h1', '¡Entorno listo!'),
            h('p', 'Vue.js está funcionando correctamente en el TFG.')
        ]);
    }
});

app.mount('#app');