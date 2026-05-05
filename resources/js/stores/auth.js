import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '@/services/api';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const token = ref(localStorage.getItem('token') || null);
  const isLoggedIn = ref(false);

  const initAuth = async () => {
    const savedToken = localStorage.getItem('token');
    if (savedToken) {
      api.defaults.headers.common['Authorization'] = `Bearer ${savedToken}`;
      try {
        const { data } = await api.get('/user');
        user.value = data;
        token.value = savedToken;
        isLoggedIn.value = true;
      } catch {
        localStorage.removeItem('token');
        delete api.defaults.headers.common['Authorization'];
        user.value = null;
        token.value = null;
        isLoggedIn.value = false;
      }
    }
  };

  const login = async (credentials) => {
    const { data } = await api.post('/login', credentials);
    
    localStorage.setItem('token', data.token);
    token.value = data.token;
    api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
    
    const userResponse = await api.get('/user');
    user.value = userResponse.data;
    isLoggedIn.value = true;
    
    return userResponse.data;
  };

  const logout = async () => {
    try {
      await api.post('/logout');
    } catch {
    }
    
    // Очищаем всё
    localStorage.removeItem('token');
    delete api.defaults.headers.common['Authorization'];
    user.value = null;
    token.value = null;
    isLoggedIn.value = false;
  };

  const fetchUser = async () => {
    try {
      const { data } = await api.get('/user');
      user.value = data;
      isLoggedIn.value = true;
    } catch {
      user.value = null;
      isLoggedIn.value = false;
    }
  };

  return { user, token, isLoggedIn, initAuth, login, logout, fetchUser };
});