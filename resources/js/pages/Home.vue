<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-linier-to-r from-blue-600 to-blue-800 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
          Berkah Onderdil
        </h1>
        <p class="text-xl text-blue-100 mb-8">
          Pusat penjualan sparepart mobil berkualitas dengan harga terjangkau
        </p>
        <button class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
          Jelajahi Katalog
        </button>
      </div>
    </section>

    <!-- Catalog Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <h2 class="text-3xl font-bold text-gray-900 mb-8">
        Katalog Mobil
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <CatalogCard
          v-for="car in cars"
          :key="car.id"
          :product="car"
        />
      </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Produk Berkualitas</h3>
            <p class="text-gray-600">Semua produk dijamin original dan berkualitas tinggi</p>
          </div>

          <div class="text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Pengiriman Cepat</h3>
            <p class="text-gray-600">Pengiriman ke seluruh Indonesia dalam 1-3 hari kerja</p>
          </div>

          <div class="text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Harga Kompetitif</h3>
            <p class="text-gray-600">Harga terbaik dengan kualitas terjamin</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import CatalogCard from '../components/CatalogCard.vue'

const cars = ref([
  {
    id: 1,
    name: 'Toyota Avanza',
    year: 2020,
    price: 150000000,
    image: '/placeholder.svg?height=200&width=300',
    itemCount: 45
  },
  {
    id: 2,
    name: 'Honda Civic',
    year: 2021,
    price: 250000000,
    image: '/placeholder.svg?height=200&width=300',
    itemCount: 38
  },
  {
    id: 3,
    name: 'Mitsubishi Xpander',
    year: 2022,
    price: 200000000,
    image: '/placeholder.svg?height=200&width=300',
    itemCount: 52
  },
  {
    id: 4,
    name: 'Daihatsu Terios',
    year: 2021,
    price: 180000000,
    image: '/placeholder.svg?height=200&width=300',
    itemCount: 41
  }
])
</script>

<style scoped>
</style>
