<template>
  <header
    class="flex justify-between items-center bg-white border-b px-6 py-3 shadow-sm"
  >
    <div>
      <h1 class="text-2xl font-semibold text-gray-700">
        {{ title }}
      </h1>
    </div>

    <div class="flex items-center space-x-3">
      <span class="text-gray-600">Halo, Admin</span>
      <button
        @click="$emit('logout')"
        class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600"
      >
        Logout
      </button>
    </div>
  </header>
</template>

<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

// Judul otomatis berdasarkan path aktif
const title = computed(() => {
  if (route.path.includes("sparepart")) return "Manajemen Sparepart";
  if (route.path.includes("model-mobil")) return "Manajemen Model Mobil";
  return "Dashboard Admin";
});
</script>
