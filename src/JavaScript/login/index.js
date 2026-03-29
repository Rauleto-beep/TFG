import { createRouter, createWebHashHistory } from 'vue-router';

import LoginView from '../../../assets/components/LoginView.vue'; 
import RegistroView from '../../../assets/components/RegisterView.vue';
import TareasView from '../../../assets/components/VistaGestionTareas.vue';
import ChatView from '../../../assets/components/VistaChat.vue';

//rutas que redirije automaticamente al usuario
const routes = [
  { path: '/', 
    redirect: () => localStorage.getItem('jwt_token') ? '/tareas' : '/login'},
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
  },
  {path: '/VistaChat', name: 'chat', component: ChatView}
];

const router = createRouter({
    // 2. Aplicamos el modo Hash aquí
    history: createWebHashHistory(),
    routes
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('jwt_token');

  // 1. SI EL USUARIO NO TIENE TOKEN:
  // Solo lo mandamos al login si intenta entrar a una ruta privada (como /tareas)
  if (!token && to.name === 'tareas') {
    return next({ name: 'login' });
  }

  // 2. SI EL USUARIO YA TIENE TOKEN:
  // Solo lo redirigimos a /tareas si intenta entrar a 'login' o 'registro' por error.
  // Si intenta ir a cualquier OTRA ruta, lo dejamos pasar libremente.
  if (token && (to.name === 'login' || to.name === 'registro')) {
    return next({ name: 'tareas' });
  }

  // 3. EN CUALQUIER OTRO CASO:
  // Libertad total para navegar.
  next();
});

export default router;