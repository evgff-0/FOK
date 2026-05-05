<template>
  <div class="container mx-auto px-6 py-12 text-white">
    <h2 class="text-3xl font-bold mb-8">Управление: {{ activeTab.toUpperCase() }}</h2>
    
    <div class="flex gap-4 mb-8">
      <button v-for="t in ['news', 'staff', 'services', 'schedules']" :key="t" 
              @click="activeTab = t" 
              class="px-6 py-2 rounded-lg font-bold"
              :class="activeTab === t ? 'bg-blue-600' : 'bg-white/5'">
        {{ t.toUpperCase() }}
      </button>
    </div>

    <div class="bg-white/5 p-6 rounded-xl border border-white/10 mb-10">
      <div class="grid grid-cols-1 gap-4">

        <div v-if="activeTab === 'schedules'" class="grid grid-cols-1 gap-4">
          <input v-model="form.time" placeholder="Время" class="bg-blue-950 p-3 rounded border">
          <input v-model="form.activity" placeholder="Занятие" class="bg-blue-950 p-3 rounded border">
          <input v-model="form.trainer" placeholder="Тренер" class="bg-blue-950 p-3 rounded border">
          <input v-model="form.day_of_week" placeholder="День недели" class="bg-blue-950 p-3 rounded border">
        </div>

        <div v-else class="grid grid-cols-1 gap-4">
          <input v-model="form.title" placeholder="Заголовок" class="bg-blue-950 p-3 rounded border">
          <input v-model="form.content" placeholder="Описание" class="bg-blue-950 p-3 rounded border">
          <input type="file" @change="e => form.image = e.target.files[0]" class="bg-blue-950 p-2 rounded">
        </div>
        
        <button @click="save" class="bg-green-600 py-3 rounded font-bold hover:bg-green-500 transition">
          СОХРАНИТЬ ЗАПИСЬ
        </button>
      </div>
    </div>

    <div class="space-y-2">
      <div v-for="item in list" :key="item.id" class="flex justify-between bg-white/5 p-4 rounded">
        <span>{{ item.title || item.activity || item.name }}</span>
        <button @click="remove(item.id)" class="text-red-400 font-bold">УДАЛИТЬ</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import api from '../services/api';

const activeTab = ref('news');
const list = ref([]);
const form = ref({}); 

const load = async () => { 
  list.value = (await api.get(`/${activeTab.value}`)).data; 
};

const save = async () => {
  try {
    if (activeTab.value === 'schedules') {

      await api.post('/schedules', form.value, {
        headers: { 'Content-Type': 'application/json' }
      });
    } else {

      const fd = new FormData();
      Object.keys(form.value).forEach(key => fd.append(key, form.value[key]));
      await api.post(`/${activeTab.value}`, fd, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    }
    alert('Сохранено!');
    form.value = {}; 
    load();
  } catch (err) {

    console.error("Детали ошибки:", err.response?.data);
    alert('Ошибка! Посмотри консоль (F12) -> вкладка Network -> ответ сервера.');
  }
};

const remove = async (id) => { 
  await api.delete(`/${activeTab.value}/${id}`); 
  load(); 
};

watch(activeTab, () => { form.value = {}; load(); });
onMounted(load);
</script>