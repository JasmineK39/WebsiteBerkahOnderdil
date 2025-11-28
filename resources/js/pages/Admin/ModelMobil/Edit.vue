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

        <div>
        <label for="image" class="block mb-2 font-medium text-gray-700">Upload Gambar (Opsional)</label>
        <input 
          id="image"
          type="file" 
          @change="handleImage" 
          class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" 
          accept="image/*" 
        />
      </div>

      <div v-if="form.image && typeof form.image === 'string'" class="mb-3">
    <img :src="getImageUrl(form.image)" class="w-28 h-20 object-cover rounded border" />
      </div>

      <br></br>

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
  image: '',
  old_image: null 
})

// 5. State untuk error validasi
const errors = ref(null)

const getImageUrl = (path) => `/storage/${path}`

const handleImage = (e) => {
  form.image = e.target.files[0] // ini wajib agar File terkirim
}

const loadData = async () => {
  try {
    const res = await axios.get(`/api/admin/models/${route.params.id}`)
    Object.assign(form, res.data)
    form.old_image = res.data.image // simpan path lama
    form.image = null 
  } catch (error) {
    console.error('Gagal memuat data:', error)
    alert('Gagal memuat data model mobil.')
  }
}

const update = async () => {
  errors.value = null

  const formData = new FormData()
  formData.append('brand', form.brand)
  formData.append('model', form.model)
  formData.append('year', form.year)
  formData.append('description', form.description)


if (form.image) {
    formData.append('image', form.image)
  }

  try {
    await axios.post(`/api/admin/models/${route.params.id}?_method=PUT`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    router.push('/admin/models')
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error(error)
      alert('Gagal update data')
    }
  }
}

onMounted(loadData)
</script>