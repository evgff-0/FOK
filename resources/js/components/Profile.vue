<template>
  <div class="container mx-auto px-6 py-12 text-white">
    <h2 class="text-3xl font-bold mb-8">Мой кабинет</h2>
    
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
      <p class="mt-4 text-gray-400">Загрузка...</p>
    </div>

    <div v-else-if="!auth.user" class="text-center py-12">
      <p class="text-gray-400">Не удалось загрузить данные профиля.</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="bg-white/5 p-6 rounded-2xl">
        <h3 class="text-xl font-bold mb-4">Данные</h3>
        <p>Имя: {{ auth.user.name }}</p>
        <p>Email: {{ auth.user.email }}</p>
        <button @click="handleLogout" class="mt-6 bg-red-600 px-4 py-2 rounded text-sm hover:bg-red-700 transition">
          Выйти
        </button>
      </div>

      <div class="bg-white/5 p-6 rounded-2xl">
        <h3 class="text-xl font-bold mb-4">Мои записи</h3>
        
        <div v-if="bookings.length === 0" class="text-gray-400">
          У вас пока нет записей.
        </div>

        <ul v-else class="space-y-3">
          <li v-for="b in bookings" :key="b.id" class="flex justify-between items-center bg-blue-950 p-3 rounded">
            <div>
              <p class="font-medium">{{ b.schedule?.activity || 'Занятие' }}</p>
              <p class="text-sm text-gray-400">
                {{ b.schedule?.day_of_week || '' }}, {{ b.schedule?.time || '' }}
              </p>
            </div>
            <button @click="cancelBooking(b.id)" class="text-red-400 hover:text-white transition text-sm">
              Отмена
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();
const router = useRouter();
const bookings = ref([]);
const loading = ref(true);

const loadBookings = async () => {
  try {
    const { data } = await api.get('/bookings');
    bookings.value = Array.isArray(data) ? data : [];
  } catch {
    bookings.value = [];
  } finally {
    loading.value = false;
  }
};

const cancelBooking = async (id) => {
  try {
    await api.delete(`/bookings/${id}`);
    bookings.value = bookings.value.filter(b => b.id !== id);
  } catch (error) {
    console.error('Ошибка при отмене записи:', error);
  }
};

const handleLogout = async () => {
  await auth.logout();
  router.push('/');
};


onMounted(async () => {
  if (!auth.user) {
    try {
      await auth.fetchUser();
    } catch {
      router.push('/login');
      return;
    }
  }
  await loadBookings();
});
</script>