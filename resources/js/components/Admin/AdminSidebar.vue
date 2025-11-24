<template>
  <aside class="w-64 bg-white shadow-md flex flex-col">
    <div class="p-4 font-bold text-xl text-blue-700 border-b">
      Admin Panel
    </div>

    <nav class="flex-1 mt-4 space-y-2">
      <RouterLink
        to="/admin/spareparts"
        class="block px-4 py-2 hover:bg-blue-100 rounded"
        active-class="bg-blue-200 font-semibold"
      >
        Sparepart
      </RouterLink>

      <RouterLink
        to="/admin/models"
        class="block px-4 py-2 hover:bg-blue-100 rounded"
        active-class="bg-blue-200 font-semibold"
      >
        Model Mobil
      </RouterLink>

      <RouterLink
        to="/admin/requests"
        class="block px-4 py-2 hover:bg-blue-100 rounded"
        active-class="bg-blue-200 font-semibold"
      >
        Request
      </RouterLink>
    </nav>

    <footer class="p-4 border-t text-xs text-gray-500 text-center">
      © 2025 Berkah Onderdil
    </footer>
  </aside>
</template>

<script setup>
</script>
