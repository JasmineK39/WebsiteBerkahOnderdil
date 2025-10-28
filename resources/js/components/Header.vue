<template>
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="shrink-0">
          <router-link to="/" class="text-2xl font-bold text-blue-600">
            Berkah Onderdil
          </router-link>
        </div>

        <!-- Navigation Menu -->
        <nav class="hidden md:flex items-center space-x-8">
          <router-link to="/" class="text-gray-700 hover:text-blue-600 transition">
            Home
          </router-link>
          <router-link to="/catalog" class="text-gray-700 hover:text-blue-600 transition">
            Katalog
          </router-link>
          <router-link to="/request" class="text-gray-700 hover:text-blue-600 transition">
            Request
          </router-link>
          <router-link to="/about" class="text-gray-700 hover:text-blue-600 transition">
            About
          </router-link>
        </nav>

        <!-- Search Bar -->
        <div class="hidden md:flex items-center flex-1 mx-8">
          <div class="relative w-full max-w-xs">
            <input
              type="text"
              placeholder="Cari sparepart..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button class="absolute right-3 top-2.5 text-gray-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Auth Buttons -->
        <div class="flex items-center space-x-4">
          <button
            @click="showLoginModal = true"
            class="text-gray-700 hover:text-blue-600 transition"
          >
            Login
          </button>
          <button
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Register
          </button>
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Login Modal -->
    <LoginModal v-if="showLoginModal" @close="showLoginModal = false" />
  </header>
</template>

<script setup>
import { ref } from 'vue'
import LoginModal from './LoginModal.vue'

const showLoginModal = ref(false)
</script>

<style scoped>
</style>
