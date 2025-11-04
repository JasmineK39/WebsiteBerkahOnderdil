<template>
  <div class="h-screen flex flex-col items-center justify-center bg-gray-100">
    <h1 class="text-6xl font-bold text-red-500 mb-4">404</h1>
    <p class="text-xl mb-6">Halaman admin tidak ditemukan</p>
    <RouterLink
      to="/admin"
      class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
    >
      Kembali ke Dashboard
    </RouterLink>
  </div>
</template>

<script setup>
// Tidak perlu logic tambahan kecuali mau cek role
</script>

<style scoped>
/* opsional styling tambahan */
</style>
