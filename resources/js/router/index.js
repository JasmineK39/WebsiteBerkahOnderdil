import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Catalog from '../pages/Catalog.vue';
import Checkout from '../pages/Checkout.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import Request from '../pages/Request.vue';
<<<<<<< HEAD
import AdminDashboard from '../pages/Admin/Dashboard.vue';
=======
import Cart from '../pages/Cart.vue';
>>>>>>> 05db3dd36ca7b9f8d4cbe3a7310b28e49f268c7d

const routes = [
  { path: '/', component: Home },
  { path: '/catalog', component: Catalog },
  { path: '/checkout', component: Checkout },
  { path: '/product/:id', component: ProductDetail },
  { path: '/request', name: 'request', component: Request },
<<<<<<< HEAD
  { path: '/admin', component: AdminDashboard }, // ✅ route baru admin
=======
  { path: '/cart', component: Cart },
>>>>>>> 05db3dd36ca7b9f8d4cbe3a7310b28e49f268c7d
];

export default createRouter({
  history: createWebHistory(),
  routes,
});

