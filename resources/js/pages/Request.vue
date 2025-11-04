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
        <form @submit.prevent="submitForm" class="bg-red-950 text-white p-6 rounded-xl shadow-lg mb-10">
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-2 font-medium">Merek Mobil</label>
              <input v-model="form.brand_req" type="text" placeholder="Contoh: Toyota"
                class="w-full px-3 py-2 rounded border border-red-700 bg-red-800/50 focus:ring-2 focus:ring-red-500 outline-none" required>
            </div>
            <div>
              <label class="block mb-2 font-medium">Model Mobil</label>
              <input v-model="form.model_req" type="text" placeholder="Contoh: Avanza"
                class="w-full px-3 py-2 rounded border border-red-700 bg-red-800/50 focus:ring-2 focus:ring-red-500 outline-none" required>
            </div>
            <div>
              <label class="block mb-2 font-medium">Tahun</label>
              <input v-model="form.year_req" type="number" placeholder="Contoh: 2018"
                class="w-full px-3 py-2 rounded border border-red-700 bg-red-800/50 focus:ring-2 focus:ring-red-500 outline-none" required>
            </div>
            <div>
              <label class="block mb-2 font-medium">Nama Sparepart</label>
              <input v-model="form.sparepart_req" type="text" placeholder="Contoh: Kampas Rem"
                class="w-full px-3 py-2 rounded border border-red-700 bg-red-800/50 focus:ring-2 focus:ring-red-500 outline-none" required>
            </div>
          </div>

          <div class="mt-4">
            <label class="block mb-2 font-medium">Catatan (opsional)</label>
            <textarea v-model="form.note" rows="3" placeholder="Tuliskan detail tambahan..."
              class="w-full px-3 py-2 rounded border border-red-700 bg-red-800/50 focus:ring-2 focus:ring-red-500 outline-none"></textarea>
          </div>

          <div class="text-right mt-6">
            <button type="submit"
              class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-all duration-200">
              Kirim Request
            </button>
          </div>
        </form>

        <!-- Tabel Riwayat Request -->
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
              <!-- Baris riwayat akan muncul hanya jika ada data -->
              <tr v-for="(req, index) in requests" :key="index" class="border-t border-red-700 hover:bg-red-800/40">
                <td class="px-4 py-3">{{ index + 1 }}</td>
                <td class="px-4 py-3">{{ req.brand_req }}</td>
                <td class="px-4 py-3">{{ req.model_req }}</td>
                <td class="px-4 py-3">{{ req.year_req }}</td>
                <td class="px-4 py-3">{{ req.sparepart_req }}</td>
                <td class="px-4 py-3">{{ req.note || '-' }}</td>
                <td class="px-4 py-3">
                  <span v-if="req.status === 'Menunggu Konfirmasi'" class="bg-yellow-400 text-black px-2 py-1 rounded">
                    {{ req.status }}
                  </span>
                  <span v-else-if="req.status === 'Diterima'" class="bg-green-600 px-2 py-1 rounded">
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
import { ref } from 'vue'
import axios from 'axios'

// Form input
const form = ref({
  brand_req: '',
  model_req: '',
  year_req: '',
  sparepart_req: '',
  note: ''
})

// Riwayat request, kosong di awal
const requests = ref([])

// Notifikasi sukses
const successMessage = ref('')

// Submit form
const submitForm = async () => {
  try {
    // Kirim request ke API
    const res = await axios.post('/api/requests', form.value)

    // Tambahkan request baru ke riwayat langsung
    // Jika API tidak mengembalikan status, kita set default "Menunggu Konfirmasi"
    const newRequest = {
      ...form.value,
      status: res.data.status || 'Menunggu Konfirmasi'
    }

    requests.value.push(newRequest)

    // Reset form
    form.value = { brand_req: '', model_req: '', year_req: '', sparepart_req: '', note: '' }

    // Notifikasi sukses
    successMessage.value = 'Request berhasil dikirim!'
    setTimeout(() => (successMessage.value = ''), 4000)
  } catch (error) {
    console.error('Gagal mengirim request:', error)
  }
}
</script>
