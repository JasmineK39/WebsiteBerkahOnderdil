<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

// Data yang dimuat dari API
const transaksi = ref(null)

// State lokal untuk input (dipecah menjadi ref terpisah untuk menghindari error kompilasi)
const address = ref('')
const paymentMethod = ref('Transfer')

const isLoading = ref(false)
const errorMessage = ref(null)

// Daftar metode pembayaran yang diizinkan (Sync dengan Database)
const allowedPaymentMethods = ['Transfer', 'Cash']
const paymentDisplayNames = {
    'Transfer': 'Transfer Bank',
    'Cash': 'Bayar Tunai'
}

const formatRupiah = (value) => {
    if (value === null || value === undefined) return '0';
    return Number(value).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
};

/**
 * Memuat detail transaksi berdasarkan ID dari rute.
 */
onMounted(async () => {
  const transactionId = route.params.id;
    if (!transactionId) {
        errorMessage.value = "ID Transaksi tidak ditemukan di URL. Tidak bisa memuat data.";
        return;
    }

    try {
        const res = await axios.get(`/api/transaction/${route.params.id}`)
        transaksi.value = res.data
        
        // Inisialisasi input dengan data yang ada di transaksi (jika ada)
        if (transaksi.value) {
            address.value = transaksi.value.address || '';
            paymentMethod.value = transaksi.value.payment_method || 'Transfer';
        }

    } catch (err) {
        console.error("Gagal memuat detail transaksi:", err)
        errorMessage.value = "Gagal memuat detail transaksi. Cek ID Transaksi atau koneksi API."
    }
})

/**
 * Mengirim pembaruan alamat dan metode pembayaran ke backend
 * (Ini menggantikan tombol "Checkout" Anda, karena ini adalah finalisasi data)
 */
async function finalizeTransaction() {
    console.log("Fungsi finalizeTransaction dipanggil.");
    isLoading.value = true
    errorMessage.value = null
    
    // Validasi Sederhana
    if (!address.value) {
        errorMessage.value = "Alamat wajib diisi untuk finalisasi pesanan."
        isLoading.value = false
        return
    }

    try {
        const payload = {
            address: address.value,
            payment_method: paymentMethod.value, 
        }

        const res = await axios.put(`/api/transaction/${route.params.id}/finalize`, payload) 
        
        transaksi.value = res.data.transaksi
        
        if (res.data.whatsapp_link) {
            window.location.href = res.data.whatsapp_link;
        } else {
            errorMessage.value = "Transaksi berhasil diperbarui, tetapi link WhatsApp tidak tersedia."
        }

        setTimeout(() => {
                router.push({ path: res.data.order_history_link || '/orders' });
            }, 2000); 

        } catch (err) {
            isLoading.value = false
            const errorDetail = err.response?.data?.message || err.message
            errorMessage.value = `Finalisasi Gagal: ${errorDetail}`
            console.error("Finalisasi Error:", err)
        }
}

</script>

<template>
    <div class="max-w-4xl mx-auto p-6 space-y-8">
        
        <!-- Notifikasi Error -->
        <div v-if="errorMessage" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
            <p class="font-bold">Perhatian!</p>
            <p>{{ errorMessage }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-xl">
            <h2 class="text-3xl font-extrabold text-gray-800 border-b pb-3 mb-5">Konfirmasi Pesanan</h2>

            <div v-if="transaksi" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Ringkasan Transaksi -->
                <div>
                    <p class="mb-2"><strong>Status:</strong> <span class="font-semibold text-orange-500">{{ transaksi.status?.toUpperCase() }}</span></p>
                    <p class="mb-4"><strong>Metode Pembayaran Awal:</strong> {{ paymentDisplayNames[transaksi.payment_method] || transaksi.payment_method }}</p>
                    
                    <div class="p-3 bg-red-100 rounded-lg shadow-inner">
                        <p class="text-xl font-bold">Total Pembayaran:</p>
                        <!-- Menggunakan 'total_amount' sesuai struktur migrasi transaksi -->
                        <p class="text-3xl font-extrabold text-red-700">Rp {{ formatRupiah(transaksi.total_amount) }}</p>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Barang -->
                <div>
                    <h3 class="text-xl font-bold border-b pb-2 text-gray-700">Detail Barang</h3>
                    <ul class="mt-4 space-y-3 max-h-60 overflow-y-auto">
                        <li v-for="item in transaksi.items" :key="item.id" class="flex justify-between items-center p-2 border-b">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ item.sparepart.name }}</p>
                            </div>
                            <div class="text-right ml-4">
                                <p class="text-sm text-gray-600">
                                    {{ item.quantity }} × Rp {{ formatRupiah(item.price) }}
                                </p>
                                <p class="font-bold text-gray-800">
                                    Subtotal: Rp {{ formatRupiah(transaksi.total_amount) }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div v-else class="text-center py-10 text-gray-500">
                <p>Memuat detail transaksi...</p>
            </div>
        </div>
        
        <!-- Form Finalisasi Alamat & Pembayaran -->
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <h3 class="text-2xl font-bold mb-4 text-gray-800">Alamat</h3>

            <!-- Form Input Alamat -->
            <div class="space-y-4 mb-6">
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Isi dengan Alamat Lengkap</label>
                    <textarea 
                        id="address" 
                        v-model="address" 
                        rows="3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 p-2 border"
                    ></textarea>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-between items-center pt-4 border-t">
            <!-- Tombol 1: Checkout (WA) -->
            <button
                @click="finalizeTransaction"
                :disabled="isLoading || !transaksi"
                class="inline-flex items-center bg-green-600 text-white font-bold px-6 py-3 rounded-lg transition duration-300 ease-in-out hover:bg-green-700 disabled:opacity-50 shadow-lg shadow-green-300"
            >
                <span v-if="isLoading">Memproses...</span>
                <span v-else>Diskusikan dengan Seller (WhatsApp)</span>
            </button>
            
            <!-- Link Kembali ke Katalog -->
            <router-link
                to="/catalog"
                class="inline-block bg-gray-200 text-gray-800 font-bold px-6 py-3 rounded-lg hover:bg-gray-300 transition"
            >
                Kembali Ke Katalog
            </router-link>
        </div>
    </div>
</template>