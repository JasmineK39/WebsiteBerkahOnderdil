<template>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-2xl border border-gray-100">
    <h2 class="text-3xl font-extrabold mb-6 text-gray-800 border-b pb-2">
      Tambah Sparepart Baru
    </h2>
    
    <!-- Tampilan Error Umum -->
    <div v-if="error" class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded">
      {{ error }}
    </div>

    <form @submit.prevent="simpan" class="space-y-6">
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

          <!-- PERUBAHAN KRUSIAL DI SINI (1/2) -->
          <div class="form-group">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Gambar Sparepart (File)</label>
            <!-- Menggunakan @change untuk memanggil fungsi penanganan file -->
            <input 
              type="file" 
              @change="handleFileUpload" 
              class="w-full border border-gray-300 rounded-lg p-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150"
              accept="image/*"
            />
            <p v-if="imageFile" class="mt-1 text-sm text-green-600">File terpilih: {{ imageFile.name }}</p>
          </div>
        </div>

        <!-- KOLOM KANAN -->
        <div class="space-y-4">
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

      <!-- Footer Buttons -->
      <div class="flex justify-end pt-4 border-t mt-6">
        <button 
          type="submit" 
          :disabled="saving" 
          class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 disabled:bg-gray-400 transition duration-150 transform hover:scale-[1.02]"
        >
          {{ saving ? 'Menyimpan...' : 'Simpan Sparepart' }}
        </button>
        <RouterLink 
          to="/admin/spareparts" 
          class="ml-3 px-6 py-3 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold transition duration-150"
        >
          Batal
        </RouterLink>
      </div>
      
      <div v-if="error" class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <p class="font-medium">Error:</p>
        <p>{{ error }}</p>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

// State untuk form (dimulai dengan nilai default)
const form = ref({
  model_mobil_id: '',
  name: '',
  brand: '',
  type: null,
  grade: '',
  stock: 0,
  price: 0,
  description: null,
  status: '',
})

// State File Upload
const imageFile = ref(null)

// Helper untuk menangani perubahan input file
const handleFileUpload = (event) => {
  // Hanya ambil file pertama
  imageFile.value = event.target.files ? event.target.files[0] : null
}

// State untuk UI
const saving = ref(false)
const error = ref(null)

const gradeOptions = ref(['A', 'B', 'C']) 
const statusOptions = ref(['available', 'sold_out'])

// State untuk data relasi
const modelMobils = ref([])

// Fungsi untuk mengambil data model mobil
const loadModelMobils = async () => {
  try {
    // Asumsi endpoint ini ada, ganti jika perlu
    const res = await axios.get('/api/admin/models') 
    modelMobils.value = res.data
  } catch (err) {
    console.error('Gagal memuat model mobil:', err)
    error.value = 'Gagal memuat daftar model mobil.'
  }
}

// Fungsi untuk mengirim data baru
const simpan = async () => {
  if (saving.value) return
  saving.value = true
  error.value = null

  const formData = new FormData()

    // Tambahkan semua field form ke FormData
  for (const key in form.value) {
    formData.append(key, form.value[key] === null ? '' : form.value[key])
  }

  // Tambahkan file gambar ke FormData
  if (imageFile.value) {
    formData.append('image', imageFile.value) 
  }

  try {
    await axios.post('/api/admin/spareparts', formData)
    router.push('/admin/spareparts')
  } catch (err) {
    console.error('Gagal menyimpan sparepart:', err)
    if (err.response && err.response.status === 422) {
      error.value = 'Data yang Anda masukkan tidak valid. Periksa kembali semua field.'
    } else {
      error.value = 'Terjadi kesalahan saat menyimpan data.'
    }
  } finally {
    saving.value = false
  }
}

// onMounted: panggil data yang diperlukan untuk form (misal: dropdown)
onMounted(() => {
  loadModelMobils()
})
</script>