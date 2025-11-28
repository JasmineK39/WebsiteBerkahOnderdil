<template>
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Tambah Model Mobil</h2>
    
    <div v-if="errors" class="mb-4 p-3 bg-red-100 text-red-700 border border-red-200 rounded">
      <ul class="list-disc pl-5">
        <li v-for="(messages, field) in errors" :key="field">
          {{ messages.join(', ') }}
        </li>
      </ul>
    </div>

    <form @submit.prevent="simpan">
      <div class="mb-4">
        <label class="block mb-1 font-medium">Brand</label>
        <input v-model="form.brand" class="w-full border rounded p-2" required />
      </div>
      
      <div class="mb-4">
        <label class="block mb-1 font-medium">Model</label>
        <input v-model="form.model" class="w-full border rounded p-2" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">URL Gambar</label>
        <input v-model="form.image" type="url" class="w-full border rounded p-2" placeholder="https://..." />
      </div>

      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Simpan
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue' // Tambahkan 'ref'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
// 3. State 'form' disesuaikan dengan field controller
const form = reactive({
  brand: '',
  model: '',
  year: '',
  description: '',
  image: ''
})

// State untuk menyimpan error validasi
const errors = ref(null)

const simpan = async () => {
  errors.value = null // Reset error setiap kali submit
  try {
    // 4. Endpoint API diubah
    await axios.post('/api/admin/models', form)
    // 5. Redirect diubah
    router.push('/admin/models')
  } catch (error) {
    // Tangani error validasi (422) dari controller
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Gagal menyimpan data:', error)
      alert('Terjadi kesalahan saat menyimpan data.')
    }
  }
}
</script>