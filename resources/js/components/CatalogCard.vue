<template>
  <router-link
    :to="`/catalog/${car.id}`"
    class="block bg-dark-secondary rounded-xl overflow-hidden shadow-lg hover:shadow-primary/40 hover:scale-[1.02] transition-all duration-300"
  >
    <img
      :src="car.image ? `/storage/${car.image}` : '/images/no-image.jpg'"
      alt="Gambar Mobil"
      class="w-full h-36 object-contain bg-white/10"
    />
    <div class="p-4">
      <h3 class="text-xl font-bold text-red-500 mb-1">
        {{ car.brand }} {{ car.model }}
      </h3>

      <p v-if="car.price" class="text-primary font-semibold text-lg mb-2">
        Rp {{ Number(car.price).toLocaleString('id-ID') }}
      </p>

      <p class="text-white/80 text-sm mb-1">Tahun: {{ car.year }}</p>
      <p class="text-white/70 text-sm leading-relaxed">
        {{ car.description }}
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
