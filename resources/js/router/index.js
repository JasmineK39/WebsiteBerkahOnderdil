import { createRouter, createWebHistory } from 'vue-router';
import { adminRoutes } from './admin';

import MainLayout from '../components/layouts/MainLayout.vue';
import AuthLayout from '../components/layouts/AuthLayout.vue';

import Home from '../pages/Home.vue';
import Catalog from '../pages/Catalog.vue';
import Checkout from '../pages/Checkout.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import Request from '../pages/Request.vue';
import VerifyOtpView from '../pages/Auth/VerifyOtpView.vue';
import Cart from '../pages/Cart.vue';
import LoginView from '../pages/Auth/LoginView.vue';
import RegisterView from '../pages/Auth/RegisterView.vue';
import ResetPassword from '../pages/Auth/ResetPassword.vue';
import ForgotPassword from '../pages/Auth/ForgotPassword.vue';
import CheckoutConfirm from '../pages/CheckoutConfirm.vue';
import ReviewStore from '../pages/ReviewStore.vue';
import OrderHistory from '../pages/OrderHistory.vue';



const routes = [
  { path: '/',  component: MainLayout,
     children: [
      { path: '', component: Home },
      { path: 'catalog', component: Catalog },
      { path: 'catalog/:carId', name: 'catalog-car', component: Catalog },
      { path: 'checkout', component: Checkout,meta: { requiresAuth: true }},
      { path: 'product/:id', component: ProductDetail },
      { path: 'request', name: 'request', component: Request,meta: { requiresAuth: true }},
      { path: 'about', name: 'about', component: () => import('../pages/About.vue'), meta: { title: 'Tentang Kami' }},
      { path: 'help', name: 'help', component: () => import('../pages/Help.vue'), meta: { title: 'Help/FAQ' }},
      { path: 'cart', component: Cart,meta: { requiresAuth: true }},
      { path: '/checkout-confirm/:id', name: 'CheckoutConfirm', component: CheckoutConfirm, props: true, },
      { path: '/review/:id', name: 'ReviewStore', component: ReviewStore, props: true },
      { path: '/orders', name: 'OrderHistory', component: OrderHistory, meta: { requiresAuth: true }},

      ]
  },
  { path: '/auth', component: AuthLayout,
      children: [
      { path: 'login', name: 'login', component: LoginView},
      { path: 'register', name: 'register', component: RegisterView},
      { path: 'forgot-password', name: 'forgot-password', component: ForgotPassword },
      { path: 'verify-otp', name: 'verify-otp', component: VerifyOtpView },
      { path: 'forgot-password', name: 'forgot-password', component: ForgotPassword },
      { path: 'reset-password', name: 'reset-password', component: ResetPassword },
      ]
    },
   ...adminRoutes,
];

const router = createRouter({
  history: createWebHistory(),
  routes,

  scrollBehavior(to, from, savedPosition) {
    // Jika posisi sebelumnya (tombol Back)
    if (savedPosition) {
      return savedPosition;
    }

    // Jika ada hash (#lokasi-toko)
    if (to.hash) {
      return {
        el: to.hash,
        behavior: "smooth",
      };
    }

    // Default: scroll ke atas
    return { top: 0, left: 0, behavior: "smooth" };
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

   // Jika user login tapi belum verifikasi OTP
  if (token && user && user.status === 'verify') {
    if (to.path !== '/auth/verify-otp') {
      return next('/auth/verify-otp'); // redirect ke halaman OTP
    }
  }

  if (requiresAuth && (!token || !user)) {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    next('/auth/login');
  } else if (requiresAdmin && user?.role !== 'admin') {
    next('/');
  } else if ((to.path === '/auth/login' || to.path === '/auth/register') && token && user && user.status === 'active') {
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

