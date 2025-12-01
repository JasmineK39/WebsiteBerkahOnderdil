<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Reset Password</h2>
    
    <div v-if="successMessage" class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ successMessage }}</div>
    <div v-if="errors.email" class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ errors.email }}</div>
    
    <form @submit.prevent="submit">
      <input type="hidden" v-model="token" />

      <div class="mb-4">
        <label>Email</label>
        <input type="email" v-model="email" class="input" required />
      </div>

        <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="relative">
          <input :type="isPasswordVisible ? 'text' : 'password'" 
                 class="w-full pl-10 pr-10 py-2 border rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]"
                 :class="errors.password ? 'border-red-500' : 'border-gray-300'" 
                 placeholder="Password" v-model="form.password" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-lock"></span>
          </span>
          <button type="button" @click="isPasswordVisible = !isPasswordVisible"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
            <span :class="isPasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></span>
          </button>
        </div>
        <p v-if="errors.password" class="mt-1 text-sm text-red-500">{{ Array.isArray(errors.password) ? errors.password[0] : errors.password }}</p>
      </div>
      <!-- INPUT: Retype Password -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <div class="relative">
          <input :type="isConfirmPasswordVisible ? 'text' : 'password'" 
                 class="w-full pl-10 pr-10 py-2 border rounded-lg focus:ring-[#BA181B] focus:border-[#BA181B]"
                 :class="errors.password_confirmation ? 'border-red-500' : 'border-gray-300'" 
                 placeholder="Ulangi Password" v-model="form.password_confirmation" required>
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="fas fa-lock"></span>
          </span>
          <button type="button" @click="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
            <span :class="isConfirmPasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></span>
          </button>
        </div>
        <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-500">{{ Array.isArray(errors.password_confirmation) ? errors.password_confirmation[0] : errors.password_confirmation }}</p>
      </div>

      <button type="submit" class="btn">Reset Password</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

const isPasswordVisible = ref(false);
const isConfirmPasswordVisible = ref(false);

const token = ref("");
const email = ref("");
const form = ref({
  password: "",
  password_confirmation: ""
});
const errors = ref({
  password_confirmation: null,
  password: null,
  email: null
});
const successMessage = ref("");

onMounted(() => {
  // Prefer token from URL fragment (hash) set by the reset email (safer).
  // Example fragment: #token=XYZ&email=user%40example.com
  const hash = route.hash || window.location.hash || '';
  const params = new URLSearchParams(hash.replace(/^#/, ''));
  // We expect password reset links to use `reset_token` in the fragment to avoid
  // colliding with OAuth `token` values used for auth.
  token.value = params.get('reset_token') || params.get('token') || route.query.token || '';
  email.value = params.get('email') || route.query.email || '';
});

async function submit() {
  errors.value = { password_confirmation: null, password: null, email: null };
  successMessage.value = "";
  try {
    const res = await axios.post("api/auth/reset-password", {
      token: token.value,
      email: email.value,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation
    });
    successMessage.value = res.data.message;
    setTimeout(() => {
      router.push("/auth/login"); // redirect ke halaman login
    }, 1500);
  } catch (err) {
    console.log("Error response:", err.response); // Debug log
    
    // Handle Laravel validation errors (422 status)
    if (err.response?.status === 422 && err.response?.data?.errors) {
      errors.value = err.response.data.errors;
    } 
    // Handle other backend errors
    else if (err.response?.data?.message) {
      errors.value.email = err.response.data.message;
    } 
    // Fallback error message
    else {
      errors.value.email = "Gagal reset password.";
    }
  }
}
</script>

<style scoped>
.input { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
.btn { background: #4f46e5; color: white; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; }
</style>
