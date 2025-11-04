<template>

  <div>

    <div class="flex justify-between items-center mb-4">

      <h2 class="text-xl font-semibold">Data Sparepart</h2>

      <RouterLink

        to="/admin/spareparts/create"

        class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600"

      >

        + Tambah Sparepart

      </RouterLink>

    </div>



    <table class="w-full bg-white rounded shadow">

      <thead class="bg-gray-200">

        <tr>

          <th class="p-2 text-left">#</th>

          <th class="p-2 text-left">Nama</th>

          <th class="p-2 text-left">Model Mobil</th>

          <th class="p-2 text-left">Grade</th>

          <th class="p-2 text-left">Stok</th>

          <th class="p-2 text-left">Status</th>

          <th class="p-2 text-left">Harga</th>

          <th class="p-2 text-left">Aksi</th>

        </tr>

      </thead>

      <tbody>

        <tr v-for="(item, i) in spareparts" :key="item.id" class="border-b">

          <td class="p-2">{{ i + 1 }}</td>

          <td class="p-2">{{ item.name }}</td>

          <td class="p-2">{{ item.model_mobil?.name || '-' }}</td>

          <td class="p-2">{{ item.grade }}</td>

          <td class="p-2">{{ item.stock }}</td>

          <td class="p-2">{{ item.status }}</td>

          <td class="p-2">Rp {{ formatCurrency(item.price) }}</td>

          <td class="p-2 space-x-2">

            <RouterLink

              :to="`/admin/spareparts/${item.id}/edit`"

              class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500"

            >Edit</RouterLink>

            <button

              @click="hapus(item.id)"

              class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600"

            >Hapus</button>

          </td>

        </tr>

      </tbody>

    </table>

  </div>

</template>



<script setup>

import { ref, onMounted } from 'vue'

import axios from 'axios'



const spareparts = ref([])



// Fungsi untuk format mata uang (opsional, tapi disarankan)

const formatCurrency = (value) => {

  if (value === null || value === undefined) return '0'

  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')

}



const loadData = async () => {

  try {

    const res = await axios.get('/api/admin/spareparts')

    spareparts.value = res.data

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