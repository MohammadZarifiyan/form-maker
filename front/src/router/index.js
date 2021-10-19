import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    component: () => import('@/views/Index.vue'),
  },
  {
    path: '/account/dashboards',
    component: () => import('@/views/account/Dashboards.vue'),
  },
  {
    path: '/account/forms',
    component: () => import('@/views/account/Forms.vue'),
  },
  {
    path: '/account/connections',
    component: () => import('@/views/account/Connections.vue'),
  },
  {
    path: '/account/queries',
    component: () => import('@/views/account/Queries.vue'),
  },
  {
    path: '/account/projects',
    component: () => import('@/views/account/Projects.vue'),
  },
  {
    path: '/account/forms/:form_id',
    component: () => import('@/views/account/ShowForm.vue'),
  },
  {
    path: '/account/dashboards/:dashboard_id',
    component: () => import('@/views/account/ShowDashboard.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router
