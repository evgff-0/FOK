<template>
  <div class="container mx-auto px-6 py-12">
    <h2 class="text-4xl font-bold mb-12">Расписание</h2>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-slate-800 p-8 rounded-3xl border border-white/20 w-full max-w-sm backdrop-blur-md shadow-2xl">
        <h3 class="text-2xl font-bold mb-4 text-white text-center">Подтверждение записи</h3>
        <p class="text-blue-200 mb-6 text-center">
          Вы собираетесь записаться на занятие<br>
          <span class="font-bold text-white">{{ selectedActivity }}</span>
        </p>
        <div class="flex gap-3">
          <button @click="showModal = false" class="flex-1 bg-gray-600 hover:bg-gray-500 py-3 rounded-xl font-bold text-white transition">
            Отмена
          </button>
          <button @click="confirmBooking" :disabled="booking" class="flex-1 bg-green-600 hover:bg-green-500 py-3 rounded-xl font-bold text-white disabled:opacity-50 transition">
            {{ booking ? 'Запись...' : 'Подтвердить' }}
          </button>
        </div>
      </div>
    </div>

    <Transition name="slide-fade">
      <div v-if="showSuccess" class="fixed top-6 right-6 z-50 bg-green-600 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-green-400/30 max-w-md">
        <div class="bg-white/20 rounded-full p-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-lg">Запись подтверждена!</p>
          <p class="text-green-100 text-sm">Вы успешно записаны на занятие «{{ lastBookedActivity }}». Вся информация доступна в вашем профиле.</p>
        </div>
        <button @click="showSuccess = false" class="text-green-200 hover:text-white ml-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </Transition>

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
          <tr v-for="schedule in schedules" :key="schedule.id" class="border-b border-white/5 hover:bg-white/10 transition">
            <td class="p-4 font-medium text-blue-200">{{ schedule.day_of_week }}</td>
            <td class="p-4 font-bold text-white">{{ schedule.time }}</td>
            <td class="p-4 text-blue-200">{{ schedule.activity }}</td>
            <td class="p-4 text-blue-400">{{ schedule.trainer }}</td>
            <td class="p-4">
              <button @click="openModal(schedule)" 
                      class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-500 transition">
                Записаться
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="schedules.length === 0 && !loading" class="text-center py-16 text-gray-400">
      <p class="text-xl">Расписание пока не заполнено</p>
      <p class="mt-2">Пожалуйста, загляните позже</p>
    </div>

    <div v-if="loading" class="text-center py-16">
      <div class="inline-block w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
      <p class="mt-4 text-gray-400">Загрузка расписания...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const schedules = ref([]);
const loading = ref(true);

const showModal = ref(false);
const currentSchedule = ref(null);
const booking = ref(false);

const showSuccess = ref(false);
const lastBookedActivity = ref('');

const selectedActivity = ref('');

const loadSchedules = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/schedules');
    schedules.value = Array.isArray(data) ? data : [];
  } catch (error) {
    console.error('Ошибка загрузки расписания:', error);
    schedules.value = [];
  } finally {
    loading.value = false;
  }
};

const openModal = (schedule) => {
  currentSchedule.value = schedule;
  selectedActivity.value = `${schedule.activity} (${schedule.day_of_week}, ${schedule.time})`;
  showModal.value = true;
};

const confirmBooking = async () => {
  if (!currentSchedule.value || booking.value) return;
  
  booking.value = true;
  
  try {
    await api.post(`/schedules/${currentSchedule.value.id}/book`);
    
    lastBookedActivity.value = `${currentSchedule.value.activity} (${currentSchedule.value.day_of_week}, ${currentSchedule.value.time})`;
    
    showModal.value = false;
    
    showSuccess.value = true;
    
    setTimeout(() => {
      showSuccess.value = false;
    }, 4000);
    
  } catch (error) {
    console.error('Ошибка при записи:', error);
    
    if (error.response?.status === 401) {
      alert('Для записи на занятие необходимо войти в систему');
    } else if (error.response?.data?.message) {
      alert(error.response.data.message);
    } else {
      alert('Не удалось выполнить запись. Попробуйте позже.');
    }
  } finally {
    booking.value = false;
    currentSchedule.value = null;
    selectedActivity.value = '';
  }
};

onMounted(loadSchedules);
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.4s ease-out;
}
.slide-fade-leave-active {
  transition: all 0.3s ease-in;
}
.slide-fade-enter-from {
  transform: translateX(100px);
  opacity: 0;
}
.slide-fade-leave-to {
  transform: translateX(100px);
  opacity: 0;
}
</style>