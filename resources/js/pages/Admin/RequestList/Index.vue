<template>
  <div>
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Data Request Sparepart</h2>
    </div>

    <table class="w-full bg-white rounded shadow">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 text-left">#</th>
          <th class="p-2 text-left">User</th>
          <th class="p-2 text-left">Merek</th>
          <th class="p-2 text-left">Model</th>
          <th class="p-2 text-left">Tahun</th>
          <th class="p-2 text-left">Sparepart</th>
          <th class="p-2 text-left">Catatan</th>
          <th class="p-2 text-left">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(req, i) in requests"
          :key="req.id"
          class="border-b hover:bg-gray-50"
        >
          <td class="p-2">{{ i + 1 }}</td>
<<<<<<< HEAD
          <td class="p-2">{{ req.user?.name || '-' }}</td>
          <td class="p-2">{{ req.merek }}</td>
          <td class="p-2">{{ req.model }}</td>
          <td class="p-2">{{ req.tahun }}</td>
          <td class="p-2">{{ req.nama_sparepart }}</td>
          <td class="p-2">{{ req.catatan || '-' }}</td>
          <td class="p-2">
            <select
              v-model="req.status"
              @change="updateStatus(req)"
              class="border rounded px-2 py-1 focus:ring-2 focus:ring-blue-500"
            >
              <option value="Menunggu">Menunggu</option>
              <option value="Diproses">Diproses</option>
              <option value="Selesai">Selesai</option>
              <option value="Ditolak">Ditolak</option>
            </select>
          </td>
=======
          <td class="p-2">{{ req.user_name }}</td>
<td class="p-2">{{ req.brand }}</td>
<td class="p-2">{{ req.model }}</td>
<td class="p-2">{{ req.year }}</td>
<td class="p-2">{{ req.part_name }}</td>
<td class="p-2">{{ req.note || '-' }}</td>

<td class="p-2">
  <select
    v-model="req.status"
    @change="updateStatus(req)"
    class="border rounded px-2 py-1"
  >
    <option value="pending">Menunggu</option>
    <option value="in_progress">Diproses</option>
    <option value="fulfilled">Selesai</option>
    <option value="rejected">Ditolak</option>
  </select>
</td>

>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
        </tr>
      </tbody>
    </table>

    <div v-if="loading" class="text-center mt-4 text-gray-600">Memuat data...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const requests = ref([])
const loading = ref(false)

const loadData = async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/admin/requests')
    requests.value = res.data
  } catch (error) {
    console.error('Gagal memuat data request:', error)
  } finally {
    loading.value = false
  }
}

const updateStatus = async (req) => {
  try {
    await axios.put(`/api/admin/requests/${req.id}`, { status: req.status })
    console.log(`Status request ${req.id} diperbarui ke ${req.status}`)
  } catch (error) {
    console.error('Gagal memperbarui status:', error)
    alert('Terjadi kesalahan saat memperbarui status.')
  }
}

onMounted(loadData)
</script>
