<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Image -->
    <div class="relative overflow-hidden bg-gray-100 h-48">
      <img
        :src="sparepart.image"
        :alt="sparepart.name"
        class="w-full h-full object-cover"
      />
      <span
        v-if="sparepart.grade"
        class="absolute top-3 right-3 bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-sm font-semibold"
      >
        {{ sparepart.grade }}
      </span>
    </div>

    <!-- Content -->
    <div class="p-4">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">
        {{ sparepart.name }}
      </h3>
      <p class="text-sm text-gray-600 mb-3 line-clamp-2">
        {{ sparepart.description }}
      </p>

      <!-- Price -->
      <div class="mb-4">
        <p class="text-2xl font-bold text-blue-600">
          Rp {{ formatPrice(sparepart.price) }}
        </p>
      </div>

      <!-- Single Action Button -->
      <button
        @click="lihatDetail(sparepart.id)"
        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium"
      >
        Lihat Detail
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const props = defineProps({
  sparepart: {
    type: Object,
    required: true
  }
})

const router = useRouter()

const formatPrice = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}

// Fungsi untuk navigasi ke halaman detail
function lihatDetail(id) {
  router.push(`/product/${id}`)
}

</script>

<style scoped>
</style>
