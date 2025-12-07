<template>
  <div class="bg-gradient-to-b from-white-900 via-white-800 to-white-900 min-h-screen flex flex-col text-black">

    <!-- MAIN CONTENT -->
    <main class="flex-grow py-10">
      <div class="max-w-6xl mx-auto px-4">

        <!-- Judul -->
        <h2 class="text-3xl font-bold text-center mb-8">Request Sparepart</h2>

        <!-- Notifikasi sukses -->
        <div v-if="successMessage" class="bg-green-600 p-4 rounded mb-6 text-center">
          {{ successMessage }}
        </div>

        <!-- Form Request -->
        <form @submit.prevent="submitForm" class="bg-red-800 text-white p-6 rounded-xl shadow-lg mb-10">
          <div class="grid md:grid-cols-2 gap-4">
            <!-- Input Merek -->
<div>
  <label class="block mb-2 font-medium">Merek Mobil</label>
  <input 
    v-model="form.brand_req" 
    type="text" 
    placeholder="Contoh: Toyota"
    class="w-full px-3 py-2 rounded border border-red-700 bg-white text-black 
           placeholder-gray-400 focus:ring-2 focus:ring-red-500 outline-none"
    required
  >
</div>

            <!-- Input Model -->
<div>
  <label class="block mb-2 font-medium">Model Mobil</label>
  <input 
    v-model="form.model_req" 
    type="text" 
    placeholder="Contoh: Avanza"
    class="w-full px-3 py-2 rounded border border-red-700 bg-white text-black 
           placeholder-gray-400 focus:ring-2 focus:ring-red-500 outline-none"
    required
  >
</div>

            <!-- Tahun -->
            <div>
              <label class="block mb-2 font-medium">Tahun</label>
              <input v-model="form.year_req" type="number" placeholder="Contoh: 2018"
                class="w-full px-3 py-2 rounded border border-red-700 bg-white text-black placeholder-gray-400 focus:ring-2 focus:ring-red-500 outline-none" required>
            </div>

            <!-- Nama Sparepart -->
            <div>
              <label class="block mb-2 font-medium">Nama Sparepart</label>
              <input v-model="form.sparepart_req" type="text" placeholder="Contoh: Kampas Rem"
                class="w-full px-3 py-2 rounded border border-red-700 bg-white text-black placeholder-gray-400 focus:ring-2 focus:ring-red-500 outline-none" required>
            </div>
          </div>

          <!-- Catatan -->
          <div class="mt-4">
            <label class="block mb-2 font-medium">Catatan (opsional)</label>
            <textarea v-model="form.note" rows="3" placeholder="Tuliskan detail tambahan..."
              class="w-full px-3 py-2 rounded border border-red-700 bg-white text-black focus:ring-2 focus:ring-red-500 outline-none"></textarea>
          </div>

          <!-- Submit -->
          <div class="text-right mt-6">
            <button type="submit"
              class="bg-red-600 hover:bg-red-400 text-white px-6 py-2 rounded-lg transition-all duration-200">
              Kirim Request
            </button>
          </div>
        </form>

        <!-- Riwayat -->
        <h3 class="text-2xl font-semibold mb-4">Riwayat Request Saya</h3>
        <div class="overflow-x-auto bg-red-950/50 rounded-xl shadow-lg">
          <table class="min-w-full text-left text-sm">
            <thead class="bg-red-800 text-white">
              <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Merek</th>
                <th class="px-4 py-3">Model</th>
                <th class="px-4 py-3">Tahun</th>
                <th class="px-4 py-3">Sparepart</th>
                <th class="px-4 py-3">Catatan</th>
                <th class="px-4 py-3">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(req, index) in requests" :key="index" 
                  class="border-t border-red-700 hover:bg-red-800/40">
                <td class="px-4 py-3">{{ index + 1 }}</td>
                <td class="px-4 py-3">{{ req.brand_req }}</td>
                <td class="px-4 py-3">{{ req.model_req }}</td>
                <td class="px-4 py-3">{{ req.year_req }}</td>
                <td class="px-4 py-3">{{ req.sparepart_req }}</td>
                <td class="px-4 py-3">{{ req.note || '-' }}</td>
                <td class="px-4 py-3">
                  <span v-if="req.status === 'pending'" class="bg-yellow-400 text-black px-2 py-1 rounded">
                    {{ req.status }}
                  </span>
                  <span v-else-if="req.status === 'fulfilled'" class="bg-green-600 px-2 py-1 rounded">
                    {{ req.status }}
                  </span>
                  <span v-else class="bg-gray-500 px-2 py-1 rounded">
                    {{ req.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </main>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Form input
const form = ref({
  brand_req: '',
  model_req: '',
  year_req: '',
  sparepart_req: '',
  note: ''
})



// Dropdown data dari API
const brands = ref([])
const models = ref([])

// Riwayat
const requests = ref([])

// Notifikasi sukses
const successMessage = ref('')

/* ---------- LOAD DATA BRAND ---------- */
const getBrands = async () => {
  const res = await axios.get('/api/brands')
  brands.value = res.data
}

/* ---------- LOAD MODEL BERDASARKAN BRAND ---------- */
const loadModels = async () => {
  if (!form.value.brand_req) return
  const res = await axios.get(`/api/models/${form.value.brand_req}`)
  models.value = res.data
}

/* ---------- SUBMIT FORM ---------- */
const submitForm = async () => {
  try {
    const res = await axios.post('/api/request-sparepart', form.value)

    const newRequest = {
      ...form.value,
      status: res.data.status || 'pending'
    }

    requests.value.push(newRequest)

    form.value = { brand_req: '', model_req: '', year_req: '', sparepart_req: '', note: '' }
    models.value = []

    successMessage.value = 'Request berhasil dikirim!'
    setTimeout(() => (successMessage.value = ''), 3000)

  } catch (error) {
    console.error('Gagal mengirim request:', error)
  }
}

/* ---------- LOAD RIWAYAT REQUEST ---------- */
const getRequests = async () => {
  try {
    const res = await axios.get('/api/request-sparepart')   // pastikan endpoint benar
    requests.value = res.data
  } catch (error) {
    console.error("Gagal memuat riwayat:", error)
  }
}


/* ---------- LOAD BRAND SAAT PERTAMA KALI ---------- */
onMounted(() => {
  getBrands()
  getRequests()
})
</script>
