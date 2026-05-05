<template>
  <div class="container mx-auto px-6 py-12">
    <h2 class="text-4xl font-bold mb-12">Расписание</h2>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white/10 p-8 rounded-3xl border border-white/20 w-full max-w-sm backdrop-blur-md">
        <h3 class="text-2xl font-bold mb-6 text-white text-center">📱 Оставьте номер</h3>
        <p class="text-blue-200 mb-6 text-center">Мы с вами свяжемся для подтверждения записи</p>
        <input v-model="phone" type="tel" placeholder="+7 (___) ___-__-__" 
               class="w-full bg-blue-950 p-4 rounded-xl border border-white/10 text-white mb-6 text-lg">
        <div class="flex gap-3">
          <button @click="showModal = false" class="flex-1 bg-gray-600 py-3 rounded-xl font-bold text-white">Отмена</button>
          <button @click="confirmBooking" class="flex-1 bg-green-600 py-3 rounded-xl font-bold text-white">Записаться</button>
        </div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full bg-white/5 rounded-xl border border-white/5">
        <thead>
          <tr class="border-b border-white/10">
            <th class="p-4 text-left font-bold text-white">День недели</th>
            <th class="p-4 text-left font-bold text-white">Время</th>
            <th class="p-4 text-left font-bold text-white">Занятие</th>
            <th class="p-4 text-left font-bold text-white">Тренер</th>
            <th class="p-4 text-left font-bold text-white">Записаться</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="schedule in schedules" :key="schedule.id" class="border-b border-white/5 hover:bg-white/10">
            <td class="p-4 font-medium text-blue-200">{{ schedule.day_of_week }}</td>
            <td class="p-4 font-bold text-white">{{ schedule.time }}</td>
            <td class="p-4 text-blue-200">{{ schedule.activity }}</td>
            <td class="p-4 text-blue-400">{{ schedule.trainer }}</td>
            <td class="p-4">
              <button @click="openModal(schedule.id)" 
                      class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-500">
                Записаться
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';

const schedules = ref([]);
const showModal = ref(false);
const currentScheduleId = ref(null);
const phone = ref('');

onMounted(async () => {
  const { data } = await api.get('/schedules');
  schedules.value = data;
});

const openModal = (id) => {
  currentScheduleId.value = id;
  phone.value = '';
  showModal.value = true;
};

const confirmBooking = () => {
  alert(`Записались на занятие!\nТелефон: ${phone.value}`);
  showModal.value = false;
};
</script>