<template>
    <div class="bg-gray-50 min-h-screen flex flex-col">
        
        <Header />

        <main class="flex-grow flex items-center justify-center py-12">
            <div class="w-full max-w-md px-4 sm:px-0">
                 <router-view /> 
            </div>
        </main>
        
        <Footer />
        
    </div>
</template>

<script setup>
// Menggunakan jalur yang telah dikoreksi (dua tingkat ke atas)
import Header from '../Header.vue'; 
</script>

<style scoped>
/* Opsional: Tambahkan styling khusus untuk Auth layout jika diperlukan */
</style>