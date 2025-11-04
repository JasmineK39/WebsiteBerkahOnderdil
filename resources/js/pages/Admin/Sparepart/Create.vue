<template>
  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Tambah Sparepart Baru</h2>
    
    <form @submit.prevent="simpan">
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
        <button type="submit" :disabled="saving" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:bg-gray-400">
          {{ saving ? 'Menyimpan...' : 'Simpan' }}
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
import { useRouter } from 'vue-router'

const router = useRouter()

// State untuk form (dimulai dengan nilai default)
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
  status: 'Availble'
})

// State untuk UI
const saving = ref(false)
const error = ref(null)

const gradeOptions = ref(['A', 'B', 'C']) 
const statusOptions = ref(['Available', 'Sold Out'])

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

  try {
    await axios.post('/api/admin/spareparts', form)
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