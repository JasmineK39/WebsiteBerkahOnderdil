<template>
  <aside class="w-64 bg-white border-r min-h-screen flex flex-col">
    <div class="p-5 font-bold text-2xl text-[#BA181B] border-b">
      Admin Panel
    </div>

    <nav class="flex-1 mt-4 space-y-1">
      <RouterLink
        to="/admin"
        class="block px-5 py-2 rounded transition"
        :class="linkClass('/admin')">
        Dashboard
      </RouterLink>

      <RouterLink
        to="/admin/spareparts"
        class="block px-5 py-2 rounded transition"
        :class="linkClass('/admin/spareparts')">
        Sparepart
      </RouterLink>

      <RouterLink
        to="/admin/models"

        class="block px-5 py-2 rounded transition"
        :class="linkClass('/admin/models')">
        Model Mobil
      </RouterLink>

      <RouterLink
        to="/admin/requests"

        class="block px-5 py-2 rounded transition"
        :class="linkClass('/admin/requests')"      >
        Request
      </RouterLink>
    </nav>


    <footer class="p-4 border-t text-xs text-gray-400 text-center">
      © 2025 Berkah Onderdil
    </footer>
  </aside>
</template>

<script setup>
import { useRoute } from "vue-router";
const route = useRoute();

const linkClass = (path) => {
  return route.path.startsWith(path)
    ? "bg-[#BA181B] text-white font-semibold"
    : "text-gray-700 hover:bg-gray-100";
};
</script>
