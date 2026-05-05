<template>
  <div class="container mx-auto px-6 py-12">
    <h2 class="text-4xl font-bold mb-12">Наши услуги</h2>
    <div class="grid grid-cols-3 gap-8">
      <div v-for="item in services" :key="item.id" class="bg-white/5 p-4 rounded-xl border border-white/5">
        <img v-if="item.image" :src="'/storage/' + item.image" class="w-full h-40 object-cover rounded-lg mb-4" />
        <h3 class="text-xl font-bold mb-2">{{ item.title }}</h3>
        <p class="text-blue-200 mb-4">{{ item.description }}</p>
        <router-link to="/schedule" class="text-blue-400 underline block mt-2 hover:text-blue-300 transition-colors">
          ➤ Перейти к расписанию
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';

const services = ref([]);
onMounted(async () => {
  const { data } = await api.get('/services');
  services.value = data;
});
</script>