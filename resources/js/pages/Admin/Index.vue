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
        <canvas ref="userChartRef"></canvas>
      </div>

      <!-- Checkout Chart -->
      <div class="bg-white p-6 rounded-xl shadow border">
        <h3 class="text-lg font-bold text-[#161A1D] mb-3">Grafik Checkout</h3>
        <canvas ref="checkoutChartRef"></canvas>
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
            <td class="py-3 px-3 text-[#161A1D]">
              {{ item.nama_sparepart ?? item.name }}
            </td>
            <td class="py-3 px-3 text-[#660708] font-semibold">
              Rp {{ item.price }}
            </td>
            <td class="py-3 px-3">
              {{ item.stok ?? item.stock }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";
import axios from "axios";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

// ================= STATE =================
const totalUser = ref(0);
const totalSparepart = ref(0);
const totalPenjualan = ref(0);
const latestSpareparts = ref([]);
const userPerMonth = ref([]);
const checkoutPerMonth = ref([]);

// ================= CANVAS REF =================
const userChartRef = ref(null);
const checkoutChartRef = ref(null);

// ================= CHART INSTANCE =================
let userChartInstance = null;
let checkoutChartInstance = null;

// ================= FETCH DASHBOARD =================
const fetchDashboard = async () => {
  try {
    const res = await axios.get("/api/admin/dashboard", {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        Accept: "application/json",
      },
    });

    totalUser.value = res.data.totalUser;
    totalSparepart.value = res.data.totalSparepart;
    totalPenjualan.value = res.data.totalPenjualan;
    latestSpareparts.value = res.data.latestSpareparts;
    userPerMonth.value = res.data.userPerMonth;
    checkoutPerMonth.value = res.data.checkoutPerMonth;

    renderCharts();
  } catch (error) {
    console.error("Gagal mengambil data dashboard:", error);
  }
};

// ================= RENDER CHART =================
const renderCharts = async () => {
  await nextTick();

  // Destroy chart lama (anti double render)
  if (userChartInstance) userChartInstance.destroy();
  if (checkoutChartInstance) checkoutChartInstance.destroy();

  // USER CHART
  userChartInstance = new Chart(userChartRef.value, {
    type: "line",
    data: {
      labels: userPerMonth.value.map(x => "Bulan " + x.bulan),
      datasets: [{
        label: "User Baru",
        data: userPerMonth.value.map(x => x.total),
        borderColor: "#BA181B",
        backgroundColor: "#E5383B55",
        tension: 0.3,
      }],
    },
  });

  // CHECKOUT CHART
  checkoutChartInstance = new Chart(checkoutChartRef.value, {
    type: "bar",
    data: {
      labels: checkoutPerMonth.value.map(x => "Bulan " + x.bulan),
      datasets: [{
        label: "Total Checkout",
        data: checkoutPerMonth.value.map(x => x.total),
        backgroundColor: "#660708",
        borderRadius: 6,
      }],
    },
  });
};

onMounted(fetchDashboard);
</script>
