import AdminLayout from '@/components/Admin/AdminLayout.vue';
import AdminDashboard from '@/pages/Admin/Index.vue';

import ModelMobilIndex from '@/pages/Admin/ModelMobil/Index.vue';
import ModelMobilCreate from '@/pages/Admin/ModelMobil/Create.vue';
import ModelMobilEdit from '@/pages/Admin/ModelMobil/Edit.vue';

import SparepartIndex from '@/pages/Admin/Sparepart/Index.vue';
import SparepartCreate from '@/pages/Admin/Sparepart/Create.vue';
import SparepartEdit from '@/pages/Admin/Sparepart/Edit.vue';

export const adminRoutes = [
  {
    path: '/admin',
    component: AdminLayout, // induk layout
    meta: { requiresAuth: true, isAdmin: true },
    children: [
      { 
        path: '', 
        name: 'AdminDashboard', 
        component: AdminDashboard },

      // === Model Mobil ===
      {
        path: 'models',
        name: 'AdminModelIndex',
        component: ModelMobilIndex,
      },
      {
        path: 'models/create',
        name: 'AdminModelCreate',
        component: ModelMobilCreate,
      },
      {
        path: 'models/:id/edit',
        name: 'AdminModelEdit',
        component: ModelMobilEdit,
      },

      // === Sparepart ===
      {
        path: 'spareparts',
        name: 'AdminSparepartIndex',
        component: SparepartIndex,
      },
      {
        path: 'spareparts/create',
        name: 'AdminSparepartCreate',
        component: SparepartCreate,
      },
      {
        path: 'spareparts/:id/edit',
        name: 'AdminSparepartEdit',
        component: SparepartEdit,
      },
       { 
        path: ':pathMatch(.*)*', 
        name: 'AdminNotFound', 
        component: () => import('@/Pages/Admin/AdminNotFound.vue') 
      },
    ],
  },
];
