<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Katalog Lengkap</h1>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1">
          <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Filter</h3>

            <!-- Price Range -->
            <div class="mb-6">
              <h4 class="font-semibold text-gray-900 mb-3">Harga</h4>
              <input type="range" min="0" max="20000000" class="w-full" />
              <div class="flex justify-between text-sm text-gray-600 mt-2">
                <span>Rp 0</span>
                <span>Rp 20M</span>
              </div>
            </div>

            <!-- Grade Filter -->
            <div class="mb-6">
              <h4 class="font-semibold text-gray-900 mb-3">Grade</h4>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" />
                  <span class="ml-2 text-gray-700">Grade A</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" />
                  <span class="ml-2 text-gray-700">Grade B</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" />
                  <span class="ml-2 text-gray-700">Grade C</span>
                </label>
              </div>
            </div>

            <!-- Category Filter -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Kategori</h4>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" />
                  <span class="ml-2 text-gray-700">Mesin</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" />
                  <span class="ml-2 text-gray-700">Transmisi</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" />
                  <span class="ml-2 text-gray-700">Suspensi</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <SparepartCard
              v-for="item in products"
              :key="item.id"
              :sparepart="item"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import SparepartCard from '../components/SparepartCard.vue'

const products = ref([
  {
    id: 1,
    name: 'Mesin Bensin 1.5L',
    grade: 'A',
    price: 8500000,
    image: '/placeholder.svg?height=200&width=300',
    description: 'Mesin bensin original dengan kondisi prima'
  },
  {
    id: 2,
    name: 'Transmisi Otomatis',
    grade: 'A',
    price: 12000000,
    image: '/placeholder.svg?height=200&width=300',
    description: 'Transmisi otomatis original dengan garansi'
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
  },
  {
    id: 6,
    name: 'Kampas Rem Depan',
    grade: 'B',
    price: 450000,
    image: '/placeholder.svg?height=200&width=300',
    description: 'Kampas rem depan berkualitas OEM'
  }
])
</script>

<style scoped>
</style>
