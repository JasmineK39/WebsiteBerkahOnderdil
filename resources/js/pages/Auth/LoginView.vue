<template>
  <div class="w-full max-w-md p-8 bg-white shadow-2xl rounded-xl">
    
    <div class="text-center mb-6">
      <h2 class="text-3xl font-bold text-gray-800">Log In</h2>
      <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda</p>
    </div>

    <form @submit.prevent="handleLogin">
      
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="relative">
          <input type="email" 
                 class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]" 
                 placeholder="Email" v-model="form.email" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-envelope"></span>
          </span>
        </div>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="relative">
          <input :type="isPasswordVisible ? 'text' : 'password'" 
                 class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]" 
                 placeholder="Password" v-model="form.password" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-lock"></span>
          </span>
          <button type="button" @click="isPasswordVisible = !isPasswordVisible"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
            <span :class="isPasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></span>
          </button>
        </div>
      </div>
      
      <div v-if="errorMessage" class="p-3 bg-red-100 text-red-700 rounded-lg text-sm mb-4">
          {{ errorMessage }}
      </div>

      <div class="flex items-center justify-between mt-3 mb-6">
        
        <div class="flex items-center">
          <input id="remember" type="checkbox" v-model="form.remember"
                 class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
          <label for="remember" class="ml-2 block text-sm text-gray-900">
            Remember Me
          </label>
        </div>

        <button type="submit" 
                class="px-6 py-2 bg-[#BA181B] text-white font-semibold rounded-lg shadow-md hover:bg-[#A4161A] transition-colors disabled:opacity-50" 
                :disabled="isLoading">
            {{ isLoading ? 'Loading...' : 'Log In' }}
        </button>
        
      </div>
    </form> 
    
    <div class="text-center mt-2 mb-3">
      <div class="relative flex py-5 items-center">
          <div class="flex-grow border-t border-gray-300"></div>
          <span class="flex-shrink mx-4 text-gray-400 text-sm">- OR -</span>
          <div class="flex-grow border-t border-gray-300"></div>
      </div>
      
      <button class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition" @click="handleSocialLogin('google')">
        <i class="fab fa-google-plus mr-2 text-red-500"></i> Log In using Google
      </button>
    </div>

    <p class="mb-2 text-center text-sm">
      <router-link to="/forgot-password" class="text-red-600 hover:text-red-700">
        I forgot my password
      </router-link>
    </p>

    <p class="mb-0 text-center text-sm">
      <router-link to="/register" class="text-red-600 font-medium hover:text-red-700">
        Register a new membership
      </router-link>
    </p>
    
  </div>
</template>

<script setup>
import AuthLayout from '../../components/layouts/AuthLayout.vue';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../../store/auth';

const router = useRouter();
const authStore = useAuthStore();

// State
const form = reactive({ email: '', password: '', remember: false });
const isPasswordVisible = ref(false);
const isLoading = ref(false);
const errorMessage = ref('');

// Logic Login Skenario 2 (API Token)
const handleLogin = async () => {
    isLoading.value = true;
    errorMessage.value = '';

    try {
        const response = await axios.post('api/login', form);
        const token = response.data.token;
        const user = response.data.user;

        // Jika login berhasil dan user aktif
        authStore.setAuth(token, user);

        if (user.role === 'admin') {
            window.location.href ='/admin';
        } else {
            window.location.href ='/catalog';
        }

    } catch (error) {
        if (error.response) {
            if (error.response.status === 403) {
                // User belum verifikasi OTP
                localStorage.setItem('userEmail', form.email); // agar verify OTP bisa pakai email ini
                errorMessage.value = error.response.data.message;
                router.push('/verify-otp'); // redirect ke halaman OTP
            } else if (error.response.data.message) {
                errorMessage.value = error.response.data.message;
            } else {
                errorMessage.value = 'Terjadi kesalahan pada server.';
            }
        } else {
            errorMessage.value = 'Tidak dapat terhubung ke server.';
        }
    } finally {
        isLoading.value = false;
    }
};
// Optional: handle social login placeholder
const handleSocialLogin = (provider) => {
    alert(`Login dengan ${provider} belum diimplementasikan`);
};
</script>
