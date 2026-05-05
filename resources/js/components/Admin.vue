<template>
  <div class="container mx-auto px-6 py-12 text-white">
    <h2 class="text-3xl font-bold mb-8">Управление: {{ activeTab.toUpperCase() }}</h2>

    <div class="flex gap-4 mb-8 flex-wrap">
      <button v-for="t in ['news', 'staff', 'services', 'schedules']" :key="t" 
              @click="activeTab = t" 
              class="px-6 py-2 rounded-lg font-bold transition"
              :class="activeTab === t ? 'bg-blue-600' : 'bg-white/5 hover:bg-white/10'">
        {{ tabsLabel[t] }}
      </button>
    </div>

    <div class="bg-white/5 p-6 rounded-xl border border-white/10 mb-10">
      <h3 class="text-xl font-bold mb-4">
        {{ editingId ? '✏️ Редактирование записи' : '➕ Новая запись' }}
      </h3>
      
      <div class="grid grid-cols-1 gap-4">
        <div v-if="activeTab === 'schedules'" class="grid grid-cols-1 gap-4">
          <div>
            <label class="block text-sm text-blue-200 mb-1">День недели</label>
            <select v-model="form.day_of_week" class="bg-blue-950 p-3 rounded border border-white/10 w-full">
              <option value="">Выберите день</option>
              <option value="Понедельник">Понедельник</option>
              <option value="Вторник">Вторник</option>
              <option value="Среда">Среда</option>
              <option value="Четверг">Четверг</option>
              <option value="Пятница">Пятница</option>
              <option value="Суббота">Суббота</option>
              <option value="Воскресенье">Воскресенье</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-blue-200 mb-1">Время</label>
            <input v-model="form.time" type="time" class="bg-blue-950 p-3 rounded border border-white/10 w-full">
          </div>
          <div>
            <label class="block text-sm text-blue-200 mb-1">Занятие</label>
            <input v-model="form.activity" placeholder="Например: Йога" class="bg-blue-950 p-3 rounded border border-white/10 w-full">
          </div>
          <div>
            <label class="block text-sm text-blue-200 mb-1">Тренер</label>
            <input v-model="form.trainer" placeholder="Имя и фамилия тренера" class="bg-blue-950 p-3 rounded border border-white/10 w-full">
          </div>
        </div>

        <div v-else class="grid grid-cols-1 gap-4">
          <div>
            <label class="block text-sm text-blue-200 mb-1">
              {{ activeTab === 'staff' ? 'Имя сотрудника' : 'Заголовок' }}
            </label>
            <input v-model="form.title" 
                   :placeholder="activeTab === 'staff' ? 'Имя и фамилия' : 'Заголовок'" 
                   class="bg-blue-950 p-3 rounded border border-white/10 w-full">
          </div>
          
          <div>
            <label class="block text-sm text-blue-200 mb-1">
              {{ activeTab === 'staff' ? 'Должность' : 'Описание' }}
            </label>
            <textarea v-model="form.content" 
                      :placeholder="activeTab === 'staff' ? 'Должность сотрудника' : 'Текст описания'" 
                      rows="4"
                      class="bg-blue-950 p-3 rounded border border-white/10 w-full"></textarea>
          </div>
          
          <div>
            <input type="file" @change="handleFileUpload" class="bg-blue-950 p-2 rounded border border-white/10 w-full">
              <p v-if="isFileObject(form.image)" class="text-green-400 text-sm mt-1">
                Выбран файл: {{ form.image.name }}
              </p>
              <p v-else-if="typeof form.image === 'string' && form.image" class="text-blue-300 text-sm mt-1">
                Изображение уже загружено (оставьте пустым, чтобы не менять)
              </p>
          </div>

          <div v-if="activeTab === 'staff'">
            <label class="block text-sm font-bold text-blue-200 mb-1">Категория:</label>
            <select v-model="form.category" class="bg-blue-950 p-3 rounded border border-white/10 w-full">
              <option value="">Выберите категорию</option>
              <option value="Руководство">Руководство</option>
              <option value="Тренеры">Тренеры</option>
              <option value="Персонал">Персонал</option>
            </select>
          </div>
        </div>

        <div class="flex gap-3">
          <button @click="save" 
                  class="flex-1 bg-green-600 py-3 rounded font-bold hover:bg-green-500 transition">
            {{ editingId ? '📝 ОБНОВИТЬ' : '✅ СОХРАНИТЬ' }}
          </button>
          <button v-if="editingId" @click="cancelEdit" 
                  class="flex-1 bg-gray-600 py-3 rounded font-bold hover:bg-gray-500 transition">
            ❌ ОТМЕНА
          </button>
        </div>
      </div>
    </div>

    <div class="space-y-3">
      <h3 class="text-xl font-bold mb-4">Список записей ({{ list.length }})</h3>
      
      <div v-if="list.length === 0" class="text-center text-gray-400 py-8">
        Нет записей для отображения
      </div>
      
      <div v-for="item in list" :key="item.id" 
           class="flex justify-between items-center bg-white/5 hover:bg-white/10 p-4 rounded transition">
        
        <div class="flex items-center gap-4">
          <!-- Миниатюра изображения -->
          <img v-if="item.image" :src="getImageUrl(item.image)" 
               class="w-12 h-12 rounded object-cover bg-blue-950">
          
          <div>
            <p class="font-bold">{{ getItemTitle(item) }}</p>
            <p class="text-sm text-gray-400">{{ getItemSubtitle(item) }}</p>
          </div>
        </div>
        
        <div class="flex gap-2">
          <button @click="edit(item)" 
                  class="bg-blue-600 px-4 py-2 rounded text-sm hover:bg-blue-500 transition">
            ✏️ РЕДАКТИРОВАТЬ
          </button>
          <button @click="remove(item.id)" 
                  class="bg-red-600 px-4 py-2 rounded text-sm hover:bg-red-500 transition">
            🗑️ УДАЛИТЬ
          </button>
        </div>
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
const editingId = ref(null);

const tabsLabel = {
  news: 'НОВОСТИ',
  staff: 'ПЕРСОНАЛ',
  services: 'УСЛУГИ',
  schedules: 'РАСПИСАНИЕ'
};

const isFileObject = (value) => {
  return value && typeof value === 'object' && value instanceof File;
};

const load = async () => {
  try {
    const { data } = await api.get(`/${activeTab.value}`);
    list.value = Array.isArray(data) ? data : (data.data || []);
  } catch (err) {
    console.error('Ошибка загрузки:', err);
    list.value = [];
  }
};

const save = async () => {
  try {
    if (editingId.value) {

      await updateItem();
    } else {

      await createItem();
    }

    resetForm();
    await load();
    alert('✅ Успешно сохранено!');
    
  } catch (err) {
    console.error('Ошибка сохранения:', err.response?.data || err);
    alert('❌ Ошибка при сохранении! Проверьте консоль (F12)');
  }
};

const createItem = async () => {
  if (activeTab.value === 'schedules') {
    await api.post('/schedules', form.value, {
      headers: { 'Content-Type': 'application/json' }
    });
  } else {
    const fd = new FormData();
    appendFormData(fd);
    await api.post(`/${activeTab.value}`, fd, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  }
};

const updateItem = async () => {
  const id = editingId.value;
  
  if (activeTab.value === 'schedules') {
    await api.put(`/schedules/${id}`, form.value, {
      headers: { 'Content-Type': 'application/json' }
    });
  } else {
    const fd = new FormData();
    fd.append('_method', 'PUT');
    appendFormData(fd);
    await api.post(`/${activeTab.value}/${id}`, fd, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  }
};

const appendFormData = (fd) => {
  Object.keys(form.value).forEach(key => {
    if (key === 'image' && form.value.image instanceof File) {
      fd.append(key, form.value.image);
    } else if (key === 'image' && typeof form.value.image === 'string') {

    } else if (form.value[key] !== null && form.value[key] !== undefined && form.value[key] !== '') {
      fd.append(key, form.value[key]);
    }
  });
};

const remove = async (id) => {
  if (!confirm('Вы уверены, что хотите удалить эту запись?')) return;
  
  try {
    await api.delete(`/${activeTab.value}/${id}`);
    await load();
    alert('🗑️ Запись удалена');
  } catch (err) {
    console.error('Ошибка удаления:', err);
    alert('❌ Ошибка при удалении');
  }
};

const edit = (item) => {
  editingId.value = item.id;
  
  if (activeTab.value === 'schedules') {
    form.value = {
      day_of_week: item.day_of_week || '',
      time: item.time || '',
      activity: item.activity || '',
      trainer: item.trainer || ''
    };
  } else if (activeTab.value === 'staff') {
    form.value = {
      title: item.name || '',
      content: item.position || '',
      category: item.category || '',
      image: item.photo || '' 
    };
  } else {
    form.value = {
      title: item.title || '',
      content: item.content || '',
      image: item.image || ''
    };
  }

  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEdit = () => {
  resetForm();
};

const resetForm = () => {
  editingId.value = null;
  form.value = {};
};

const handleFileUpload = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.image = file;
  }
};
const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  return `http://127.0.0.1:8000/storage/${path.replace(/^\/?storage\//, '')}`;
};

const getItemTitle = (item) => {
  if (activeTab.value === 'schedules') return item.activity || 'Без названия';
  if (activeTab.value === 'staff') return item.name || item.title || 'Без имени';
  return item.title || 'Без заголовка';
};

const getItemSubtitle = (item) => {
  if (activeTab.value === 'schedules') {
    return `${item.day_of_week || ''} в ${item.time || '—'} | ${item.trainer || ''}`;
  }
  if (activeTab.value === 'staff') {
    return item.position || item.content || '';
  }
  return item.content ? item.content.substring(0, 100) + (item.content.length > 100 ? '...' : '') : '';
};

watch(activeTab, () => {
  resetForm();
  load();
});

onMounted(load);
</script>