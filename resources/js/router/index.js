import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Login from '../components/Login.vue';
import Admin from '../components/Admin.vue';
import Profile from '../components/Profile.vue';
import Schedule from '../components/Schedule.vue';
import Services from '../components/Services.vue';
import About from '../components/About.vue';
import Contacts from '../components/Contacts.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/schedule', component: Schedule },
  { path: '/services', component: Services },
  { path: '/about', component: About },
  { path: '/contacts', component: Contacts },
  { path: '/admin', component: Admin, meta: { requiresAuth: true, admin: true } },
  { path: '/profile', component: Profile, meta: { requiresAuth: true } }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token');

  if (to.meta.requiresAuth && !token) {
    next('/login');
    return;
  }

  if (to.meta.admin) {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/user', {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });
      
      if (!response.ok) {
        next('/login');
        return;
      }
      
      const user = await response.json();
      if (!user.is_admin) {
        next('/');
        return;
      }
    } catch {
      next('/login');
      return;
    }
  }

  next();
});

export default router;