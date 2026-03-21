import './styles/app.css'; // Esto le dice a Webpack que procese Tailwind

import { createApp } from 'vue';
import App from './components/App.vue'; // Importamos el componente
import router from '../src/JavaScript/login/index.js';

createApp(App)
    .use(router) // ESTA LÍNEA ES LA QUE ACTIVA TUS RUTAS
    .mount('#app');