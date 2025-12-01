<script setup>
import { computed, ref, onMounted } from 'vue' 
import { useCartStore } from '../store/cart'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const cart = useCartStore()

// State untuk Error Handling & Loading
const checkoutError = ref(null)
const isClearing = ref(false)
const clearMessage = ref('')
const isConfirmModalOpen = ref(false)
const isRemovingItem = ref(null) // Untuk melacak ID item yang sedang dihapus

// Format harga dengan aman
const formatPrice = (price) => {
    if (price === undefined || price === null) return '0'
    const numericPrice = parseFloat(price);
    if (isNaN(numericPrice)) return '0';
    return numericPrice.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}


// Total harga dihitung langsung dari items
const total = computed(() =>
    cart.items.reduce((sum, item) => sum + (item.price ?? 0) * (item.quantity ?? 1), 0)
)

// Fungsi untuk mengambil keranjang dari backend (wajib jika menggunakan backend storage)
const fetchCart = async () => {
    try {
        // Data keranjang akan diambil dan disimpan oleh action Pinia.
        cart.fetchCart();
    } catch (error) {
        console.error("Gagal memuat keranjang dari backend:", error)
    }
}

onMounted(() => {
    cart.fetchCart() 
})

// === FUNGSI BARU: HAPUS SATU ITEM DARI KERANJANG ===
const removeItem = async (item) => {
    isRemovingItem.value = item.id; // Set loading state untuk item ini
    clearMessage.value = '';
    
    try {
        // Panggil endpoint DELETE /api/cart/{sparepartId}
        const response = await axios.delete(`/api/cart/${item.id}`);
        
        clearMessage.value = response.data.message;
        
        // Panggil fetchCart untuk memperbarui Pinia store dari backend
        await cart.fetchCart(); 

    } catch (error) {
        const errorMsg = error.response?.data?.message || 'Gagal menghapus item karena kesalahan server.';
        clearMessage.value = `Error: ${errorMsg}`;
        console.error("Remove Item Error:", error);
    } finally {
        isRemovingItem.value = null; // Hapus loading state
    }
}


// === FUNGSI CHECKOUT DAN CLEAR CART LAMA (DIPERTAHANKAN) ===

const openConfirmModal = () => {
    isConfirmModalOpen.value = true
    clearMessage.value = '' 
}

const closeConfirmModal = () => {
    isConfirmModalOpen.value = false
}

const clearCart = async () => {
    closeConfirmModal(); 

    isClearing.value = true;
    clearMessage.value = '';

    try {
        const response = await axios.delete('/api/cart');
        
        clearMessage.value = response.data.message;
        
        cart.items = [] // Kosongkan store Pinia
        
    } catch (error) {
        const errorMsg = error.response?.data?.message || 'Gagal mengosongkan keranjang karena kesalahan server.';
        clearMessage.value = `Error: ${errorMsg}`;
        console.error("Clear Cart Error:", error);
    } finally {
        isClearing.value = false;
    }
};

const checkout = async () => {
    checkoutError.value = null; 

    if (!cart.items || cart.items.length === 0) {
        checkoutError.value = 'Keranjang Anda kosong.';
        return;
    }
    
    if (!cart.paymentMethod) {
        checkoutError.value = 'Silakan pilih metode pembayaran.';
        return;
    }

    try {
        const res = await axios.post('/api/checkout', {
            payment_method: cart.paymentMethod, 
            address: 'Contoh Alamat Pengiriman', 
            
            items: cart.items.map(item => ({
                sparepart_id: item.id,
                price: item.price,
                quantity: item.quantity, 
            })),
        })

        if (!res.data.transaksi || !res.data.transaksi.id) {
            throw new Error("Respons checkout tidak valid: ID Transaksi tidak ditemukan.");
        }
        
        const transaksiId = res.data.transaksi.id
        
        cart.items = [] // Kosongkan keranjang lokal
        router.push(`/checkout-confirm/${transaksiId}`)
        
    } catch (err) {
        console.error("Checkout gagal:", err.response || err);
        
        if (err.response && err.response.status === 422) {
            checkoutError.value = 'Validasi gagal. Cek field yang wajib diisi.'
        } else if (err.response && err.response.data && err.response.data.message) {
            checkoutError.value = err.response.data.message;
        } else {
            checkoutError.value = 'Checkout gagal: Terjadi kesalahan server.';
        }
    }
}

// Fungsi tambah/kurangi qty
const increaseQty = async (item) => {
    try {
        await axios.post(`/api/cart`, { sparepart_id: item.id, quantity: 1 })
        await cart.fetchCart() 
    } catch (err) {
        console.warn('Gagal menambah quantity di backend, gunakan local mode', err)
        cart.increaseQty(item.id) 
    }
}

const decreaseQty = async (item) => {
   try {
    if (item.quantity > 1) { 
        await axios.post(`/api/cart`, { sparepart_id: item.id, quantity: -1 })
    } else {
        // Jika kuantitas 1 dan dikurangi, kita panggil fungsi hapus item yang baru
        // Walaupun logika di atas sudah benar, memanggil removeItem lebih eksplisit.
        await removeItem(item);
    }
    // Jika removeItem dipanggil di atas, fetchCart akan diurus oleh removeItem
    // Jika post dipanggil, kita tetap perlu fetchCart
    if (item.quantity > 1) {
        await cart.fetchCart() 
    }
    } catch (err) {
        console.warn('Gagal mengurangi quantity di backend, gunakan local mode', err)
        cart.decreaseQty(item.id)
    }
}
</script>

<template>
 <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-xl mt-8">
  <h2 class="text-3xl font-extrabold text-red-700 mb-6 border-b pb-2">Keranjang Belanja Anda</h2>

  <!-- Tombol kembali ke katalog -->
  <router-link
  to="/catalog"
  class="inline-flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition mb-4"
  >
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
      </svg>
      Kembali Belanja
  </router-link>
  
  <!-- Tombol Kosongkan Keranjang -->
  <div v-if="cart.items.length > 0" class="flex justify-end mb-4">
    <button @click="openConfirmModal" :disabled="isClearing"
        class="flex items-center space-x-2 text-sm text-red-600 font-semibold py-1 px-3 border border-red-300 rounded-full hover:bg-red-50 transition duration-200 ease-in-out">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        <span>Kosongkan Keranjang</span>
    </button>
  </div>

    <!-- Notifikasi Error Checkout atau Clear Cart -->
    <div v-if="checkoutError || clearMessage" 
         :class="{'bg-red-100 text-red-700 border-red-300': checkoutError || clearMessage.includes('Error'), 'bg-green-100 text-green-700 border-green-300': clearMessage && !clearMessage.includes('Error')}"
         class="mb-4 p-3 border rounded-lg font-medium">
        {{ checkoutError || clearMessage }}
    </div>
    
  <!-- Jika keranjang kosong -->
  <div v-if="cart.items.length === 0" class="text-center py-10 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
    <p class="text-gray-500 text-lg">Keranjang Anda kosong. Yuk, temukan sparepart terbaik!</p>
  </div>

    <!-- Daftar item -->
    <div v-else>
        <div
            v-for="item in cart.items"
            :key="item.id"
            class="flex flex-col sm:flex-row justify-between items-center border-b py-4 hover:bg-gray-50 transition duration-100"
        >
            <div class="flex items-center gap-4 w-full sm:w-1/2 mb-2 sm:mb-0">
                <!-- Image Placeholder (if image exists) -->
                <img :src="item.image || 'https://placehold.co/60x60/f87171/ffffff?text=Part'" 
                    alt="Sparepart" 
                    class="w-12 h-12 object-cover rounded" />

                <!-- Nama & Harga Satuan -->
                <div>
                    <span class="font-semibold text-gray-800">{{ item.name }}</span>
                    <p class="text-sm text-gray-500">Rp {{ formatPrice(item.price) }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between sm:justify-end w-full sm:w-1/2 gap-3">
                
                <!-- Kontrol Kuantitas -->
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                    <!-- Tombol Kurang -->
                    <button
                        @click="decreaseQty(item)"
                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 transition text-lg disabled:opacity-50"
                        :disabled="item.quantity <= 1 && item.quantity"
                    >
                        −
                    </button>

                    <!-- Jumlah (menggunakan item.quantity) -->
                    <span class="px-3 text-center w-8 font-medium">{{ item.quantity }}</span>

                    <!-- Tombol Tambah -->
                    <button
                        @click="increaseQty(item)"
                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 transition text-lg"
                    >
                        +
                    </button>
                </div>

                <!-- Harga total per item -->
                <div class="text-lg font-bold text-red-600 w-28 text-right hidden sm:block">
                    Rp {{ formatPrice(item.price * item.quantity) }}
                </div>
                
                <!-- Tombol Hapus per Item -->
                <button
                    @click="removeItem(item)"
                    :disabled="isRemovingItem === item.id"
                    class="p-2 ml-4 text-gray-400 rounded-full hover:bg-red-100 hover:text-red-500 transition disabled:opacity-50"
                    title="Hapus Item"
                >
                    <svg v-if="isRemovingItem !== item.id" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <!-- Loading Spinner (Opsional) -->
                    <svg v-else class="w-5 h-5 animate-spin text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </div>
        </div>
    </div>

<!-- Total -->
<div v-if="cart.items.length > 0" class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
<p class="text-2xl font-bold text-gray-800">
Total Belanja: <span class="text-red-700">Rp {{ formatPrice(total) }}</span>
</p>
</div>

<!-- Pilih metode pembayaran -->
<div v-if="cart.items.length > 0" class="mt-6 p-4 bg-gray-50 rounded-lg">
<h3 class="font-bold text-lg mb-3 text-gray-800">Pilih Metode Pembayaran:</h3>

<div class="flex gap-3 flex-wrap">
<label
v-for="method in ['Transfer', 'Cash']"
:key="method"
 @click="cart.setPaymentMethod(method)"
 class="cursor-pointer inline-block px-4 py-2 rounded-full border-2 transition duration-200"
:class="cart.paymentMethod === method
 ? 'bg-red-700 text-white border-red-700 shadow-md'
 : 'bg-white text-black border-gray-300 hover:border-red-500'"
>
 <input
type="radio"
 name="payment"
 class="hidden"
 :value="method"
  :checked="cart.paymentMethod === method"
 />
 {{ method }}
 </label>
 </div>

 <!-- Penjelasan metode pembayaran -->
 <transition name="fade">
 <div v-if="cart.paymentMethod" class="mt-4 p-4 bg-red-50 rounded-lg text-gray-700 leading-relaxed text-sm border border-red-200">
 <p v-if="cart.paymentMethod === 'Transfer'">
 <strong>Pembayaran Transfer:</strong> Setelah klik <strong>Checkout</strong>, tim kami akan mengirimkan nomor rekening resmi Berkah Onderdil. Pastikan bukti transfer disimpan ya!
 </p>
 <p v-else-if="cart.paymentMethod === 'Cash'">
 <strong>Pembayaran Cash:</strong> Pembayaran cash hanya untuk transaksi langsung di toko atau COD di alamat yang anda cantumkan.
</p>
</div>
 </transition>
 </div>

    <!-- Tombol checkout -->
        <div v-if="cart.items.length > 0" class="mt-6">
            <button
                class="w-full bg-red-600 text-white font-bold tracking-wider py-3 rounded-xl hover:bg-black transition duration-300 transform hover:scale-[1.01]"
                @click="checkout"
                >
                PROSES KE CHECKOUT SEKARANG
            </button>
        </div>
        
    <br v-if="cart.items.length > 0"></br>
    
    <!-- Bagian Riwayat Pesanan -->
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-sm w-full border border-gray-100 mx-auto"
         :class="{'mt-8': cart.items.length === 0}">
        <h2 class="text-lg font-bold text-gray-800 mb-2">Akses Riwayat Pesanan</h2>
        <p class="text-sm text-gray-600 mb-4">
            Lihat semua transaksi Anda yang telah selesai dan status pengiriman.
        </p>
        <router-link to="/orders" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150 w-full justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-9 0V3h4v2m-4 0h4"></path></svg>
            Lihat Riwayat Pesanan Anda
        </router-link>
    </div>
    
    <!-- Custom Modal Konfirmasi -->
    <div v-if="isConfirmModalOpen" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-100">
            <h3 class="text-xl font-bold mb-4 text-red-600">Konfirmasi Penghapusan</h3>
            <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus SEMUA item dari keranjang belanja Anda?</p>
            <div class="flex justify-end space-x-3">
                <button @click="closeConfirmModal" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
                <button @click="clearCart" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Ya, Kosongkan Keranjang
                </button>
            </div>
        </div>
    </div>

 </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
 transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
 opacity: 0;
}
</style>