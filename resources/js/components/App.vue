<template>
  <div class="min-h-screen bg-[#0a192f] text-white font-sans selection:bg-blue-500">
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
      <div class="absolute -top-20 -left-20 w-96 h-96 bg-blue-500 rounded-full blur-3xl opacity-20"></div>
      <div class="absolute top-1/2 right-0 w-80 h-80 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
    </div>

    <div class="relative z-10">
      <header class="bg-blue-950/30 backdrop-blur-md border-b border-white/10 sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
          <h1 class="text-2xl font-black">ФОК «АТЛАНТ»</h1>
          <div class="flex items-center space-x-8 font-light text-sm tracking-widest">
            <router-link to="/" class="hover:text-blue-400">ГЛАВНАЯ</router-link>
            <router-link to="/about" class="hover:text-blue-400">О НАС</router-link>
            <router-link to="/services" class="hover:text-blue-400">УСЛУГИ</router-link>
            <router-link to="/schedule" class="hover:text-blue-400">РАСПИСАНИЕ</router-link>
            <router-link to="/contacts" class="hover:text-blue-400">КОНТАКТЫ</router-link>

            <router-link to="/login" class="opacity-70 hover:opacity-100 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </router-link>
          </div>
        </nav>
      </header>

      <main class="container mx-auto px-6 py-8">
        <router-view />
      </main>
    </div>
  </div>
</template>