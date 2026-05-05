<template>
  <div class="min-h-[60vh] flex items-center justify-center py-20">
    <div class="bg-white/5 p-10 rounded-3xl border border-white/5 w-full max-w-sm backdrop-blur-md">
      <div class="flex mb-10 border-b border-white/10">
        <button @click="mode = 'login'" :class="mode === 'login' ? 'text-white' : 'text-gray-500'" class="flex-1 pb-4 font-bold">ВХОД</button>
        <button @click="mode = 'register'" :class="mode === 'register' ? 'text-white' : 'text-gray-500'" class="flex-1 pb-4 font-bold">РЕГИСТРАЦИЯ</button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div v-if="mode === 'register'">
          <input v-model="form.name" placeholder="Имя" class="w-full bg-blue-950 p-3 rounded">
        </div>
        <input v-model="form.email" type="email" placeholder="Email" class="w-full bg-blue-950 p-3 rounded">
        <input v-model="form.password" type="password" placeholder="Пароль" class="w-full bg-blue-950 p-3 rounded">
        <button class="w-full bg-blue-600 py-3 rounded font-bold uppercase tracking-widest text-sm">
          {{ mode === 'login' ? 'Войти' : 'Создать аккаунт' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../services/api';
import { useRouter } from 'vue-router';

const router = useRouter();
const mode = ref('login');
const form = ref({ name: '', email: '', password: '' });

const handleSubmit = async () => {
  try {
    if (mode.value === 'login') {
      const { data } = await api.post('/login', form.value);
      localStorage.setItem('token', data.token);
      localStorage.setItem('user', JSON.stringify(data.user));
      data.user.is_admin ? router.push('/admin') : router.push('/profile');
    } else {
      await api.post('/register', form.value);
      alert('Успешно! Теперь войдите.');
      mode.value = 'login';
    }
  } catch (e) {
    alert('Ошибка: ' + (e.response?.data?.message || 'Неверные данные'));
  }
};
</script>