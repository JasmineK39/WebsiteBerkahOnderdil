<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Image -->
    <div class="relative overflow-hidden bg-gray-100 h-48">
      <img
        :src="sparepart.image"
        :alt="sparepart.name"
        class="w-full h-full object-cover"
      />
      <span v-if="sparepart.grade" class="absolute top-3 right-3 bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">
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

      <!-- Actions -->
      <div class="space-y-2">
        <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium">
          Tambah ke Keranjang
        </button>
        <button class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition font-medium flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c0-.297-.113-.585-.32-.799l-4.88-5.005c-.165-.17-.39-.267-.624-.267-.234 0-.459.097-.624.267l-4.88 5.005c-.207.214-.32.502-.32.799 0 .614.502 1.116 1.116 1.116h.528v4.771c0 .614.502 1.116 1.116 1.116h6.968c.614 0 1.116-.502 1.116-1.116v-4.771h.528c.614 0 1.116-.502 1.116-1.116z" />
          </svg>
          Chat via WA
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  sparepart: {
    type: Object,
    required: true
  }
})

const formatPrice = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}
</script>

<style scoped>
</style>
