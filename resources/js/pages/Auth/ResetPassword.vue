<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Reset Password</h2>
    
    <form @submit.prevent="submit">
      <input type="hidden" v-model="token" />

      <div class="mb-4">
        <label>Email</label>
        <input type="email" v-model="email" class="input" required />
      </div>

      <div class="mb-4">
        <label>Password Baru</label>
        <input type="password" v-model="password" class="input" required />
      </div>

      <div class="mb-4">
        <label>Konfirmasi Password</label>
        <input type="password" v-model="password_confirmation" class="input" required />
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

const token = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");

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
  try {
    const res = await axios.post("api/auth/reset-password", {
      token: token.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    });
    alert(res.data.message);
    router.push("/auth/login"); // redirect ke halaman login
  } catch (err) {
    alert(err.response.data.message || "Gagal reset password.");
  }
}
</script>

<style scoped>
.input { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
.btn { background: #4f46e5; color: white; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; }
</style>
