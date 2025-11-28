<template>
  <!-- WRAPPER HALAMAN (Menggantikan .register-page) -->
  <!-- Diasumsikan AuthLayout menyediakan wrapper flex/center -->
  <!-- Jika tidak menggunakan AuthLayout, ganti div ini dengan: 
       <div class="flex items-center justify-center min-h-screen bg-gray-100">...</div> -->
  <div class="w-full max-w-md p-8 bg-white shadow-2xl rounded-xl">

    <!-- JUDUL -->
    <div class="text-center mb-6">
      <h2 class="text-3xl font-bold text-gray-800">Daftar Akun Baru</h2>
      <p class="text-sm text-gray-500 mt-1">Buat akun untuk mulai berbelanja</p>
    </div>

    <form @submit.prevent="handleRegister">
      
      <!-- INPUT: Full Name -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
        <div class="relative">
          <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]" 
                 placeholder="Nama Lengkap" v-model="form.name" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-user"></span>
          </span>
        </div>
      </div>

      <!-- INPUT: Email -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="relative">
          <input type="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]" 
                 placeholder="Email" v-model="form.email" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-envelope"></span>
          </span>
        </div>
        <div v-if="errors.email" class="text-red-500 text-xs mt-1">
          {{ errors.email[0] }}
        </div>
      </div>

      <!-- INPUT: No. Telepon -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
        <div class="relative">
          <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]" 
                 placeholder="No. Telepon (08xx...)" v-model="form.phone" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-phone"></span>
          </span>
        </div>
        <div v-if="errors.phone" class="text-red-500 text-xs mt-1">
          {{ errors.phone[0] }}
        </div>
      </div>
      
      <!-- INPUT: Password -->
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
      <!-- INPUT: Retype Password -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <div class="relative">
          <input :type="isConfirmPasswordVisible ? 'text' : 'password'" 
                 class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]" 
                 placeholder="Ulangi Password" v-model="form.password_confirmation" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-lock"></span>
          </span>
          <button type="button" @click="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
            <span :class="isConfirmPasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></span>
          </button>
        </div>
        <div v-if="errors.password" class="text-red-500 text-xs mt-1">
          {{ errors.password[0] }}
        </div>
      </div>
     <div style="min-height: 120px;">
     <div id="recaptcha-container" class="g-recaptcha"></div>
      </div>
      <!-- TOMBOL REGISTER (Rata Kanan) -->
      <div class="flex justify-end mt-4">
        <button type="submit" 
                class="w-1/2 px-6 py-2 bg-[#BA181B] text-white font-semibold rounded-lg shadow-md hover:bg-[#A4161A] transition-colors disabled:opacity-50" 
                :disabled="isLoading">
          {{ isLoading ? 'Memuat...' : 'Daftar' }}
        </button>
      </div>

    </form>

    <!-- ERROR UMUM -->
    <div v-if="generalError" class="p-3 bg-red-100 text-red-700 rounded-lg text-sm mt-4">
      {{ generalError }}
    </div>

    <!-- LINK BAWAH -->
    <div class="mt-8 space-y-2 text-center text-sm">
      <p class="text-gray-600">
        Sudah punya akun? 
        <router-link to="/login" class="text-red-600 font-medium hover:text-red-700">
          Login
        </router-link>
      </p>
      
      <!-- SOSIAL MEDIA -->
      <div class="relative flex py-5 items-center">
          <div class="flex-grow border-t border-gray-300"></div>
          <span class="flex-shrink mx-4 text-gray-400">- OR -</span>
          <div class="flex-grow border-t border-gray-300"></div>
      </div>
      <button class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
        <i class="fab fa-google-plus mr-2 text-red-500"></i> Daftar menggunakan Google
      </button>
    </div>
  </div>
</template>

<script setup>
import AuthLayout from '../../components/layouts/AuthLayout.vue';
import { reactive, ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
// Import store auth agar state langsung update setelah register
import { useAuthStore } from '../../store/auth'; 

const RECAPTCHA_SITE_KEY = import.meta.env.VITE_RECAPTCHA_KEY;
const captchaToken = ref(null);

function onCaptchaVerified(token) {
    captchaToken.value = token;
    form.captcha_token = token; // <-- WAJIB, baru terkirim ke backend
    console.log('Captcha token:', token);
  }
const showCaptcha = ref(false);

onMounted(() => {
  showCaptcha.value = true; // agar muncul

  const interval = setInterval(() => {
    if (window.grecaptcha) {
      clearInterval(interval);
      window.grecaptcha.render("recaptcha-container", {
        sitekey: RECAPTCHA_SITE_KEY,
        callback: onCaptchaVerified
      });
    }
  }, 300);
});


console.log(RECAPTCHA_SITE_KEY);

const router = useRouter();
const authStore = useAuthStore();

const isLoading = ref(false);
const generalError = ref('');
const errors = ref({}); 
const isPasswordVisible = ref(false);
const isConfirmPasswordVisible = ref(false);

const form = reactive({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '', 
    captcha_token: '',
});

const handleRegister = async () => {
    errors.value = {};
    generalError.value = '';

    if (!form.captcha_token) {
    generalError.value = "Silakan selesaikan CAPTCHA terlebih dahulu.";
    return;
    }

    isLoading.value = true;

    try {
        const response = await axios.post('/api/register', form);

        // Simpan email user ke localStorage untuk OTP
        localStorage.setItem('userEmail', response.data.user.email);

        // Optional: simpan token sementara jika perlu, tapi user belum bisa login
        // localStorage.setItem('token', response.data.token);

        alert('Registrasi berhasil! Silakan cek email untuk kode OTP.');
        // Redirect ke halaman verify OTP
        router.push('/verify-otp');

    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            generalError.value = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    } finally {
        isLoading.value = false;
    }
};
</script>