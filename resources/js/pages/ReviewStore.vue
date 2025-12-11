<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute(); 
const isLoading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const transactionDetails = ref(null); 
const itemsToReview = ref([]); // Data item dari API (hanya yang belum diulas)
const reviewForms = ref([]); // Array baru untuk menampung formulir ulasan untuk setiap item
const hoverRating = ref(0); // State untuk efek hover bintang global

// Teks deskripsi untuk setiap rating (Indonesian)
const ratingDescriptions = [
    '', // Index 0 tidak terpakai
    'Sangat Buruk', // Rating 1
    'Buruk', // Rating 2
    'Cukup Baik', // Rating 3
    'Baik', // Rating 4
    'Sangat Baik' // Rating 5
];

const transaksiId = route.params.id;

// 1. Ambil detail transaksi dan item yang bisa diulas
const fetchTransactionDetail = async () => {
    isLoading.value = true;
    errorMessage.value = '';

    try {
        const response = await axios.get(`/api/transaksi/${transaksiId}/review-details`);
        
        transactionDetails.value = response.data;
        
        const allItems = response.data?.items || [];
        
        itemsToReview.value = allItems.filter(item => !item.is_reviewed);

        if (itemsToReview.value.length > 0) {
            // MAPPING: Ubah data item API menjadi array form ulasan
            reviewForms.value = itemsToReview.value.map(item => ({
                transaksi_id: transaksiId, 
                sparepart_id: item.sparepart_id,
                name: item.sparepart.name,
                quantity: item.quantity,
                rating: 5, // Default rating 5
                comment: '',
                isSubmitted: false, // Tambahkan status untuk pelacakan
            }));

        } else {
             errorMessage.value = 'Semua item dalam transaksi ini sudah Anda ulas.';
        }

    } catch (error) {
        console.error("Fetch Transaction Detail Error:", error);
        errorMessage.value = error.response?.data?.message || 'Gagal memuat detail transaksi.';
    } finally {
        isLoading.value = false;
    }
};

const submitForm = async () => {
    isLoading.value = true;
    errorMessage.value = '';
    successMessage.value = '';

    // Filter ulasan yang belum disubmit dan memiliki rating valid
    const reviewsToSubmit = reviewForms.value.filter(form => 
        !form.isSubmitted && form.rating >= 1 && form.rating <= 5
    );

    if (reviewsToSubmit.length === 0) {
        if (reviewForms.value.length > 0) {
            errorMessage.value = 'Tidak ada item yang perlu diulas atau semua sudah disubmit.';
        } else {
            errorMessage.value = 'Tidak ada item yang perlu diulas.';
        }
        isLoading.value = false;
        return;
    }

    // 2. Kirim setiap ulasan satu per satu (Parallel submission)
    const submissionPromises = reviewsToSubmit.map(formItem => {
        const payload = {
            sparepart_id: formItem.sparepart_id,
            rating: formItem.rating,
            comment: formItem.comment,
        };

        return axios.post(`/api/transaksi/${transaksiId}/review`, payload)
            .then(() => {
                formItem.isSubmitted = true; // Tandai sukses
                return { success: true, name: formItem.name };
            })
            .catch(error => {
                const errorMsg = error.response?.data?.message || `Gagal submit ulasan untuk ${formItem.name}.`;
                return { success: false, name: formItem.name, message: errorMsg };
            });
    });

    const results = await Promise.all(submissionPromises);

    // 3. Proses Hasil
    const successfulSubmissions = results.filter(r => r.success).length;
    const failedSubmissions = results.filter(r => !r.success);

    if (successfulSubmissions > 0) {
        let msg = `Berhasil menyimpan ${successfulSubmissions} ulasan.`;
        if (failedSubmissions.length > 0) {
             msg += ` Namun, ${failedSubmissions.length} ulasan gagal disubmit.`;
        } else {
            // Jika semua sukses, hapus semua item dari reviewForms
            reviewForms.value = []; 
            
            successMessage.value = msg;
            // Redirect setelah semua sukses
            setTimeout(() => {
                router.push({ path: '/orders' });
            }, 3000);
        }
        successMessage.value = msg;

    } 
    
    if (failedSubmissions.length > 0) {
        errorMessage.value = 'Beberapa ulasan gagal disimpan: ' + 
                             failedSubmissions.map(f => `${f.name} (${f.message})`).join('; ');
        // Perbarui daftar formulir untuk hanya menampilkan yang gagal
        reviewForms.value = reviewForms.value.filter(form => !form.isSubmitted);
    }
    
    isLoading.value = false;
};

// Fungsi untuk menangani interaksi bintang
const setRating = (formItem, rating) => {
    formItem.rating = rating;
};

onMounted(fetchTransactionDetail);
</script>

<template>
    <div class="p-4 flex justify-center">
        <div class="w-full max-w-2xl">
            <!-- Message Box -->
            <div v-if="successMessage" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                {{ errorMessage }}
            </div>
            
            <!-- Form Review -->
            <form @submit.prevent="submitForm" class="bg-white p-6 rounded-xl shadow-2xl">
                <h1 class="text-3xl font-extrabold mb-6 text-center text-blue-800">Beri Penilaian & Ulasan Produk</h1>

                <div v-if="isLoading && itemsToReview.length === 0" class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 inline-block"></div>
                    <p class="mt-2 text-gray-500">Memuat detail transaksi...</p>
                </div>

                <div v-else-if="itemsToReview.length === 0 && !isLoading && !errorMessage" class="p-6 text-center text-gray-600">
                    <p>Semua item dalam transaksi ini sudah Anda ulas. Terima kasih!</p>
                </div>
                
                <fieldset :disabled="isLoading" v-else>
                    <p class="mb-6 text-gray-600 text-center">Anda memiliki <strong>{{ reviewForms.length }} item</strong> dari transaksi ID: <span class="font-mono bg-yellow-100 px-1 rounded">{{ transaksiId }}</span> yang menunggu ulasan Anda.</p>
                    
                    <!-- Loop untuk setiap item yang akan diulas -->
                    <div v-for="(formItem, index) in reviewForms" :key="formItem.sparepart_id" 
                         class="mb-8 p-5 border border-gray-200 rounded-lg shadow-md transition duration-150 hover:shadow-lg bg-white">
                        
                        <!-- Nama Item -->
                        <h2 class="text-xl font-bold text-gray-800 mb-3 border-b pb-2">
                            {{ index + 1 }}. {{ formItem.name }} 
                            <span class="text-sm font-normal text-gray-500">({{ formItem.quantity }} unit)</span>
                        </h2>

                        <!-- Input Rating INTERAKTIF (Star Rating) -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Penilaian Anda:</label>
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex space-x-1"
                                     @mouseleave="hoverRating = 0">
                                    <!-- Iterasi 5 Bintang -->
                                    <template v-for="n in 5" :key="n">
                                        <span 
                                            @click="setRating(formItem, n)"
                                            @mouseover="hoverRating = n"
                                            :class="{ 
                                                'text-yellow-500': n <= (hoverRating || formItem.rating),
                                                'text-gray-300': n > (hoverRating || formItem.rating),
                                                'hover:text-yellow-600 transition-colors duration-150': true
                                            }"
                                            class="text-3xl cursor-pointer">
                                            ★
                                        </span>
                                    </template>
                                </div>
                                <!-- Keterangan Rating (Feedback Visual) -->
                                <p class="text-md font-medium text-blue-600">
                                    {{ ratingDescriptions[hoverRating || formItem.rating] }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Input Komentar -->
                        <div class="mb-2">
                            <label class="block text-gray-700 text-sm font-semibold mb-2" :for="`comment-${formItem.sparepart_id}`">Komentar Produk (Opsional)</label>
                            <textarea v-model="formItem.comment" :id="`comment-${formItem.sparepart_id}`" rows="2" placeholder="Tulis ulasan Anda tentang sparepart ini..."
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150"></textarea>
                        </div>
                    </div>
                    
                    <!-- Tombol Submit Akhir -->
                    <button type="submit" :disabled="isLoading || reviewForms.length === 0"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 px-4 rounded-xl shadow-lg transition duration-200 transform hover:scale-[1.01] focus:outline-none focus:shadow-outline"
                            :class="{ 'opacity-50 cursor-not-allowed': isLoading || reviewForms.length === 0 }">
                        <span v-if="isLoading">Mengirim {{ reviewForms.length }} Ulasan...</span>
                        <span v-else>Kirim Semua Ulasan ({{ reviewForms.length }})</span>
                    </button>
                </fieldset>
            </form>
        </div>
    </div>
</template>