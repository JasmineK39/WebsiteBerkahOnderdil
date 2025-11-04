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
  const phoneNumber = '081326553304' // pastikan format internasional tanpa tanda + dan 0 di depan
  const message = 'Halo, saya ingin checkout.'
  const encodedMessage = encodeURIComponent(message)

  window.open(`https://wa.me/${phoneNumber}?text=${encodedMessage}`, '_blank')
}

// Fungsi tambah/kurangi qty
const increaseQty = (itemId) => {
  cart.increaseQty(itemId)
}

const decreaseQty = (itemId) => {
  cart.decreaseQty(itemId)
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

    <!-- Jika ada isi keranjang -->
    <div v-else>
      <!-- Daftar item -->
      <div
        v-for="item in cart.items"
        :key="item.id"
        class="flex justify-between items-center border-b py-3"
      >
        <div class="flex items-center gap-2">
          <span>{{ item.name }}</span>
          <button
            @click="decreaseQty(item.id)"
            class="px-2 bg-gray-200 rounded hover:bg-gray-300"
          >−</button>
          <span>{{ item.qty }}</span>
          <button
            @click="increaseQty(item.id)"
            class="px-2 bg-gray-200 rounded hover:bg-gray-300"
          >+</button>
        </div>
        <div>Rp {{ formatPrice((item.price ?? 0) * (item.qty ?? 1)) }}</div>
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
        <div class="flex gap-4">
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
  </div>
</template>
