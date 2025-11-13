import { createRouter, createWebHistory } from 'vue-router';
import { adminRoutes } from './admin';
import Home from '../pages/Home.vue';
import Catalog from '../pages/Catalog.vue';
import Checkout from '../pages/Checkout.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import Request from '../pages/Request.vue';

import Cart from '../pages/Cart.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/catalog', component: Catalog },
  { path: '/catalog/:carId', name: 'catalog-car', component: Catalog },
  { path: '/checkout', component: Checkout },
  { path: '/product/:id', component: ProductDetail },
  { path: '/request', name: 'request', component: Request },
  { path: '/cart', component: Cart },

   ...adminRoutes,
];

const router = createRouter({
  history: createWebHistory(),
  routes,

  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition; 
    } else {
      return { top: 0, left: 0, behavior: 'smooth' }; // setiap pindah halaman, mulai dari atas
    }
  },
});


router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem('user')); // misal ambil dari localStorage
  
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some(record => record.meta.isAdmin);

  if (requiresAuth && !user) {
    next('/login');
  } else if (requiresAdmin && user?.role !== 'admin') {
    next('/');
  } else {
    next();
  }
});
router.afterEach((to) => {
  document.title = to.meta.title ? `Berkah Onderdil | ${to.meta.title}` : 'Berkah Onderdil';
});

export default router;