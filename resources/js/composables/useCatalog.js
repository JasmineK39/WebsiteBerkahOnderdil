import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

export default function useCatalog() {
  const cars = ref([])
  const currentPage = ref(1)
  const itemsPerPage = ref(8)
  const totalPages = ref(1)
  const loading = ref(false)
  const error = ref(null)

  async function fetchCars(page = 1) {
    loading.value = true
    error.value = null
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/cars?page=${page}`)
      cars.value = response.data.data        // Data mobil
      totalPages.value = response.data.last_page // Total halaman dari pagination Laravel
      currentPage.value = response.data.current_page
    } catch (err) {
      error.value = 'Gagal mengambil data mobil.'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  function nextPage() {
    if (currentPage.value < totalPages.value) fetchCars(currentPage.value + 1)
  }

  function prevPage() {
    if (currentPage.value > 1) fetchCars(currentPage.value - 1)
  }

  function goToPage(page) {
    if (page >= 1 && page <= totalPages.value) fetchCars(page)
  }

  // Ambil data saat komponen pertama kali dimount
  onMounted(() => {
    fetchCars()
  })

  return {
    cars,
    currentPage,
    totalPages,
    loading,
    error,
    nextPage,
    prevPage,
    goToPage
  }
}
