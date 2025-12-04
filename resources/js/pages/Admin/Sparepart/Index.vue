<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-bold text-gray-800">Data Sparepart</h2>
      <RouterLink
        to="/admin/spareparts/create"
        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-150"
      >
        + Tambah Sparepart
      </RouterLink>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow-2xl border border-gray-100">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">#</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Gambar</th> 
            
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Model Mobil</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Grade</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Stok</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Harga</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="(item, i) in spareparts" :key="item.id" class="hover:bg-gray-50 transition duration-100">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ i + 1 }}</td>
            
            <!-- TAMPILAN GAMBAR -->
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              <img 
                v-if="item.image"
                :src="getImageUrl(item.image)" 
                :alt="item.name" 
                class="w-16 h-16 object-cover rounded-md shadow-sm border"
                loading="lazy"
              />
              <span v-else class="text-gray-400 text-xs">No Image</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ item.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.model_mobil?.model || '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                <span :class="{'bg-green-100 text-green-800': item.grade === 'A', 'bg-yellow-100 text-yellow-800': item.grade === 'B', 'bg-red-100 text-red-800': item.grade === 'C'}" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ item.grade }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.stock }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span :class="{'bg-blue-100 text-blue-800': item.status === 'available', 'bg-red-100 text-red-800': item.status === 'sold_out'}" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ item.status }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ formatCurrency(item.price) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
              <RouterLink
                :to="`/admin/spareparts/${item.id}/edit`"
                 class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition duration-150"
              >Edit</RouterLink>
              <button
                @click="hapus(item.id)"
                class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150"
              >Hapus</button>
            </td>
          </tr>
          <tr v-if="spareparts.length === 0">
            <td colspan="9" class="px-6 py-4 text-center text-gray-500">Tidak ada data sparepart.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const spareparts = ref([])

const getImageUrl = (path) => {
    if (path && path.startsWith('images/spareparts')) {
        return `/storage/${path}`;
    }
    if (path.startsWith('/storage/')) {
    return path
    }
    return `/storage/images/spareparts/${path}`
}

// Fungsi untuk format mata uang (opsional, tapi disarankan)
const formatCurrency = (value) => {
  if (value === null || value === undefined) return '0'
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}

const loadData = async () => {
  try {
    const res = await axios.get('/api/admin/spareparts')
    // --- Bagian yang ditambahkan untuk penyortiran ---
    const sortedData = res.data.sort((a, b) => b.id - a.id) // Mengurutkan ID dari besar ke kecil (terbaru ke terlama)
    spareparts.value = sortedData
  } catch (error) {
    console.error('Gagal memuat data sparepart:', error)
  }
}

const hapus = async (id) => {
  if (confirm('Yakin ingin menghapus data ini?')) {
    try {
      await axios.delete(`/api/admin/spareparts/${id}`)
      loadData()
    } catch (error) {
      console.error('Gagal menghapus data:', error)
    }
  }
}

onMounted(loadData)
</script>