<template>
  <header class="bg-linear-to-r from-[#660708] via-[#A4161A] to-[#BA181B] sticky top-0 z-50 shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-4">
        <router-link to="/" class="flex items-center space-x-3 shrink-0">
          <div class="bg-white p-2.5 rounded-xl shadow-lg hover:shadow-2xl transition-shadow">
            <svg class="w-10 h-10 text-[#BA181B]" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94L14.4 2.81c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
            </svg>
          </div>
          <div class="hidden sm:block">
            <h1 class="text-2xl font-bold text-white leading-tight tracking-wide">BERKAH ONDERDIL</h1>
            <p class="text-xs text-[#F5F3F4] font-medium">Melayani Jual Beli Sparepart Mobil Second</p>
          </div>
        </router-link>

        <nav class="hidden lg:flex items-center space-x-2">
          <router-link to="/" class="px-5 py-2.5 text-white hover:bg-white/20 rounded-lg transition-all font-semibold text-sm backdrop-blur-sm">
            Home
          </router-link>
          <router-link to="/catalog" class="px-5 py-2.5 text-white hover:bg-white/20 rounded-lg transition-all font-semibold text-sm backdrop-blur-sm">
            Katalog
          </router-link>
          <router-link to="/request" class="px-5 py-2.5 text-white hover:bg-white/20 rounded-lg transition-all font-semibold text-sm backdrop-blur-sm">
            Request
          </router-link>
          <router-link to="/about" class="px-5 py-2.5 text-white hover:bg-white/20 rounded-lg transition-all font-semibold text-sm backdrop-blur-sm">
            Tentang
          </router-link>
        </nav>

        <div class="flex items-center space-x-3">
          <div class="hidden md:block relative">
            <input v-model="keyword" @keyup.enter="goToSearch" type="text" placeholder="Cari sparepart..." class="w-72 px-4 py-2.5 pr-11 rounded-lg bg-white/95 backdrop-blur-sm text-[#0B090A] placeholder-[#B1A7A6] border-2 border-transparent focus:border-white focus:outline-none focus:bg-white transition-all font-medium" />
            <button @click="goToSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#BA181B] hover:text-[#E5383B] transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>
          <router-link to="/cart" class="relative text-white hover:text-[#FFD60A] transition p-2 rounded-lg hover:bg-white/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h14l-2-9M10 21a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z" />
            </svg>
            <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-[#FFD60A] text-[#161A1D] text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center">
              {{ cartCount }}
            </span>
          </router-link>
          <template v-if="!isAuthenticated">
            <router-link to="/login" class="hidden md:block px-5 py-2.5 text-black bg-[#FFD60A] backdrop-blur-sm border-2 border-[#FFD60A] rounded-lg hover:bg-white hover:text-[#BA181B] hover:border-white transition-all font-semibold text-sm shadow-lg">
              Masuk
            </router-link>
            
            <router-link to="/register" class="hidden md:block px-5 py-2.5 text-white bg-white/10 backdrop-blur-sm border-2 border-white rounded-lg hover:bg-white hover:text-[#BA181B] transition-all font-semibold text-sm shadow-lg">
              Daftar
            </router-link>
          </template>

          <template v-else>
            <span class="text-white font-semibold text-sm hidden md:block">
              Hi, {{ user?.name }}
            </span>
            <button @click="logout" class="px-5 py-2.5 text-white bg-white/10 backdrop-blur-sm border-2 border-white rounded-lg hover:bg-white hover:text-[#BA181B] transition-all font-semibold text-sm shadow-lg">
              Keluar
            </button>
          </template>

          <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white p-2.5 hover:bg-white/20 rounded-lg transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
      <div v-if="mobileMenuOpen" class="lg:hidden bg-[#161A1D] border-t-2 border-[#E5383B]">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-2">
          <div class="relative mb-3">
            <input type="text" placeholder="Cari sparepart..." class="w-full px-4 py-2.5 pr-11 rounded-lg bg-[#0B090A] text-white placeholder-[#B1A7A6] border-2 border-[#660708] focus:border-[#E5383B] focus:outline-none transition-all" />
            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-[#E5383B]">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>

          <router-link to="/" @click="mobileMenuOpen = false" class="block px-4 py-3 text-white hover:bg-[#BA181B] rounded-lg transition-all font-semibold">
            Home
          </router-link>
          <router-link to="/catalog" @click="mobileMenuOpen = false" class="block px-4 py-3 text-white hover:bg-[#BA181B] rounded-lg transition-all font-semibold">
            Katalog
          </router-link>
          <router-link to="/request" @click="mobileMenuOpen = false" class="block px-4 py-3 text-white hover:bg-[#BA181B] rounded-lg transition-all font-semibold">
            Request
          </router-link>
          <router-link to="/about" @click="mobileMenuOpen = false" class="block px-4 py-3 text-white hover:bg-[#BA181B] rounded-lg transition-all font-semibold">
            Tentang
          </router-link>

          <router-link to="/cart" @click="mobileMenuOpen = false" class="block px-4 py-3 text-white border-2 border-[#FFD60A] rounded-lg hover:bg-[#FFD60A] hover:text-[#161A1D] transition-all font-semibold">
            🛒 Keranjang <span v-if="cartCount > 0">({{ cartCount }})</span>
          </router-link>

          <template v-if="!isAuthenticated">
            <router-link to="/login" @click="mobileMenuOpen = false" class="block text-center w-full px-4 py-3 text-white border-2 border-[#E5383B] rounded-lg hover:bg-[#E5383B] transition-all font-semibold mt-2">
              Masuk
            </router-link>

            <router-link to="/register" @click="mobileMenuOpen = false" class="block text-center w-full px-4 py-3 text-[#161A1D] bg-[#FFD60A] rounded-lg hover:bg-yellow-300 transition-all font-semibold mt-2">
              Daftar
            </router-link>
          </template>
          
          <template v-else>
            <div class="px-4 py-3 text-white font-semibold border-b border-gray-700">
              Hi, {{ user?.name }}
            </div>
            <button @click="logout" class="w-full px-4 py-3 text-white border-2 border-[#E5383B] rounded-lg hover:bg-[#E5383B] transition-all font-semibold mt-2">
              Keluar
            </button>
          </template>

        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
// Pastikan path ini sesuai dengan store Anda
import { useAuthStore } from '../store/auth' 

const keyword = ref('')
const router = useRouter()
const mobileMenuOpen = ref(false)
const cartCount = ref(2) 

// Auth Logic
const authStore = useAuthStore() // Error handle: pastikan file ini ada
const isAuthenticated = computed(() => authStore?.isAuthenticated || false)
const user = computed(() => authStore?.user || null)

const logout = async () => {
  if (authStore) {
    await authStore.handleLogout()
    router.push('/')
  }
}

function goToSearch() {
  if (keyword.value.trim() !== '') {
    router.push({ path: '/catalog', query: { search: keyword.value } })
  }
}
</script>