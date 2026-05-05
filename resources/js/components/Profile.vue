<template>
  <div class="container mx-auto px-6 py-12 text-white">
    <h2 class="text-3xl font-bold mb-8">Мой кабинет</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="bg-white/5 p-6 rounded-2xl">
        <h3 class="text-xl font-bold mb-4">Данные</h3>
        <p>Имя: {{ user?.name }}</p>
        <p>Email: {{ user?.email }}</p>
        <button @click="logout" class="mt-6 bg-red-600 px-4 py-2 rounded text-sm">Выйти</button>
      </div>

      <div class="bg-white/5 p-6 rounded-2xl">
        <h3 class="text-xl font-bold mb-4">Мои записи</h3>
        
        <div v-if="bookings.length === 0" class="text-gray-400">
          У вас пока нет записей.
        </div>

        <ul v-else class="space-y-3">
          <li v-for="b in bookings" :key="b.id" class="flex justify-between bg-blue-950 p-3 rounded">
            <span>{{ b.activity }} ({{ b.time }})</span>
            <button @click="cancel(b.id)" class="text-red-400 hover:text-white">Отмена</button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const user = JSON.parse(localStorage.getItem('user'));
const bookings = ref([]);

const load = async () => {
  const { data } = await api.get('/bookings');
  bookings.value = Array.isArray(data) ? data : [];
};

const cancel = async (id) => {
  await api.delete(`/bookings/${id}`);
  load();
};

const logout = () => {
  localStorage.clear();
  window.location.href = '/';
};

onMounted(load);
</script>