<template>

  <div class="p-6">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold text-[#161A1D] mb-6">
      Dashboard Admin
    </h1>

    <!-- ==== STATISTIC CARDS ==== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

      <div class="p-5 bg-white shadow rounded-xl border-l-4 border-[#BA181B]">
        <h2 class="text-sm text-[#660708] font-semibold">Total User</h2>
        <p class="text-3xl font-bold text-[#161A1D] mt-1">{{ totalUser }}</p>
      </div>

      <div class="p-5 bg-white shadow rounded-xl border-l-4 border-[#A4161A]">
        <h2 class="text-sm text-[#660708] font-semibold">Total Sparepart</h2>
        <p class="text-3xl font-bold text-[#161A1D] mt-1">{{ totalSparepart }}</p>
      </div>

      <div class="p-5 bg-white shadow rounded-xl border-l-4 border-[#660708]">

        <h2 class="text-sm text-[#161A1D] font-semibold">Total Penjualan</h2>
        <p class="text-3xl font-bold">{{ totalPenjualan }}</p>
      </div>
    </div>

    <!-- ==== CHARTS ==== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

      <!-- User Chart -->
      <div class="bg-white p-6 rounded-xl shadow border">
        <h3 class="text-lg font-bold text-[#161A1D] mb-3">Grafik User</h3>
        <canvas id="userChart"></canvas>
      </div>

      <!-- Sparepart Chart -->
      <div class="bg-white p-6 rounded-xl shadow border">

        <h3 class="text-lg font-bold text-[#161A1D] mb-3">Grafik Sparepart</h3>
        <canvas id="sparepartChart"></canvas>
      </div>

    </div>

    <!-- ==== TABEL SPAREPART TERBARU ==== -->
    <div class="bg-white p-6 rounded-xl shadow border">
      <h3 class="text-lg font-bold text-[#161A1D] mb-4">Sparepart Terbaru</h3>

      <table class="w-full text-sm">
        <thead class="bg-[#BA181B] text-white">
          <tr>
            <th class="py-2 px-3 text-left">Nama</th>
            <th class="py-2 px-3 text-left">Harga</th>
            <th class="py-2 px-3 text-left">Stok</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="item in latestSpareparts"
            :key="item.id"
            class="border-b hover:bg-[#F5F3F4] transition"
          >
            <td class="py-3 px-3 text-[#161A1D]">{{ item.nama }}</td>
            <td class="py-3 px-3 text-[#660708] font-semibold">
              Rp {{ item.harga }}
            </td>
            <td class="py-3 px-3">{{ item.stok }}</td>
          </tr>
        </tbody>
      </table>

    </div>

  </div>
</template>

<script setup>
import { Chart, registerables } from "chart.js";
import { onMounted } from "vue";

Chart.register(...registerables);

const props = defineProps({

  totalUser: Number,
  totalSparepart: Number,
  totalPenjualan: Number,
  latestSpareparts: Array,
  userPerMonth: Array,
  checkoutPerMonth: Array
});

onMounted(() => {
  // USER CHART
  const userLabels = (props.userPerMonth || []).map(x => "Bulan " + x.bulan);
  const userData = (props.userPerMonth || []).map(x => x.total);

  new Chart(document.getElementById("userChart"), {
    type: "line",
    data: {
      labels: userLabels,
      datasets: [
        {
          label: "User Baru",
          data: userData,
          borderColor: "#BA181B",
          backgroundColor: "#E5383B55",
          borderWidth: 2,

          tension: 0.3,
        },
      ],
    },
  });

  // SPAREPART CHART
  const spareLabels = (props.sparepartPerMonth || []).map(x => "Bulan " + x.bulan);
  const spareData = (props.sparepartPerMonth || []).map(x => x.total);

  new Chart(document.getElementById("sparepartChart"), {
    type: "bar",
    data: {
      labels: spareLabels,
      datasets: [
        {
          label: "Sparepart Masuk",
          data: spareData,
          backgroundColor: "#660708",
          borderRadius: 6,
        },
      ],
    },
  });
});

</script>