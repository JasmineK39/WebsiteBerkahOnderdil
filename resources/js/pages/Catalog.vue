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
              <input type="range" min="0" max="1500000" v-model="maxPrice" class="w-full" />
              <div class="flex justify-between text-sm text-gray-600 mt-2">
                <span>Rp {{ minPrice.toLocaleString() }}</span>
                <span>Rp {{ maxPrice.toLocaleString() }}</span>
              </div>

            </div>

            <!-- Grade Filter -->
            <div class="mb-6">
              <h4 class="font-semibold text-gray-900 mb-3">Grade</h4>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" value="A" v-model="selectedGrades" />
                  <span class="ml-2 text-gray-700">Grade A</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" value="B" v-model="selectedGrades" />
                  <span class="ml-2 text-gray-700">Grade B</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" value="C" v-model="selectedGrades" />
                  <span class="ml-2 text-gray-700">Grade C</span>
                </label>

              </div>
            </div>

            <!-- Category Filter -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Kategori</h4>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" value="Mesin" v-model="selectedCategories" />
                  <span class="ml-2 text-gray-700">Mesin</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" value="Transmisi" v-model="selectedCategories" />
                  <span class="ml-2 text-gray-700">Transmisi</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded" value="Suspensi" v-model="selectedCategories" />
                  <span class="ml-2 text-gray-700">Suspensi</span>
                </label>

              </div>
            </div>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <SparepartCard v-for="item in products" :key="item.id" :sparepart="item" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import SparepartCard from '../components/SparepartCard.vue'
import axios from 'axios'
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const products = ref([])
const loading = ref(false)
const error = ref(null)

// ✅ Tambahkan reaktif state untuk filter (biar bisa digunakan di template)
const minPrice = ref(0)
const maxPrice = ref(1500000)
const selectedGrades = ref([])
const selectedCategories = ref([])

const carId = ref(route.params.carId || null)

// 🔹 Fetch data dari backend
async function fetchProducts() {
  loading.value = true
  error.value = null

  try {
    const params = new URLSearchParams()

    if (route.query.search) params.append('search', route.query.search)
    if (carId.value) params.append('car_id', carId.value)
    if (selectedCategories.value.length > 0)
      params.append('category', selectedCategories.value.join(','))
    if (selectedGrades.value.length > 0)
      params.append('grade', selectedGrades.value.join(','))
    if (minPrice.value > 0) params.append('min_price', minPrice.value)
    if (maxPrice.value < 1500000) params.append('max_price', maxPrice.value)

    const url = `/api/spareparts?${params.toString()}`
    console.log('🔍 Fetching:', url)

    const res = await axios.get(url)
    console.log('✅ Response:', res.data)

<<<<<<< HEAD
    products.value = Array.isArray(res.data) ? res.data : res.data.data || []
=======
    products.value = Array.isArray(res.data) ? res.data : []
>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
  } catch (err) {
    console.error('❌ Error fetching spareparts:', err)
    error.value = 'Gagal memuat data sparepart.'
  } finally {
    loading.value = false
  }
}

// ✅ Lifecycle dan reactivity
onMounted(fetchProducts)
watch(() => route.query.search, fetchProducts)
watch([selectedGrades, selectedCategories, minPrice, maxPrice], fetchProducts)
watch(() => route.params.carId, (newId) => {
  carId.value = newId
  fetchProducts()
})

</script>


<style scoped></style>
