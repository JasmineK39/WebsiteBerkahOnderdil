<template>
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Status Request</h2>

    <!-- Error Message -->
    <div
      v-if="errors"
      class="mb-4 p-3 bg-red-100 text-red-700 border border-red-200 rounded"
    >
      <ul class="list-disc pl-5">
        <li v-for="(messages, field) in errors" :key="field">
          {{ messages.join(', ') }}
        </li>
      </ul>
    </div>

    <form @submit.prevent="update">
      <!-- User -->
      <div class="mb-3">
        <label class="block mb-1 font-medium">User</label>
        <input
          v-model="form.user_name"
          class="w-full border rounded p-2 bg-gray-100"
          disabled
        />
      </div>

      <!-- Merek -->
      <div class="mb-3">
        <label class="block mb-1 font-medium">Merek Mobil</label>
        <input
          v-model="form.merek"
          class="w-full border rounded p-2 bg-gray-100"
          disabled
        />
      </div>

      <!-- Model -->
      <div class="mb-3">
        <label class="block mb-1 font-medium">Model</label>
        <input
          v-model="form.model"
          class="w-full border rounded p-2 bg-gray-100"
          disabled
        />
      </div>

      <!-- Tahun -->
      <div class="mb-3">
        <label class="block mb-1 font-medium">Tahun</label>
        <input
          v-model="form.tahun"
          class="w-full border rounded p-2 bg-gray-100"
          disabled
        />
      </div>

      <!-- Nama Sparepart -->
      <div class="mb-3">
        <label class="block mb-1 font-medium">Nama Sparepart</label>
        <input
          v-model="form.nama_sparepart"
          class="w-full border rounded p-2 bg-gray-100"
          disabled
        />
      </div>

      <!-- Catatan -->
      <div class="mb-3">
        <label class="block mb-1 font-medium">Catatan</label>
        <textarea
          v-model="form.catatan"
          class="w-full border rounded p-2 bg-gray-100"
          rows="2"
          disabled
        />
      </div>

      <!-- Status -->
      <div class="mb-4">
        <label class="block mb-1 font-medium">Status Request</label>
        <select v-model="form.status" class="w-full border rounded p-2">
          <option value="Menunggu">Menunggu</option>
          <option value="Diproses">Diproses</option>
          <option value="Selesai">Selesai</option>
          <option value="Ditolak">Ditolak</option>
        </select>
      </div>

      <!-- Button -->
      <button
        type="submit"
        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
      >
        Update Status
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()
const errors = ref(null)

const form = reactive({
  user_name: '',
  merek: '',
  model: '',
  tahun: '',
  nama_sparepart: '',
  catatan: '',
  status: 'Menunggu',
})

const loadData = async () => {
  try {
    const res = await axios.get(`/api/admin/requests/${route.params.id}`)

    // Isi form berdasarkan data dari API
    form.user_name = res.data.user_name || '-'
    form.merek = res.data.brand || res.data.merek || '-'
    form.model = res.data.model || '-'
    form.tahun = res.data.year || res.data.tahun || '-'
    form.nama_sparepart = res.data.part_name || res.data.nama_sparepart || '-'
    form.catatan = res.data.note || res.data.catatan || '-'
    form.status = res.data.status || 'Menunggu'
  } catch (error) {
    console.error('Gagal memuat data request:', error)
    alert('Gagal memuat data request.')
  }
}

const update = async () => {
  errors.value = null
  try {
    await axios.put(`/api/admin/requests/${route.params.id}`, {
      status: form.status,
    })
    router.push('/admin/requests')
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Gagal mengupdate status:', error)
      alert('Terjadi kesalahan saat update status.')
    }
  }
}

onMounted(loadData)
</script>
