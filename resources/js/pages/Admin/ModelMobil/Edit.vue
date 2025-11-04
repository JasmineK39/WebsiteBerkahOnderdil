<template>
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Model Mobil</h2>

    <div v-if="errors" class="mb-4 p-3 bg-red-100 text-red-700 border border-red-200 rounded">
      <ul class="list-disc pl-5">
        <li v-for="(messages, field) in errors" :key="field">
          {{ messages.join(', ') }}
        </li>
      </ul>
    </div>

    <form @submit.prevent="update">
      <div class="mb-4">
        <label class="block mb-1 font-medium">Brand</label>
        <input v-model="form.brand" class="w-full border rounded p-2" required />
      </div>
      
      <div class="mb-4">
        <label class="block mb-1 font-medium">Model</label>
        <input v-model="form.model" class="w-full border rounded p-2" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Tahun</label>
        <input v-model="form.year" type="number" min="1886" :max="new Date().getFullYear()" class="w-full border rounded p-2" placeholder="Contoh: 2023" />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Deskripsi</label>
        <textarea v-model="form.description" class="w-full border rounded p-2" rows="3"></textarea>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">URL Gambar</label>
        <input v-model="form.image" type="url" class="w-full border rounded p-2" placeholder="https://..." />
      </div>

      <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
        Update
      </button>
    </form>
  </div>
</template>

<script setup>
// 3. Import 'ref' ditambahkan
import { reactive, onMounted, ref } from 'vue'
import axios from 'axios'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

// 4. State 'form' disesuaikan
const form = reactive({
  brand: '',
  model: '',
  year: '',
  description: '',
  image: ''
})

// 5. State untuk error validasi
const errors = ref(null)

const loadData = async () => {
  try {
    // 6. Endpoint GET diubah
    const res = await axios.get(`/api/admin/models/${route.params.id}`)
    // Ini akan mengisi 'form' dengan data yang dimuat (brand, model, dll.)
    Object.assign(form, res.data)
  } catch (error) {
    console.error('Gagal memuat data:', error)
    alert('Gagal memuat data model mobil.')
  }
}

const update = async () => {
  errors.value = null // Reset error
  try {
    // 7. Endpoint PUT diubah
    await axios.put(`/api/admin/models/${route.params.id}`, form)
    // 8. Redirect diubah
    router.push('/admin/models')
  } catch (error) {
    // 9. Penanganan error validasi
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Gagal mengupdate data:', error)
      alert('Terjadi kesalahan saat mengupdate data.')
    }
  }
}

onMounted(loadData)
</script>