<template>
  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Sparepart</h2>
    
    <div v-if="loading" class="text-center">Memuat data...</div>

    <form v-else @submit.prevent="update">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
        
        <div>
          <div class="mb-4">
            <label class="block mb-1 font-medium">Nama Sparepart</label>
            <input v-model="form.name" class="w-full border rounded p-2" required />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Model Mobil</label>
            <select v-model="form.model_mobil_id" class="w-full border rounded p-2" required>
              <option value="" disabled>Pilih Model Mobil</option>
              <option v-for="model in modelMobils" :key="model.id" :value="model.id">
                {{ model.model }} </option>
            </select>
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Merk Sparepart</label>
            <input v-model="form.brand" class="w-full border rounded p-2" required />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Type (Opsional)</label>
            <input v-model="form.type" class="w-full border rounded p-2" />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Image (Opsional)</label>
            <input v-model="form.image" class="w-full border rounded p-2" placeholder="Contoh: /path/to/image.jpg" />
          </div>
        </div>

        <div>
          <div class="mb-4">
            <label class="block mb-1 font-medium">Harga</label>
            <input v-model="form.price" type="number" class="w-full border rounded p-2" required />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Stok</label>
            <input v-model="form.stock" type="number" class="w-full border rounded p-2" required />
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Grade</label>
            <select v-model="form.grade" class="w-full border rounded p-2" required>
              <option value="" disabled>Pilih Grade</option>
              <option v-for="grade in gradeOptions" :key="grade" :value="grade">
                {{ grade }}
              </option>
            </select>
          </div>

          <div class="mb-4">
            <label class="block mb-1 font-medium">Status</label>
            <select v-model="form.status" class="w-full border rounded p-2" required>
              <option value="" disabled>Pilih Status</option>
              <option v-for="status in statusOptions" :key="status" :value="status">
                {{ status }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Deskripsi (Opsional)</label>
        <textarea v-model="form.description" class="w-full border rounded p-2" rows="4"></textarea>
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
  image: '',
  status: ''
})

// State untuk UI
const loading = ref(true)
const saving = ref(false)
const error = ref(null)

const gradeOptions = ref(['A', 'B', 'C']) 
const statusOptions = ref(['Available', 'Sold Out'])


// State untuk data relasi
const modelMobils = ref([])

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

// Fungsi untuk mengirim data update
const update = async () => {
  if (saving.value) return
  saving.value = true
  error.value = null

  try {
    await axios.put(`/api/admin/spareparts/${sparepartId}`, form)
    router.push('/admin/spareparts')
  } catch (err) {
    console.error('Gagal mengupdate sparepart:', err)
    if (err.response && err.response.status === 422) {
      // Menampilkan error validasi (jika ada)
      error.value = 'Data yang Anda masukkan tidak valid. Periksa kembali semua field.'
      // Di sini Anda bisa mem-parsing err.response.data.errors untuk
      // menampilkan pesan error yang lebih spesifik per field.
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