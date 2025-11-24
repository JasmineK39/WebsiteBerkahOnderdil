<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main content -->
    <main class="flex-1 flex flex-col">
      <!-- Header -->
      <AdminHeader @logout="logout" />

      <!-- Halaman admin ditampilkan di sini -->
      <section class="p-6 flex-1 overflow-y-auto">
        <RouterView />
      </section>
    </main>
  </div>
</template>

<script setup>
import AdminSidebar from "@/components/Admin/AdminSidebar.vue";
import AdminHeader from "@/components/Admin/AdminHeader.vue";

const logout = () => {
  alert("Logout berhasil (implementasi bisa disesuaikan nanti)");
};
</script>

<style scoped>
/* kamu bisa tambahkan styling tambahan di sini kalau perlu */
</style>
