<template>
  <div class="max-w-xl mx-auto bg-white p-8 mt-10 rounded-xl shadow-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Tambah Model Mobil</h2>
    
    <!-- Area Notifikasi Error -->
    <div v-if="errors" class="mb-6 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
      <p class="font-semibold mb-2">Terjadi Kesalahan Validasi:</p>
      <ul class="list-disc pl-5 space-y-1">
        <li v-for="(messages, field) in errors" :key="field">
          <strong>{{ field }}:</strong> {{ messages.join(', ') }}
        </li>
      </ul>
    </div>
    <!-- /Area Notifikasi Error -->

    <form @submit.prevent="simpan" class="space-y-6">
      <!-- Field Brand -->
      <div>
        <label for="brand" class="block mb-2 font-medium text-gray-700">Brand <span class="text-red-500">*</span></label>
        <input 
          id="brand"
          v-model="form.brand" 
          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" 
          required 
          placeholder="Contoh: Toyota"
        />
      </div>
      
      <!-- Field Model -->
      <div>
        <label for="model" class="block mb-2 font-medium text-gray-700">Model <span class="text-red-500">*</span></label>
        <input 
          id="model"
          v-model="form.model" 
          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" 
          required 
          placeholder="Contoh: Avanza"
        />
      </div>

      <!-- Field Gambar -->
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
                                                                                  
      <button type="submit" :disabled="isSubmitting" class="w-full bg-blue-600 text-white font-semibold px-4 py-3 rounded-lg hover:bg-blue-700 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
        {{ isSubmitting ? 'Menyimpan...' : 'Simpan Model Mobil' }}
      </button>
    </form>
    
    <!-- Modal Pesan Kesalahan Non-Validasi -->
    <div v-if="generalError" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm">
            <h3 class="text-xl font-bold text-red-600 mb-4">Kesalahan Server</h3>
            <p class="text-gray-700">{{ generalError }}</p>
            <button @click="generalError = null" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Tutup</button>
        </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const isSubmitting = ref(false)
const generalError = ref(null) // State untuk error non-validasi (misal: 401, 500)

const form = reactive({
  brand: '',
  model: '',
  image: null // Mengubah ke null
})

const handleImage = (e) => {
  form.image = e.target.files[0]
}

const errors = ref(null)

const simpan = async () => {
  errors.value = null
  generalError.value = null
  isSubmitting.value = true

  try {
    const formData = new FormData()
    formData.append('brand', form.brand)
    formData.append('model', form.model)
    // Append field opsional (year, description) hanya jika ada isinya
    if (form.year) formData.append('year', form.year)
    if (form.description) formData.append('description', form.description)

    // Append gambar
    if (form.image) {
      formData.append('image', form.image)
    }

    await axios.post('/api/admin/models', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    router.push('/admin/models')
  } catch (error) {
    if (error.response) {
      if (error.response.status === 422) {
        // Error Validasi
        errors.value = error.response.data.errors
      } else if (error.response.status === 401) {
        // Error Unauthorized (Biasanya karena token hilang)
        generalError.value = 'Akses ditolak. Anda tidak terotentikasi atau tidak memiliki izin Admin. Silakan login ulang.'
      } else {
        // Error Server Lain (500)
        generalError.value = `Gagal menyimpan data. Status: ${error.response.status}. Detail: ${error.response.data.message || 'Terjadi kesalahan server.'}`
      }
    } else {
        // Error jaringan atau koneksi
        generalError.value = 'Gagal terhubung ke server. Periksa koneksi internet Anda.'
    }
  } finally {
    isSubmitting.value = false
  }
}

</script>