import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '@/services/api';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const isLoggedIn = ref(false);

  const loadUser = async () => {
    try {
      const { data } = await api.get('/user');
      user.value = data;
      isLoggedIn.value = true;
    } catch {
      user.value = null;
      isLoggedIn.value = false;
    }
  };

  const login = async (credentials) => {
    const { data } = await api.post('/login', credentials);
    localStorage.setItem('token', data.token);
    api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
    await loadUser();
  };

  const logout = () => {
    localStorage.removeItem('token');
    delete api.defaults.headers.common['Authorization'];
    user.value = null;
    isLoggedIn.value = false;
  };

  return { user, isLoggedIn, loadUser, login, logout };
});