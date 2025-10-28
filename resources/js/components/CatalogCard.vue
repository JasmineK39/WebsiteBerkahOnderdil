<template>
  <router-link :to="`/product/${product.id}`" class="group">
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
      <!-- Image -->
      <div class="relative overflow-hidden bg-gray-100 h-48">
        <img
          :src="product.image"
          :alt="product.name"
          class="w-full h-full object-cover group-hover:scale-105 transition"
        />
      </div>

      <!-- Content -->
      <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
          {{ product.name }}
        </h3>
        <p class="text-sm text-gray-600 mb-3">
          Tahun: {{ product.year }}
        </p>
        <div class="flex items-center justify-between">
          <span class="text-blue-600 font-bold text-lg">
            Rp {{ formatPrice(product.price) }}
          </span>
          <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">
            {{ product.itemCount }} item
          </span>
        </div>
      </div>
    </div>
  </router-link>
</template>

<script setup>
defineProps({
  product: {
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
