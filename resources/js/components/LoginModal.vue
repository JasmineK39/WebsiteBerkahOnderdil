<template>
  <div class="fixed inset-0 bg-overlay flex items-center justify-center z-50 p-4">
    <div class="bg-dark-secondary rounded-2xl shadow-2xl max-w-md w-full border border-gray-700 overflow-hidden">
      <!-- Header with Red Accent -->
      <div class="bg-gradient-to-r from-primary-dark to-primary p-6">
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-bold text-light">Login</h2>
          <button
            @click="$emit('close')"
            class="text-light-secondary hover:text-light hover:rotate-90 transition-all duration-300"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <p class="text-light-secondary text-sm mt-2">Masuk ke akun Anda untuk melanjutkan</p>
      </div>

      <!-- Form -->
      <div class="p-6 space-y-5">
        <div>
          <label class="block text-sm font-semibold text-light mb-2">
            Email
          </label>
          <input
            type="email"
            placeholder="your@email.com"
            class="w-full px-4 py-3 bg-dark border border-gray-700 rounded-lg text-light placeholder-gray focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold text-light mb-2">
            Password
          </label>
          <input
            type="password"
            placeholder="••••••••"
            class="w-full px-4 py-3 bg-dark border border-gray-700 rounded-lg text-light placeholder-gray focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
          />
        </div>

        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center text-light-secondary cursor-pointer">
            <input type="checkbox" class="mr-2 accent-primary">
            Ingat saya
          </label>
          <a href="#" class="text-primary-light hover:text-primary hover:underline transition">
            Lupa password?
          </a>
        </div>

        <button class="w-full bg-gradient-to-r from-primary-dark to-primary text-light py-3 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-300 font-bold text-lg">
          Masuk
        </button>

        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-700"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-dark-secondary text-gray">atau</span>
          </div>
        </div>

        <p class="text-center text-sm text-light-secondary">
          Belum punya akun?
          <a href="#" class="text-primary-light hover:text-primary hover:underline font-semibold ml-1 transition">
            Daftar di sini
          </a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineEmits(['close'])
</script>

<style scoped>
.bg-dark {
  background-color: #0B090A;
}

.bg-dark-secondary {
  background-color: #161A1D;
}

.bg-primary {
  background-color: #BA181B;
}

.bg-primary-dark {
  background-color: #660708;
}

.bg-overlay {
  background-color: rgba(11, 9, 10, 0.85);
}

.text-primary {
  color: #BA181B;
}

.text-primary-light {
  color: #E5383B;
}

.text-light {
  color: #F5F3F4;
}

.text-light-secondary {
  color: #D3D3D3;
}

.text-gray {
  color: #B1A7A6;
}

.placeholder-gray::placeholder {
  color: #B1A7A6;
}

.border-gray-700 {
  border-color: #2a2d31;
}

.from-primary-dark {
  --tw-gradient-from: #660708;
}

.to-primary {
  --tw-gradient-to: #BA181B;
}

.focus\:ring-primary:focus {
  --tw-ring-color: #BA181B;
}

.accent-primary {
  accent-color: #BA181B;
}
</style>