import { createRouter, createWebHistory } from 'vue-router';
import { adminRoutes } from './admin';

import MainLayout from '../components/layouts/MainLayout.vue';
import AuthLayout from '../components/layouts/AuthLayout.vue';

import Home from '../pages/Home.vue';
import Catalog from '../pages/Catalog.vue';
import Checkout from '../pages/Checkout.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import Request from '../pages/Request.vue';
import Cart from '../pages/Cart.vue';
import LoginView from '../pages/Auth/LoginView.vue';
import RegisterView from '../pages/Auth/RegisterView.vue';

const routes = [
  { path: '/',  component: MainLayout,
     children: [
      { path: '', component: Home },
      { path: 'catalog', component: Catalog },
      { path: 'catalog/:carId', name: 'catalog-car', component: Catalog },
      { path: 'checkout', component: Checkout,meta: { requiresAuth: true }},
      { path: 'product/:id', component: ProductDetail },
      { path: 'request', name: 'request', component: Request },
      { path: 'cart', component: Cart,meta: { requiresAuth: true }},
      ]
  },
  { path: '/', component: AuthLayout,
      children: [
      { path: 'login', name: 'login', component: LoginView},
      { path: 'register', name: 'register', component: RegisterView},
      { path: 'forgot-password', component: { template: '<h1>Halaman Lupa Password Belum Dibuat</h1>' }},
      ]
    },
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
  const token = localStorage.getItem('token');
  const userString = localStorage.getItem('user');
  let user = null;

  try {
    user = JSON.parse(userString);
  } catch (e) {
    user = null;
  }

  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some(record => record.meta.isAdmin);

  if (requiresAuth && (!token || !user)) {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    next('/login');
  } else if (requiresAdmin && user?.role !== 'admin') {
    next('/');
  } else if ((to.path === '/login' || to.path === '/register') && token && user) {
    if (user.role === 'admin') {
        next('/admin'); // Admin ke Dashboard Admin
    } else {
        next('/catalog'); // Customer ke Katalog
    }
  }else {
    next();
  }
});
router.afterEach((to) => {
  document.title = to.meta.title ? `Berkah Onderdil | ${to.meta.title}` : 'Berkah Onderdil';
});

export default router;