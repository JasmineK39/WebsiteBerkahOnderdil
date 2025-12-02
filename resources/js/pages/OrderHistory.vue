<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const orders = ref([]);
const isLoading = ref(true);
const errorMessage = ref('');
const currentPage = ref(1);
const totalPages = ref(1);

const router = useRouter();

async function fetchOrders(page = 1) {
    isLoading.value = true;
    errorMessage.value = '';
    
    try {
        // Panggil endpoint API yang sudah dibuat
        const response = await axios.get(`/api/orders?page=${page}`);
        
        // Data paginasi berada di level root respons JSON dari Laravel
        orders.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
        
    } catch (error) {
        console.error("Gagal mengambil riwayat pesanan:", error);
        errorMessage.value = 'Gagal memuat riwayat pesanan. Silakan coba lagi.';
    } finally {
        isLoading.value = false;
    }
};

// Fungsi untuk navigasi paginasi
const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        fetchOrders(page);
    }
};

// Fungsi format Rupiah
const formatRupiah = (number) => {
    if (number === null || number === undefined) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};

// Fungsi sederhana untuk menentukan warna status Tailwind
const getStatusClass = (status) => {
    switch (status) {
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'shipped':
            return 'bg-blue-100 text-blue-800';
        case 'paid':
            return 'bg-yellow-100 text-yellow-800';
        case 'pending':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-red-100 text-red-800';
    }
};

// Ambil data saat komponen dimuat
onMounted(() => {
    fetchOrders();
});
</script>

<template>
    <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-xl p-6">
            <h1 class="text-3xl font-extrabold mb-6 text-gray-900 border-b pb-3">Riwayat Pesanan Anda</h1>

            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center items-center h-40">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-3 text-gray-600">Memuat data pesanan...</span>
            </div>

            <!-- Error State -->
            <div v-else-if="errorMessage" class="p-4 bg-red-100 text-red-700 border border-red-400 rounded-lg">
                <p class="font-semibold">{{ errorMessage }}</p>
            </div>

            <!-- No Orders State -->
            <div v-else-if="orders.length === 0" class="text-center p-10 bg-gray-50 rounded-lg border-dashed border-2 border-gray-300">
                <p class="text-xl text-gray-500 font-medium mb-3">Anda belum memiliki riwayat pesanan yang sudah selesai.</p>
                <router-link to="/catalog" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-semibold transition duration-150">
                    Mulai Belanja Sekarang &rarr;
                </router-link>
            </div>

            <!-- Data Orders -->
            <div v-else class="space-y-6">
                <div v-for="order in orders" :key="order.id"
                     class="border border-gray-200 rounded-xl p-5 transition duration-300 ease-in-out hover:shadow-lg bg-white">
                    
                    <!-- Header Transaksi -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-3 border-b border-gray-100 mb-4">
                        <div class="flex items-center space-x-4 mb-2 md:mb-0">
                            <span class="text-sm font-semibold text-gray-500">
                                ID Transaksi: <span class="text-gray-800 font-bold">#{{ order.id }}</span>
                            </span>
                            <span :class="['px-3 py-1 text-xs rounded-full font-medium shadow-sm', getStatusClass(order.status)]">
                                {{ order.status.toUpperCase() }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-600">
                            Tanggal Pesan: {{ new Date(order.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) }}
                        </span>
                    </div>

                    <!-- Detail Utama & Item List -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Kolom Detail Ringkas -->
                        <div class="col-span-1 lg:col-span-1 border-r lg:pr-6 pr-0 border-gray-100">
                            <div class="mb-3">
                                <p class="text-xs font-semibold text-gray-500">Total Pembayaran</p>
                                <p class="text-2xl font-extrabold text-blue-700">{{ formatRupiah(order.total_amount) }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-xs font-semibold text-gray-500">Metode Bayar</p>
                                <p class="text-sm font-medium text-gray-700">{{ order.payment_method || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500">Alamat Kirim</p>
                                <p class="text-sm text-gray-700 line-clamp-2">{{ order.address || 'Alamat tidak tersedia' }}</p>
                            </div>
                        </div>

                        <!-- Kolom Daftar Barang (Item List) -->
                        <div class="col-span-1 lg:col-span-2">
                            <p class="text-sm font-semibold text-gray-600 mb-2 border-b pb-1">Detail Barang ({{ order.items.length || 0 }} Jenis)</p>
                            <div class="space-y-2 max-h-40 overflow-y-auto pr-2">
                                <!-- PERBAIKAN: Nested loop untuk menampilkan item -->
                                <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm">
                                    <p class="text-gray-800 grow pr-2">
                                        {{ item.sparepart.name || 'Nama Barang Tidak Diketahui' }} 
                                        <span class="text-xs text-gray-500">({{ item.quantity }} pcs)</span>
                                    </p>
                                    <p class="font-semibold text-right shrink-0">{{ formatRupiah(item.subtotal) }}</p>
                                </div>
                                <p v-if="!order.items || order.items.length === 0" class="text-sm text-gray-400 italic">Tidak ada detail barang.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Aksi -->
                    <div class="flex justify-end pt-4 border-t border-gray-100 mt-4 space-x-4">
                        <router-link v-if="order.status === 'completed' && (!order.reviews || order.reviews.length === 0)" 
                                     :to="`/review/${order.id}`" 
                                     class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-300 font-semibold text-sm transition duration-150">
                            Tulis Review
                        </router-link>

                        <!-- Tanda sudah diulas -->
                        <span v-else-if="order.status === 'completed' && order.reviews && order.reviews.length > 0"
                              class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
                            &#10003; Sudah Diulas
                        </span>
                    </div>

                </div>

                <!-- Paginasi -->
                <div class="flex justify-center mt-8 space-x-2" v-if="totalPages > 1">
                    <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                            class="px-4 py-2 border rounded-md text-sm font-medium transition"
                            :class="currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-100 border-gray-300'">
                        &larr; Sebelumnya
                    </button>
                    
                    <span class="px-4 py-2 text-sm font-medium text-gray-700">
                        Halaman {{ currentPage }} dari {{ totalPages }}
                    </span>

                    <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                            class="px-4 py-2 border rounded-md text-sm font-medium transition"
                            :class="currentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-100 border-gray-300'">
                        Berikutnya &rarr;
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>