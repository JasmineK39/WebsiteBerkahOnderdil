import { ref, computed } from 'vue'

export default function useCatalog() {
  const cars = ref([
    { id: 1, name: 'Toyota Avanza', year: 2020, price: 150000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 2, name: 'Honda Civic', year: 2021, price: 250000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 3, name: 'Mitsubishi Xpander', year: 2022, price: 200000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 4, name: 'Daihatsu Terios', year: 2021, price: 180000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 5, name: 'Toyota Rush', year: 2022, price: 210000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 6, name: 'Suzuki Ertiga', year: 2020, price: 175000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 7, name: 'Honda Brio', year: 2021, price: 160000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 8, name: 'Nissan Livina', year: 2022, price: 195000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 9, name: 'Mazda CX-5', year: 2023, price: 400000000, image: '/placeholder.svg?height=200&width=300' },
    { id: 10, name: 'Wuling Almaz', year: 2023, price: 320000000, image: '/placeholder.svg?height=200&width=300' }
  ])

  const currentPage = ref(1)
  const itemsPerPage = 8

  const totalPages = computed(() => Math.ceil(cars.value.length / itemsPerPage))

  const paginatedCars = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return cars.value.slice(start, start + itemsPerPage)
  })

  function nextPage() {
    if (currentPage.value < totalPages.value) currentPage.value++
  }

  function prevPage() {
    if (currentPage.value > 1) currentPage.value--
  }

  function goToPage(page) {
    currentPage.value = page
  }

  return {
    cars,
    paginatedCars,
    currentPage,
    totalPages,
    nextPage,
    prevPage,
    goToPage
  }
}
