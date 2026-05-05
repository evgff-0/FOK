<template>
  <div class="container mx-auto px-6 py-12 grid grid-cols-2 gap-16">
    <div>
      <h2 class="text-3xl font-bold mb-8">Контакты</h2>
      <div class="space-y-4 text-blue-200">
        <p>Телефон: <a href="tel:+78313286051" class="text-white">+7(831) 328-60-51</a></p>
        <p>Почта: <a href="mailto:info@atlanta.ru" class="text-white">info@atlanta.ru</a></p>
        <p>Адрес: г. Нижний Новгород, ул. Большая Покровская,63</p>
        <p>Время работы: ежедневно с 8:00 до 22:00</p>
      </div>
    </div>

    <form class="bg-white/5 p-8 rounded-xl border border-white/5 space-y-4" @submit.prevent="send">
      <h3 class="text-xl font-bold mb-4">У вас есть вопросы?</h3>
      <input v-model="form.name" type="text" placeholder="Имя" class="w-full bg-blue-950 p-4 rounded-lg border border-white/5" required>
      <input v-model="form.phone" type="tel" placeholder="Телефон" class="w-full bg-blue-950 p-4 rounded-lg border border-white/5" required>
      <input v-model="form.email" type="email" placeholder="Почта" class="w-full bg-blue-950 p-4 rounded-lg border border-white/5" required>
      <textarea v-model="form.message" placeholder="Ваш вопрос" class="w-full bg-blue-950 p-4 rounded-lg border border-white/5 h-32" required></textarea>
      <button type="submit" :disabled="loading" class="bg-blue-600 px-8 py-4 rounded-lg font-bold w-full disabled:opacity-50">
        {{ loading ? 'Отправляем...' : 'Отправить' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '@/services/api';
import { useRouter } from 'vue-router';

const form = ref({ name: '', phone: '', email: '', message: '' });
const loading = ref(false);
const router = useRouter();

const send = async () => {
  try {
    await api.post('/contacts', form.value);
    alert('Отправлено!');
    form.value = { name: '', phone: '', email: '', message: '' };
  } catch (e) {
    alert('Ошибка');
  }
};
</script>