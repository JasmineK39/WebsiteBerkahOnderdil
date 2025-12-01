<template>
  <router-link
    :to="`/catalog/${car.id}`"
    class="block bg-dark-secondary rounded-xl overflow-hidden shadow-lg hover:shadow-primary/40 hover:scale-[1.02] transition-all duration-300"
  >
    <img
      :src="car.image ? `/storage/${car.image}` : '/images/no-image.jpg'"
      alt="Gambar Mobil"
      class="w-full h-45 object-contain bg-white/10"
    />
    <div class="p-4">
      <h3 class="text-xl font-bold text-red-800 mb-1">
        {{ car.brand }} {{ car.model }}
      </h3>

      <p class="text-yellow-600 font-semibold text-sm mb-2">
        Cek Sparepart Sekarang →
      </p>
    </div>
  </router-link>
</template>

<script setup>
defineProps({
  car: {
    type: Object,
    required: true
  }
})
</script>
