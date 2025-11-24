<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="md:col-span-2">
          <!-- Shipping Address -->
          <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Alamat Pengiriman</h2>
            <div class="space-y-4">
              <input
                type="text"
                placeholder="Nama Lengkap"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <input
                type="email"
                placeholder="Email"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <input
                type="tel"
                placeholder="Nomor Telepon"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <textarea
                placeholder="Alamat Lengkap"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>
          </div>

          <!-- Shipping Method -->
          <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Metode Pengiriman</h2>
            <div class="space-y-3">
              <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="shipping" class="rounded" checked />
                <span class="ml-3">
                  <span class="font-semibold text-gray-900">JNE Regular</span>
                  <p class="text-sm text-gray-600">3-5 hari kerja - Rp 50.000</p>
                </span>
              </label>
              <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="shipping" class="rounded" />
                <span class="ml-3">
                  <span class="font-semibold text-gray-900">JNE Express</span>
                  <p class="text-sm text-gray-600">1-2 hari kerja - Rp 100.000</p>
                </span>
              </label>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Metode Pembayaran</h2>
            <div class="space-y-3">
              <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="payment" class="rounded" checked />
                <span class="ml-3 font-semibold text-gray-900">Transfer Bank</span>
              </label>
              <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="payment" class="rounded" />
                <span class="ml-3 font-semibold text-gray-900">E-Wallet</span>
              </label>
              <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="payment" class="rounded" />
                <span class="ml-3 font-semibold text-gray-900">Cicilan</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="md:col-span-1">
          <div class="bg-white p-6 rounded-lg shadow-md sticky top-20">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>

            <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
              <div v-for="item in cartItems" :key="item.id" class="flex justify-between text-sm">
                <span class="text-gray-600">{{ item.name }} x{{ item.qty }}</span>
                <span class="font-semibold text-gray-900">Rp {{ formatPrice(item.price * item.qty) }}</span>
              </div>
            </div>

            <div class="space-y-2 mb-4">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>Rp {{ formatPrice(subtotal) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Pengiriman</span>
                <span>Rp {{ formatPrice(shipping) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Pajak</span>
                <span>Rp {{ formatPrice(tax) }}</span>
              </div>
            </div>

            <div class="border-t border-gray-200 pt-4 mb-6">
              <div class="flex justify-between text-lg font-bold text-gray-900">
                <span>Total</span>
                <span class="text-blue-600">Rp {{ formatPrice(total) }}</span>
              </div>
            </div>

            <button class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-bold">
              Lanjutkan Pembayaran
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const cartItems = ref([
  { id: 1, name: 'Mesin Bensin 1.5L', price: 8500000, qty: 1 },
  { id: 2, name: 'Transmisi Otomatis', price: 12000000, qty: 1 }
])

const shipping = ref(50000)
const subtotal = computed(() => cartItems.value.reduce((sum, item) => sum + (item.price * item.qty), 0))
const tax = computed(() => Math.floor(subtotal.value * 0.1))
const total = computed(() => subtotal.value + shipping.value + tax.value)

const formatPrice = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}
</script>

<style scoped>
</style>
