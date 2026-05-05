<template>
  <form @submit.prevent="register" class="max-w-md mx-auto p-8 bg-white/5 rounded-xl">
    <h2 class="text-3xl font-bold mb-8 text-center">Регистрация</h2>
    <input v-model="form.name" type="text" placeholder="Имя" class="w-full bg-blue-950 p-3 rounded mb-4" required>
    <input v-model="form.email" type="email" placeholder="Email" class="w-full bg-blue-950 p-3 rounded mb-4" required>
    <input v-model="form.password" type="password" placeholder="Пароль" class="w-full bg-blue-950 p-3 rounded mb-4" required>
    <button type="submit" class="w-full bg-green-600 text-white p-3 rounded font-bold hover:bg-green-700">
      Зарегистрироваться
    </button>
  </form>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/api';

const router = useRouter();
const form = ref({ name: '', email: '', password: '' });

const register = async () => {
  try {
    const { data } = await api.post('/register', form.value);
    localStorage.setItem('token', data.token);
    router.push('/profile');
  } catch (e) {
    alert('Ошибка регистрации');
  }
};
</script>