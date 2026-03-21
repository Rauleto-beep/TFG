import { createRouter, createWebHashHistory } from 'vue-router';

import LoginView from '../../../assets/components/LoginView.vue'; 
import RegistroView from '../../../assets/components/RegisterView.vue';
import TareasView from '../../../assets/components/VistaGestionTareas.vue';

//rutas que redirije automaticamente al usuario
const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/registro', name: 'registro', component: RegistroView },
  { 
    path: '/tareas', 
    name: 'tareas', 
    component: TareasView,
    beforeEnter: (to, from, next) => {
      if (!localStorage.getItem('jwt_token')) next('/login');
      else next();
    }
  }
];

const router = createRouter({
    // 2. Aplicamos el modo Hash aquí
    history: createWebHashHistory(),
    routes
});

export default router;