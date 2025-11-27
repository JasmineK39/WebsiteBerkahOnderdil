<template>
  <header class="flex justify-between items-center bg-white border-b px-6 py-4 shadow-sm">
    
    <h1 class="text-xl font-semibold text-[#BA181B]">
      {{ computedTitle }}
    </h1>

    <div class="flex items-center gap-4">
      <span class="text-gray-600">Halo, Admin</span>

      <button
        @click="$emit('logout')"
        class="px-4 py-2 bg-[#BA181B] text-white rounded-lg hover:bg-[#d62828] transition"
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

const computedTitle = computed(() => {
  if (route.path.includes("spareparts")) return "Kelola Sparepart";
  if (route.path.includes("models")) return "Kelola Model Mobil";
  if (route.path.includes("requests")) return "Daftar Request";
  return "Dashboard Admin";
});
</script>
