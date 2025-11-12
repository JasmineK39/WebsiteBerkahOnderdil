<script setup>
import { computed } from 'vue'
import { useCartStore } from '../store/cart'

const cart = useCartStore()

// Format harga dengan aman
const formatPrice = (price) => {
  if (price === undefined || price === null) return '0'
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}

// Total harga dihitung langsung dari items
const total = computed(() =>
  cart.items.reduce((sum, item) => sum + (item.price ?? 0) * (item.qty ?? 1), 0)
)

// Fungsi checkout: buka WhatsApp tanpa pesan otomatis
const checkout = () => {
  const phoneNumber = '6281326553304'
  const message = 'Halo, saya ingin bertanya lebih lanjut terkait barang/proses checkout.'
  const encodedMessage = encodeURIComponent(message)

  window.open(`https://wa.me/${phoneNumber}?text=${encodedMessage}`, '_blank')
}

// Fungsi tambah/kurangi qty
const increaseQty = async (item) => {
  try {
    // Coba kirim ke API (kalau user login)
    await axios.post(`/api/cart`, {
      sparepart_id: item.id,
      quantity: 1
    })
    await fetchCart() // refresh isi cart dari backend
  } catch (err) {
    // Jika gagal (kemungkinan guest mode) → fallback ke local Pinia
    console.warn('Gagal menambah quantity di backend, gunakan local mode', err)
    cart.increaseQty(item.id)
  }
}

const decreaseQty = async (item) => {
  try {
    if (item.quantity > 1) {
      await axios.post(`/api/cart`, {
        sparepart_id: item.id,
        quantity: -1
      })
    } else {
      await axios.delete(`/api/cart/${item.id}`)
    }
    await fetchCart()
  } catch (err) {
    // Fallback ke local jika gagal koneksi/API
    console.warn('Gagal mengurangi quantity di backend, gunakan local mode', err)
    cart.decreaseQty(item.id)
  }
}
</script>

<template>
  <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Keranjang Belanja</h2>

    <!-- Tombol kembali ke katalog (tetap tampil di atas) -->
    <router-link
      to="/catalog"
      class="inline-block bg-red-700 text-white px-4 py-2 rounded hover:bg-gray-400 hover:text-black transition mb-4"
    >
      Kembali ke Katalog
    </router-link>

    <!-- Jika keranjang kosong -->
    <div v-if="cart.items.length === 0" class="text-center space-y-4">
      <p>Keranjang masih kosong.</p>
    </div>

<div v-else>
  <!-- Daftar item -->
  <div
    v-for="item in cart.items"
    :key="item.id"
    class="flex justify-between items-center border-b py-3"
  >
    <div class="flex items-center gap-2">
      <span class="font-medium">{{ item.name }}</span>

      <!-- Tombol Kurang -->
      <button
        @click="decreaseQty(item)"
        class="px-2 bg-gray-200 rounded hover:bg-gray-300 transition"
      >
        −
      </button>

      <!-- Jumlah -->
      <span>{{ item.quantity ?? item.qty }}</span>

      <!-- Tombol Tambah -->
      <button
        @click="increaseQty(item)"
        class="px-2 bg-gray-200 rounded hover:bg-gray-300 transition"
      >
        +
      </button>
    </div>

    <!-- Harga total per item -->
    <div>
      Rp {{ formatPrice((item.price ?? 0) * (item.quantity ?? item.qty ?? 1)) }}
    </div>
  </div>
</div>


      <!-- Total -->
      <div class="mt-4">
        <p class="text-xl font-semibold">
          Total: Rp {{ formatPrice(total) }}
        </p>
      </div>

      <!-- Pilih metode pembayaran -->
      <div class="mt-6">
      <h3 class="font-semibold mb-2">Pilih Metode Pembayaran:</h3>

      <div class="flex gap-4 flex-wrap">
        <label
          v-for="method in ['Transfer', 'COD', 'Temu Tengah']"
          :key="method"
          class="cursor-pointer inline-block px-4 py-2 rounded border transition"
          :class="cart.paymentMethod === method
            ? 'bg-red-700 text-white border-red-700'
            : 'bg-gray-200 text-black border-gray-300 hover:bg-gray-300'"
        >
          <input
            type="radio"
            name="payment"
            class="hidden"
            :value="method"
            v-model="cart.paymentMethod"
          />
          {{ method }}
        </label>
      </div>

      <!-- 🟢 Penjelasan metode pembayaran -->
      <transition name="fade">
        <div v-if="cart.paymentMethod" class="mt-4 p-4 bg-gray-100 rounded text-gray-800 leading-relaxed">
          <p v-if="cart.paymentMethod === 'Transfer'">
            <strong>Pembayaran Transfer:</strong> Setelah klik <strong>Checkout</strong>, tim kami akan mengirimkan nomor rekening resmi Berkah Onderdil. Pastikan bukti transfer disimpan ya!
          </p>
          <p v-else-if="cart.paymentMethod === 'COD'">
            <strong>Pembayaran COD:</strong> Barang akan dikirim langsung ke alamat Anda, dan Anda bisa membayar saat barang diterima.
          </p>
          <p v-else-if="cart.paymentMethod === 'Temu Tengah'">
            <strong>Temu Tengah:</strong> Anda bisa bertemu langsung dengan penjual untuk transaksi dan cek barang di tempat.
          </p>
        </div>
      </transition>
    </div>

      <!-- Tombol checkout dan kembali -->
      <div class="flex flex-col sm:flex-row gap-4 mt-6">
        <button
          class="flex-1 bg-green-600 text-white py-3 rounded hover:bg-green-700"
          @click="checkout"
        >
          Checkout akan terhubung ke WhatsApp
        </button>
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
