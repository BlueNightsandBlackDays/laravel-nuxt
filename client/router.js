import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const page = path => () => import(`~/pages/${path}`).then(m => m.default || m)

const routes = [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/home', name: 'home', component: page('home.vue') },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },

  // Users route.
  { path: '/users', name: 'users-list', component: page('users/list.vue') },
  { path: '/users/create', name: 'users-create', component: page('users/create.vue') },
  { path: '/users/:id?', name: 'users-view', component: page('users/_view.vue') },
  { path: '/users/:id?/update', name: 'users-update', component: page('users/edit.vue') },

  // Roles route.
  { path: '/roles', name: 'roles-list', component: page('role/list.vue') },
  { path: '/roles/create', name: 'roles-create', component: page('role/create.vue') },
  { path: '/roles/:id?', name: 'roles-view', component: page('role/_view.vue') },
  { path: '/roles/:id?/update', name: 'roles-update', component: page('role/edit.vue') },

  // Attendances route.
  { path: '/attendances', name: 'attendances-list', component: page('attendance/list.vue') },
  { path: '/attendances/create', name: 'attendances-create', component: page('attendance/create.vue') },
  { path: '/attendances/:id?', name: 'attendances-view', component: page('attendance/_view.vue') },
  { path: '/attendances/:id?/update', name: 'attendances-update', component: page('attendance/edit.vue') }
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
