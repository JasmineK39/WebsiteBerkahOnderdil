<template>
  <div class="min-h-screen bg-dark-secondary text-light py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-4xl font-black mb-6">
        Sparepart untuk <span class="text-primary-light">{{ carName }}</span>
      </h2>

      <div v-if="loading" class="text-center text-gray">Memuat data...</div>
      <div v-else-if="spareparts.length === 0" class="text-center text-gray">
        Belum ada sparepart untuk mobil ini.
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="part in spareparts"
          :key="part.id"
          class="bg-dark border border-gray-700 rounded-xl p-4 hover:border-primary transition-all"
        >

        <!-- GAMBAR SPAREPART -->
  <img
    :src="part.image"
    :alt="part.name"
    class="w-full h-40 object-cover rounded-lg mb-3"
  />
        
          <h3 class="text-lg font-bold text-primary mb-2">{{ part.name }}</h3>
          <p class="text-white/70 text-sm mb-1">{{ part.brand }}</p>
          <p class="text-white/70 text-sm mb-1">Grade: {{ part.grade }}</p>
          <p class="text-white/80 text-sm">Rp {{ Number(part.price).toLocaleString('id-ID') }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const carId = route.params.carId
const carName = ref('')
const spareparts = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const resCar = await axios.get(`/api/cars`)
    const car = resCar.data.find(c => c.id == carId)
    carName.value = car ? `${car.brand} ${car.model}` : 'Mobil Tidak Dikenal'

    const resParts = await axios.get(`/api/spareparts?car_id=${carId}`)
    spareparts.value = resParts.data.data ?? []
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
})
</script>
