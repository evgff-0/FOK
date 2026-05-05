<template>
  <div class="space-y-16 pb-20">
    <div class="relative h-[400px] w-full rounded-3xl overflow-hidden">
      <img src="/public/images/4.png" class="w-full h-full object-cover opacity-40" />
      <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
        <h2 class="text-5xl font-black text-white mb-6 drop-shadow-xl">О нас</h2>
        <p class="max-w-2xl text-xl text-white font-medium leading-relaxed drop-shadow-lg">
          Наш ФОК «Атлант» был создан для развития спорта в Нижнем Новгороде. Мы объединяем тысячи людей. Наша цель — доступный спорт каждому.
        </p>
      </div>
    </div>

    <div v-for="cat in ['Руководство', 'Тренеры', 'Персонал']" :key="cat" class="container mx-auto px-6">
      <h3 class="text-2xl font-bold mb-8 text-blue-300 border-l-4 border-blue-500 pl-4">{{ cat }}</h3>
      <div class="grid grid-cols-4 gap-6">
        <div v-for="person in staff.filter(s => s.category === cat)" :key="person.id" class="bg-white/5 p-4 rounded-2xl border border-white/5">
          <img v-if="person.photo" :src="'/storage/' + person.photo" class="w-full h-48 object-cover rounded-xl mb-4" />
          <h4 class="font-bold text-white">{{ person.name }}</h4>
          <p class="text-blue-400 text-sm">{{ person.position }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';

const staff = ref([]);
onMounted(async () => {
  const { data } = await api.get('/staff');
  staff.value = data;
});
</script>
