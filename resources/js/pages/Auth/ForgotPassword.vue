<template>
  <div class="w-full max-w-md p-8 bg-white shadow-2xl rounded-xl">
    
    <div class="text-center mb-6">
      <h2 class="text-3xl font-bold text-gray-800">Forgot Password</h2>
      <p class="text-sm text-gray-500 mt-1">Masukkan email Anda untuk menerima link reset password</p>
    </div>

    <form @submit.prevent="handleSubmit" v-if="!submitted">
      
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="relative">
          <input type="email" 
                 class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]" 
                 placeholder="Email" v-model="email" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-envelope"></span>
          </span>
        </div>
      </div>
      
      <div v-if="errorMessage" class="p-3 bg-red-100 text-red-700 rounded-lg text-sm mb-4">
          {{ errorMessage }}
      </div>

      <div class="flex items-center justify-center mt-6">
        <button type="submit" 
                class="px-6 py-2 bg-[#BA181B] text-white font-semibold rounded-lg shadow-md hover:bg-[#A4161A] transition-colors disabled:opacity-50" 
                :disabled="isLoading">
            {{ isLoading ? 'Loading...' : 'Send Reset Link' }}
        </button>
      </div>
    </form>

    <!-- Success Message -->
    <div v-else class="text-center">
      <div class="mb-6 text-green-600">
        <span class="fas fa-check-circle text-5xl"></span>
      </div>
      <h3 class="text-xl font-bold text-gray-800 mb-2">Email Sent Successfully</h3>
      <p class="text-sm text-gray-600 mb-6">
        Kami telah mengirimkan link reset password ke email Anda. Silakan cek inbox atau folder spam Anda.
      </p>
      <p class="text-xs text-gray-500 mb-6">
        Link akan berlaku selama 60 menit.
      </p>
      <router-link to="/auth/login" class="inline-block px-6 py-2 bg-[#BA181B] text-white font-semibold rounded-lg shadow-md hover:bg-[#A4161A] transition-colors">
        Kembali ke Login
      </router-link>
    </div>
    
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const email = ref('');
const isLoading = ref(false);
const errorMessage = ref('');
const submitted = ref(false);

const handleSubmit = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    const response = await axios.post('/api/auth/forgot-password', { email: email.value });
    
    // Success
    submitted.value = true;
    
  } catch (error) {
    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = 'Gagal mengirim link reset password. Silakan coba lagi.';
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>

</style>
