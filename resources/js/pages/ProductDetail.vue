<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <div class="mb-8 text-sm text-gray-600">
        <router-link to="/" class="text-blue-600 hover:underline">Home</router-link>
        <span class="mx-2">/</span>
        <router-link to="/catalog" class="text-blue-600 hover:underline">Katalog</router-link>
        <span class="mx-2">/</span>
        <span>{{ product.name }}</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-8 rounded-lg shadow-md">
        <!-- Image -->
        <div>
          <img
            :src="product.image"
            :alt="product.name"
            class="w-full rounded-lg"
          />
        </div>

        <!-- Details -->
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-4">
            {{ product.name }}
          </h1>

          <!-- Grade -->
          <div class="mb-4">
            <span class="inline-block bg-yellow-400 text-gray-900 px-4 py-2 rounded-full font-semibold">
              Grade: {{ product.grade }}
            </span>
          </div>

          <!-- Price -->
          <div class="mb-6">
            <p class="text-4xl font-bold text-blue-600">
              Rp {{ formatPrice(product.price) }}
            </p>
          </div>

          <!-- Description -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
            <p class="text-gray-600 leading-relaxed">
              {{ product.description }}
            </p>
          </div>

          <!-- Specifications -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Spesifikasi</h3>
            <ul class="space-y-2 text-gray-600">
              <li><strong>Tipe:</strong> {{ product.type }}</li>
              <li><strong>Kompatibilitas:</strong> {{ product.compatibility }}</li>
              <li><strong>Berat:</strong> {{ product.weight }}</li>
              <li><strong>Garansi:</strong> {{ product.warranty }}</li>
            </ul>
          </div>

          <!-- Stock -->
          <div class="mb-6">
            <p class="text-sm text-gray-600">
              <span class="font-semibold">Stok Tersedia:</span> {{ product.stock }} unit
            </p>
          </div>

          <!-- Actions -->
          <div class="space-y-3">
            <button class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-bold text-lg">
              Tambah ke Keranjang
            </button>
            <button class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition font-bold text-lg flex items-center justify-center gap-2">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c0-.297-.113-.585-.32-.799l-4.88-5.005c-.165-.17-.39-.267-.624-.267-.234 0-.459.097-.624.267l-4.88 5.005c-.207.214-.32.502-.32.799 0 .614.502 1.116 1.116 1.116h.528v4.771c0 .614.502 1.116 1.116 1.116h6.968c.614 0 1.116-.502 1.116-1.116v-4.771h.528c.614 0 1.116-.502 1.116-1.116z" />
              </svg>
              Chat via WhatsApp
            </button>
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <section class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <SparepartCard
            v-for="item in relatedProducts"
            :key="item.id"
            :sparepart="item"
          />
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import SparepartCard from '../components/SparepartCard.vue'

const product = ref({
  id: 1,
  name: 'Mesin Bensin 1.5L Toyota Avanza',
  grade: 'A',
  price: 8500000,
  image: '/placeholder.svg?height=400&width=500',
  description: 'Mesin bensin original Toyota Avanza 1.5L dengan kondisi prima. Telah melalui quality control ketat dan siap untuk digunakan. Kompatibel dengan berbagai tahun produksi Toyota Avanza.',
  type: 'Mesin Bensin',
  compatibility: 'Toyota Avanza 2007-2023',
  weight: '120 kg',
  warranty: '6 bulan',
  stock: 5
})

const relatedProducts = ref([
  {
    id: 2,
    name: 'Transmisi Otomatis Avanza',
    grade: 'A',
    price: 12000000,
    image: '/placeholder.svg?height=200&width=300',
    description: 'Transmisi otomatis original dengan garansi resmi'
  },
  {
    id: 3,
    name: 'Radiator Pendingin',
    grade: 'B',
    price: 1500000,
    image: '/placeholder.svg?height=200&width=300',
    description: 'Radiator pendingin berkualitas tinggi'
  },
  {
    id: 4,
    name: 'Alternator 12V',
    grade: 'A',
    price: 2000000,
    image: '/placeholder.svg?height=200&width=300',
    description: 'Alternator original dengan output stabil'
  },
  {
    id: 5,
    name: 'Baterai Mobil 60Ah',
    grade: 'A',
    price: 1200000,
    image: '/placeholder.svg?height=200&width=300',
    description: 'Baterai mobil kapasitas 60Ah tahan lama'
  }
])

const formatPrice = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}
</script>

<style scoped>
</style>
