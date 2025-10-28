<template>
  <router-link :to="`/product/${product.id}`" class="group block">
    <div class="bg-card rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-700">
      <!-- Image Container -->
      <div class="relative overflow-hidden bg-dark-secondary h-52">
        <img
          :src="product.image"
          :alt="product.name"
          class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
        />
        <!-- Year Badge -->
        <div class="absolute top-3 right-3 bg-primary text-light px-3 py-1 rounded-lg font-bold text-sm shadow-lg">
          {{ product.year }}
        </div>
        <!-- Overlay on Hover -->
        <div class="absolute inset-0 bg-gradient-to-t from-dark via-dark/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
          <span class="text-light font-semibold text-sm bg-primary px-4 py-2 rounded-full">
            Lihat Detail →
          </span>
        </div>
      </div>

      <!-- Content -->
      <div class="p-5 bg-dark-secondary">
        <h3 class="text-lg font-bold text-light mb-3 group-hover:text-primary transition-colors line-clamp-2">
          {{ product.name }}
        </h3>
        
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-700">
          <div>
            <p class="text-xs text-gray uppercase tracking-wide mb-1">Harga</p>
            <span class="text-primary-light font-bold text-xl">
              Rp {{ formatPrice(product.price) }}
            </span>
          </div>
          <div class="text-right">
            <p class="text-xs text-gray uppercase tracking-wide mb-1">Stok</p>
            <span class="text-light-secondary bg-dark px-3 py-1 rounded-full text-sm font-semibold">
              {{ product.itemCount }} item
            </span>
          </div>
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
.bg-dark {
  background-color: #0B090A;
}

.bg-dark-secondary {
  background-color: #161A1D;
}

.bg-card {
  background-color: #161A1D;
}

.bg-primary {
  background-color: #BA181B;
}

.text-primary {
  color: #E5383B;
}

.text-primary-light {
  color: #E5383B;
}

.text-light {
  color: #F5F3F4;
}

.text-light-secondary {
  color: #D3D3D3;
}

.text-gray {
  color: #B1A7A6;
}

.border-gray-700 {
  border-color: #2a2d31;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>