<template>
  <div class="flex min-h-screen bg-[#F5F5F5]">
    
    <AdminSidebar />

    <main class="flex-1 flex flex-col">

      <AdminHeader @logout="logout" />

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
  localStorage.removeItem("token");
  localStorage.removeItem("user");

  window.location.href = "/login";
};
</script>