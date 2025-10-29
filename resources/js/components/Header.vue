<template>
  <header class="bg-linear-to-r from-[#660708] via-[#A4161A] to-[#BA181B] sticky top-0 z-50 shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-4">
        <!-- Logo Section -->
        <router-link to="/" class="flex items-center space-x-3 shrink-0">
          <div class="bg-white p-2.5 rounded-xl shadow-lg hover:shadow-2xl transition-shadow">
            <svg class="w-10 h-10 text-[#BA181B]" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94L14.4 2.81c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
            </svg>
          </div>
          <div class="hidden sm:block">
            <h1 class="text-2xl font-bold text-white leading-tight tracking-wide">BERKAH ONDERDIL</h1>
            <p class="text-xs text-[#F5F3F4] font-medium">Melayani Jual Beli Mobil & Onderdil</p>
          </div>
        </router-link>

        <!-- Navigation Menu - Desktop -->
        <nav class="hidden lg:flex items-center space-x-2">
          <router-link 
            to="/" 
            class="px-5 py-2.5 text-white hover:bg-white/20 rounded-lg transition-all font-semibold text-sm backdrop-blur-sm"
          >
            Home
          </router-link>
          <router-link 
            to="/catalog" 
            class="px-5 py-2.5 text-white hover:bg-white/20 rounded-lg transition-all font-semibold text-sm backdrop-blur-sm"
          >
            Katalog
          </router-link>
          <router-link 
            to="/request" 
            class="px-5 py-2.5 text-white hover:bg-white/20 rounded-lg transition-all font-semibold text-sm backdrop-blur-sm"
          >
            Request
          </router-link>
          <router-link 
            to="/about" 
            class="px-5 py-2.5 text-white hover:bg-white/20 rounded-lg transition-all font-semibold text-sm backdrop-blur-sm"
          >
            Tentang
          </router-link>
        </nav>

        <!-- Search & Auth Section -->
<div class="flex items-center space-x-3">
  <!-- Search Bar - Desktop -->
  <div class="hidden md:block relative">
    <input
      v-model="keyword"
      @keyup.enter="goToSearch"
      type="text"
      placeholder="Cari sparepart..."
      class="w-72 px-4 py-2.5 pr-11 rounded-lg bg-white/95 backdrop-blur-sm text-[#0B090A] placeholder-[#B1A7A6] border-2 border-transparent focus:border-white focus:outline-none focus:bg-white transition-all font-medium"
    />
    <button
      @click="goToSearch"
      class="absolute right-3 top-1/2 -translate-y-1/2 text-[#BA181B] hover:text-[#E5383B] transition"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </button>
  </div>


          <!-- Login Button - Desktop -->
          <button
            @click="showLoginModal = true"
            class="hidden md:block px-5 py-2.5 text-white bg-white/10 backdrop-blur-sm border-2 border-white rounded-lg hover:bg-white hover:text-[#BA181B] transition-all font-semibold text-sm shadow-lg"
          >
            Masuk
          </button>

          <!-- Mobile Menu Button -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="lg:hidden text-white p-2.5 hover:bg-white/20 rounded-lg transition-all"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div v-if="mobileMenuOpen" class="lg:hidden bg-[#161A1D] border-t-2 border-[#E5383B]">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-2">
          <!-- Mobile Search -->
          <div class="relative mb-3">
            <input
              type="text"
              placeholder="Cari sparepart..."
              class="w-full px-4 py-2.5 pr-11 rounded-lg bg-[#0B090A] text-white placeholder-[#B1A7A6] border-2 border-[#660708] focus:border-[#E5383B] focus:outline-none transition-all"
            />
            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-[#E5383B]">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>

          <!-- Mobile Navigation -->
          <router-link 
            to="/" 
            @click="mobileMenuOpen = false"
            class="block px-4 py-3 text-white hover:bg-[#BA181B] rounded-lg transition-all font-semibold"
          >
            Home
          </router-link>
          <router-link 
            to="/catalog" 
            @click="mobileMenuOpen = false"
            class="block px-4 py-3 text-white hover:bg-[#BA181B] rounded-lg transition-all font-semibold"
          >
            Katalog
          </router-link>
          <router-link 
            to="/request" 
            @click="mobileMenuOpen = false"
            class="block px-4 py-3 text-white hover:bg-[#BA181B] rounded-lg transition-all font-semibold"
          >
            Request
          </router-link>
          <router-link 
            to="/about" 
            @click="mobileMenuOpen = false"
            class="block px-4 py-3 text-white hover:bg-[#BA181B] rounded-lg transition-all font-semibold"
          >
            Tentang
          </router-link>

          <!-- Mobile Login -->
          <button
            @click="showLoginModal = true; mobileMenuOpen = false"
            class="w-full px-4 py-3 text-white border-2 border-[#E5383B] rounded-lg hover:bg-[#E5383B] transition-all font-semibold mt-2"
          >
            Masuk
          </button>
        </div>
      </div>
    </transition>

    <!-- Login Modal -->
    <LoginModal v-if="showLoginModal" @close="showLoginModal = false" />
  </header>
</template>

<script setup>
import { ref } from 'vue'
import LoginModal from './LoginModal.vue'
import { useRouter } from 'vue-router'

const keyword = ref('')
const router = useRouter()

function goToSearch() {
  if (keyword.value.trim() !== '') {
    router.push({ path: '/catalog', query: { search: keyword.value } })
  }
}

const showLoginModal = ref(false)
const mobileMenuOpen = ref(false)
</script>

<style scoped>
/* Optional: Add custom styles if needed */
</style>