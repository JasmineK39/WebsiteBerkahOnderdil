<template>
  <!-- WRAPPER HALAMAN VERIFIKASI -->
  <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">

    <!-- LOADING SEMENTARA STORE BELUM SIAP -->
    <div v-if="loadingStore" class="text-center p-8 bg-white shadow-xl rounded-xl">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#BA181B] mx-auto mb-4"></div>
      <p class="text-gray-600">Memuat data sesi...</p>
    </div>

    <!-- KONTEN UTAMA -->
    <div v-else class="w-full max-w-md p-8 bg-white shadow-2xl rounded-xl border border-red-100">

      <!-- JUDUL -->
      <div class="text-center mb-6">
        <div class="text-5xl text-[#BA181B] mb-3">
          <i class="fas fa-mobile-alt"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Verifikasi Akun Anda</h2>
        <p class="text-sm text-gray-600 mt-2">
          Kami telah mengirimkan kode verifikasi (OTP) ke email:
          <strong class="text-[#BA181B] block mt-1 break-words">{{ email }}</strong>
        </p>
      </div>

      <!-- FORM VERIFIKASI -->
      <form @submit.prevent="verifyOtp">
        <!-- INPUT OTP -->
        <div class="mb-4">
          <label for="otp" class="block text-sm font-medium text-gray-700 mb-2 text-center">
            Masukkan Kode 6 Digit
          </label>
          <input
            id="otp"
            type="text"
            v-model="otp"
            maxlength="6"
            placeholder="000000"
            required
            class="w-full text-center text-2xl tracking-widest p-3 border-2 border-gray-300 rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B] transition"
          />
        </div>

        <!-- PESAN ERROR/SUKSES -->
        <div v-if="message" :class="{'bg-red-100 text-red-700': isError, 'bg-green-100 text-green-700': !isError}" 
             class="p-3 rounded-lg text-sm mb-4 text-center">
          {{ message }}
        </div>

        <!-- TOMBOL VERIFIKASI -->
        <button
          type="submit"
          class="w-full px-6 py-3 bg-[#BA181B] text-white font-semibold rounded-lg shadow-md hover:bg-[#A4161A] transition-colors disabled:opacity-50"
          :disabled="loading || otp.length !== 6"
        >
          {{ loading ? 'Memproses...' : 'Verifikasi Sekarang' }}
        </button>
      </form>

      <!-- RESEND CODE & LOGIN LINK -->
      <div class="mt-6 text-center text-sm space-y-3">
        <p class="text-gray-600">
          Belum menerima kode?
          <button
            @click="resendOtp"
            :disabled="loadingResend || timer > 0"
            class="font-medium text-red-600 hover:text-red-700 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors"
          >
            {{ loadingResend ? 'Mengirim...' : (timer > 0 ? `Kirim ulang dalam ${timer}s` : 'Kirim Ulang Kode') }}
          </button>
        </p>

        <p class="text-gray-500 pt-2 border-t border-gray-100">
          <router-link to="/auth/login" class="text-blue-600 hover:text-blue-700">
            &larr; Kembali ke Halaman Login
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const otp = ref('');
const email = localStorage.getItem('userEmail'); // ambil email dari localStorage
const loading = ref(false);
const loadingResend = ref(false);
const message = ref('');
const isError = ref(false);
const timer = ref(60); // countdown awal
let interval = null;
const loadingStore = ref(false); // jika ada store, bisa simulasikan

// Fungsi countdown OTP
const startCountdown = () => {
  clearInterval(interval); // hentikan interval lama
  interval = setInterval(() => {
    if (timer.value > 0) {
      timer.value--;
    } else {
      clearInterval(interval);
    }
  }, 1000);
};

// Jalankan countdown saat mounted
onMounted(() => {
  startCountdown();
});

// VERIFY OTP
const verifyOtp = async () => {
  if (otp.value.length !== 6) {
    message.value = "OTP harus 6 digit";
    isError.value = true;
    return;
  }

  loading.value = true;
  try {
    const res = await axios.post("/api/auth/verify-otp", { email, otp: otp.value });
    const updatedUser = res.data.user;
    localStorage.setItem('user', JSON.stringify(updatedUser));
    localStorage.removeItem('userEmail');
    localStorage.removeItem('token');

    message.value = "Verifikasi berhasil! Silahkan login.";
    isError.value = false;
    setTimeout(() => window.location.href = "/auth/login", 1500);
  } catch (err) {
    message.value = err.response?.data?.message || "OTP salah!";
    isError.value = true;
  }
  loading.value = false;
};

// RESEND OTP
const resendOtp = async () => {
  loadingResend.value = true;
  try {
    await axios.post("/api/auth/resend-otp", { email });
    message.value = "OTP baru telah dikirim ke email!";
    isError.value = false;
    timer.value = 60;
    startCountdown();
  } catch (err) {
    message.value = "Gagal mengirim ulang OTP";
    isError.value = true;
  }
  loadingResend.value = false;
};
</script>

<style scoped>
/* Contoh tambahan jika ingin mempercantik tombol atau input */
</style>
