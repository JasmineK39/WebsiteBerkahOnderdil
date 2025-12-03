<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute(); // Digunakan untuk mendapatkan ID Transaksi
const isLoading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

// Asumsi form.value sudah terisi dari input pengguna
const form = ref({
    // ID transaksi diambil dari URL, misalnya /review/123
    transaksi_id: route.params.id, 
    // Anda harus mendapatkan sparepart_id dari data yang dimuat sebelumnya
    // UNTUK DEMO, kita asumsikan ID Sparepart 5
    sparepart_id: 5, 
    rating: 5, 
    comment: 'Pelayanan sangat cepat dan barang sesuai deskripsi.',
});

// Ganti fungsi lama ini
const submitForm = async () => {
    // Hilangkan alert, ganti dengan logic API
    // alert(`Terima kasih atas review Anda!\nRating: ${form.value.rating}`)
    
    isLoading.value = true;
    errorMessage.value = '';
    successMessage.value = '';

    try {
        const payload = {
            transaksi_id: form.value.transaksi_id,
            sparepart_id: form.value.sparepart_id,
            rating: form.value.rating,
            comment: form.value.comment,
        };

        const transaksiId = form.value.transaksi_id;

        const response = await axios.post(`/api/transaksi/${transaksiId}/review`, payload);
        
        successMessage.value = response.data.message;
        
        // Ganti alert dengan feedback UI yang lebih baik (pesan sukses)

        // Redirect ke riwayat pesanan setelah 3 detik
        setTimeout(() => {
            router.push({ path: '/orders' });
        }, 3000);

    } catch (error) {
        // Handle error seperti validasi atau duplikasi
        const errorMsg = error.response?.data?.message || 'Terjadi kesalahan saat menyimpan review.';
        errorMessage.value = `Gagal Submit: ${errorMsg}`;
        console.error("Review Submit Error:", error);
    } finally {
        isLoading.value = false;
    }
};

// ... kode dan setup lainnya (misalnya, mengambil detail transaksi untuk mendapatkan sparepart_id yang benar)
</script>

<template>
    <div class="p-4">
        <!-- Message Box -->
        <div v-if="successMessage" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            {{ errorMessage }}
        </div>
        
        <!-- Form Review -->
        <form @submit.prevent="submitForm" class="bg-white p-6 rounded-xl shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Berikan Review Anda</h1>
            
            <!-- Contoh input rating (asumsi Anda memiliki komponen rating) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Rating (1-5)</label>
                <input type="number" v-model.number="form.rating" min="1" max="5" required 
                       class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <!-- Contoh input komentar -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Komentar</label>
                <textarea v-model="form.comment" rows="4" 
                          class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            
            <!-- Tombol Submit -->
            <button type="submit" :disabled="isLoading"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150"
                    :class="{ 'opacity-50 cursor-not-allowed': isLoading }">
                <span v-if="isLoading">Menyimpan...</span>
                <span v-else>Kirim Review</span>
            </button>
        </form>
    </div>
</template>