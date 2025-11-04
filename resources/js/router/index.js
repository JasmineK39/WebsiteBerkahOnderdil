import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Catalog from '../pages/Catalog.vue';
import Checkout from '../pages/Checkout.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import Request from '../pages/Request.vue';
import Cart from '../pages/Cart.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/catalog', component: Catalog },
  { path: '/checkout', component: Checkout },
  { path: '/product/:id', component: ProductDetail },
  { path: '/request', name: 'request', component: Request },
  { path: '/cart', component: Cart },
];

export default createRouter({
  history: createWebHistory(),
  routes,
});