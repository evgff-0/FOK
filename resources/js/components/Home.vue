<template>
  <div class="space-y-20 pb-20">
    <section class="container mx-auto px-6">
      <carousel :items-to-show="1" :autoplay="5000" :wrap-around="true">
        <slide v-for="i in 3" :key="i">
          <div class="relative w-full h-[500px] overflow-hidden">
            <img :src="`/images/banner${i}.jpg`" class="w-full h-full rounded-3xl object-cover" />
          </div>
        </slide>
      </carousel>
    </section>

    <section class="container mx-auto px-6 grid grid-cols-2 gap-16 items-center">
      <div>
        <h2 class="text-4xl font-bold mb-6">О нас</h2>
        <p class="text-gray-300 mb-6 leading-relaxed">
          ФОК «Атлант» — современный спортивный центр, предлагающий уникальные возможности для развития вашего тела и духа. Наши залы оснащены по последнему слову техники, а профессиональные инструкторы помогут вам достичь поставленных целей в комфортной атмосфере.
        </p>
        <router-link to="/about" class="bg-blue-600 px-8 py-3 rounded-full font-bold">Подробнее</router-link>
      </div>
      <img src="/public/images/4.png" class="rounded-3xl shadow-2xl h-full object-cover" />
    </section>

    <section class="container mx-auto px-6 grid grid-cols-4 gap-8">
      <div v-for="stat in [{n:'2000+',l:'посетителей'},{n:'16',l:'площадок и залов'},{n:'11000+',l:'м² площадь'},{n:'15',l:'секций'}]" 
           :key="stat.l" class="text-center p-6 bg-white/5 rounded-2xl">
        <div class="text-5xl font-black text-blue-400 mb-2">{{ stat.n }}</div>
        <p class="uppercase text-sm tracking-widest">{{ stat.l }}</p>
      </div>
    </section>

    <section class="container mx-auto px-6">
      <img src="/public/images/5.jpg" class="w-full h-[600px] object-cover rounded-3xl shadow-2xl" />
    </section>

<section class="container mx-auto px-6">
  <h2 class="text-3xl font-bold mb-8">Новости</h2>

  <div v-if="news.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div v-for="item in news" :key="item.id" 
         class="bg-white/5 rounded-2xl overflow-hidden border border-white/10 hover:border-blue-500 transition text-left">

      <img v-if="item.image" 
           :src="`/storage/${item.image}`" 
           class="w-full h-48 object-cover" 
           alt="Новость" />
      
      <div class="p-6">
        <h3 class="font-bold text-xl mb-2">{{ item.title }}</h3>
        <p class="text-sm text-gray-400 line-clamp-3">{{ item.content }}</p>
      </div>
    </div>
  </div>
  
  <div v-else class="text-gray-500 italic">
    Новостей пока нет...
  </div>
</section>
<section class="text-center py-10">
      <h2 class="text-3xl font-bold mb-6">Остались вопросы?</h2>
      <router-link to="/contacts" class="bg-blue-600 px-12 py-4 rounded-full font-bold">Напишите нам</router-link>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Navigation } from 'vue3-carousel';

const news = ref([]);

onMounted(async () => {
  try {
    const { data } = await api.get('/news');
    news.value = data;
  } catch (e) {
    console.error("Ошибка загрузки новостей:", e);
  }
});

</script>

    
