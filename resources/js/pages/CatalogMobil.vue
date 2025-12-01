<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <CatalogCard
      v-for="car in cars"
      :key="car.id"
      :car="car"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import CatalogCard from '../components/CatalogCard.vue'

const cars = ref([])

onMounted(async () => {
  const res = await axios.get('/api/cars')
  cars.value = res.data.data
})
</script>
