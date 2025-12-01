<template>
  <div>
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Data Model Mobil</h2>
      <RouterLink
        to="/admin/models/create"
        class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600"
      >
        + Tambah Model Mobil
      </RouterLink>
    </div>

    <table class="w-full bg-white rounded shadow">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 text-left">#</th>
          <th class="p-2 text-left">Gambar</th>
          <th class="p-2 text-left">Brand</th>
          <th class="p-2 text-left">Model</th>
          <th class="p-2 text-left">Jml. Sparepart</th>
          <th class="p-2 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, i) in modelMobils" :key="item.id" class="border-b">
          <td class="p-2">{{ i + 1 }}</td>

          <!-- Tampilkan gambar -->
          <td class="p-2">
            <img 
              v-if="item.image"
              :src="getImageUrl(item.image)"
              class="w-16 h-12 object-contain rounded border bg-white"
            />
            <span v-else class="text-gray-400 text-xs italic">No Image</span>
          </td>

          <td class="p-2">{{ item.brand }}</td>
          <td class="p-2">{{ item.model }}</td>
          <td class="p-2">{{ item.spareparts ? item.spareparts.length : 0 }}</td>
          <td class="p-2 space-x-2">
            <RouterLink
              :to="`/admin/models/${item.id}/edit`"
              class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500"
            >Edit</RouterLink>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const modelMobils = ref([])

const getImageUrl = (path) => {
  return path ? `/storage/${path}` : ''
}

const loadData = async () => {
  try {
    const res = await axios.get('/api/admin/models')
    const sortedData = res.data.sort((a, b) => b.id - a.id)
    modelMobils.value = sortedData
  } catch (error) {
    console.error('Gagal memuat data model mobil:', error)
  }
}

onMounted(loadData)
</script>
