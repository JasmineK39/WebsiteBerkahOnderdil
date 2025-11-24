<template>
  <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-2xl border border-gray-100">
    <h2 class="text-3xl font-extrabold mb-6 text-gray-800 border-b pb-2">
      Edit Sparepart
    </h2>

    <!-- Tampilan Error Umum -->
    <div v-if="error" class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded">
      {{ error }}
    </div>
    
    <form @submit.prevent="update" class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- KOLOM KIRI -->
        <div class="space-y-4">
          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Nama Sparepart</label>
            <input v-model="form.name" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" required />
          </div>

          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Model Mobil</label>
            <select v-model="form.model_mobil_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none transition duration-150" required>
              <option value="" disabled>Pilih Model Mobil</option>
              <option v-for="model in modelMobils" :key="model.id" :value="model.id">
                {{ model.model }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Merk Sparepart</label>
            <input v-model="form.brand" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" required />
          </div>

          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Type (Opsional)</label>
            <input v-model="form.type" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" />
          </div>

          <!-- INPUT FILE BARU -->
          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Gambar Sparepart (Ganti File)</label>
            <input 
              type="file" 
              @change="handleFileUpload" 
              class="w-full border border-gray-300 rounded-lg p-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150"
              accept="image/*"
            />
            <p v-if="newImageFile" class="mt-1 text-sm text-green-600">File baru terpilih: {{ newImageFile.name }}</p>
          </div>
          
        </div>

        <!-- KOLOM KANAN -->
        <div class="space-y-4">
          
          <!-- PREVIEW GAMBAR LAMA -->
          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Gambar Saat Ini</label>
            <div v-if="form.image && !newImageFile" class="border border-gray-300 rounded-lg p-2 flex justify-center">
              <!-- Memastikan URL gambar benar -->
              <img :src="`http://localhost:8000/storage/${form.image}`" 
                   alt="Gambar Sparepart Lama" 
                   class="max-h-40 object-contain rounded-md"
              />
            </div>
            <div v-else-if="newImageFile" class="p-3 text-sm text-blue-700 bg-blue-100 rounded">
                File baru akan menggantikan gambar lama.
            </div>
            <div v-else class="p-3 text-sm text-gray-500 bg-gray-100 rounded">
                Tidak ada gambar saat ini.
            </div>
          </div>
          
          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Harga</label>
            <input v-model="form.price" type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" required />
          </div>

          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Stok</label>
            <input v-model="form.stock" type="number" min="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" required />
          </div>

          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Grade</label>
            <select v-model="form.grade" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none transition duration-150" required>
              <option value="" disabled>Pilih Grade</option>
              <option v-for="grade in gradeOptions" :key="grade" :value="grade">
                {{ grade }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Status</label>
            <select v-model="form.status" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none transition duration-150" required>
              <option value="" disabled>Pilih Status</option>
              <option v-for="status in statusOptions" :key="status" :value="status">
                {{ status }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="block mb-1 text-sm font-semibold text-gray-700">Deskripsi (Opsional)</label>
        <textarea v-model="form.description" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" rows="4"></textarea>
      </div>

      <div class="mt-4">
        <button type="submit" :disabled="saving" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 disabled:bg-gray-400">
          {{ saving ? 'Menyimpan...' : 'Update' }}
        </button>
        <RouterLink 
          to="/admin/spareparts" 
          class="ml-2 px-4 py-2 rounded text-gray-700 hover:bg-gray-200"
        >
          Batal
        </RouterLink>
      </div>
      
      <div v-if="error" class="mt-4 text-red-600">
        {{ error }}
      </div>

    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()
const sparepartId = route.params.id

// State untuk form
const form = reactive({
  name: '',
  model_mobil_id: '',
  brand: '',
  type: '',
  grade: '',
  stock: 0,
  price: 0,
  description: '',
  // Tidak perlu 'image' di sini karena data gambar lama sudah di loadData
  status: ''
})

// State untuk file gambar BARU yang dipilih
const newImageFile = ref(null) 

// State untuk UI
const loading = ref(true)
const saving = ref(false)
const error = ref(null)

const gradeOptions = ref(['A', 'B', 'C']) 
const statusOptions = ref(['Available', 'Sold Out'])

// State untuk data relasi
const modelMobils = ref([])

// FUNGSI BARU: Menangani pemilihan file
const handleFileUpload = (event) => {
  newImageFile.value = event.target.files ? event.target.files[0] : null
}


// Fungsi untuk mengambil data sparepart yang akan diedit
const loadData = async () => {
  try {
    const res = await axios.get(`/api/admin/spareparts/${sparepartId}`)
    // Object.assign akan mengisi 'form' dengan data dari API
    Object.assign(form, res.data)
  } catch (err) {
    console.error('Gagal memuat data sparepart:', err)
    error.value = 'Gagal memuat data. Coba lagi nanti.'
  }
}

// Fungsi untuk mengambil data model mobil
const loadModelMobils = async () => {
  try {
    const res = await axios.get('/api/admin/models') 
    modelMobils.value = res.data
  } catch (err) {
    console.error('Gagal memuat model mobil:', err)
    error.value = 'Gagal memuat daftar model mobil.'
  }
}

// FUNGSI UPDATE YANG DIREVISI TOTAL (Menggunakan FormData)
const update = async () => {
  if (saving.value) return
  saving.value = true
  error.value = null

  // 1. Buat FormData
  const formData = new FormData()

  // 2. Tambahkan field form non-file ke FormData
  for (const key in form) {
    // Pastikan nilai tidak null atau undefined, kecuali description/type
    if (form[key] !== null && form[key] !== undefined) {
      formData.append(key, form[key])
    }
  }

  // 3. Tambahkan file gambar BARU (jika ada)
  if (newImageFile.value) {
    formData.append('image', newImageFile.value)
  }
  
  // 4. Tambahkan metode PUT spoofing untuk Laravel
  formData.append('_method', 'PUT') 

  try {
    // Kirim request menggunakan axios.post (karena ada file upload)
    await axios.post(`/api/admin/spareparts/${sparepartId}`, formData, {
        // Header ini penting, tetapi Axios biasanya menanganinya secara otomatis
        // saat menggunakan FormData, jadi ini opsional:
        // headers: { 'Content-Type': 'multipart/form-data' } 
    })
    router.push('/admin/spareparts')
  } catch (err) {
    console.error('Gagal mengupdate sparepart:', err)
    if (err.response && err.response.status === 422) {
      error.value = 'Data yang Anda masukkan tidak valid. Periksa kembali semua field.'
    } else {
      error.value = 'Terjadi kesalahan saat menyimpan data.'
    }
  } finally {
    saving.value = false
  }
}

// onMounted: panggil semua fungsi load data saat komponen dimuat
onMounted(async () => {
  loading.value = true
  // Jalankan pengambilan data secara paralel
  await Promise.all([
    loadData(),
    loadModelMobils()
  ])
  loading.value = false
})
</script>