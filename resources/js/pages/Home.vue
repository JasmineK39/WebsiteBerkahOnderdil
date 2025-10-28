<template>
  <div class="min-h-screen bg-dark">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-dark via-primary to-primary-light overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-10">
        <div
          class="absolute inset-0"
          style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"
        ></div>
      </div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="text-center">
          <div class="inline-block mb-6">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-2 tracking-tight">
              BERKAH ONDERDIL
            </h1>
            <div class="h-1 bg-white rounded-full"></div>
          </div>

          <p class="text-xl md:text-2xl text-light-secondary mb-4 max-w-3xl mx-auto font-medium">
            Pusat Sparepart Mobil Terlengkap & Terpercaya
          </p>
          <p class="text-base md:text-lg text-light-secondary/80 mb-10 max-w-2xl mx-auto">
            Kualitas original, harga bersahabat, siap kirim ke seluruh Indonesia
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button
              class="bg-white text-primary px-8 py-4 rounded-lg font-bold text-lg hover:bg-light hover:scale-105 transition-all duration-300 shadow-lg"
            >
              Lihat Katalog Lengkap
            </button>
            <button
              class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-primary transition-all duration-300"
            >
              Hubungi Kami
            </button>
          </div>
        </div>
      </div>

      <!-- Wave Divider -->
      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z"
            fill="#0B090A"
          />
        </svg>
      </div>
    </section>

    <!-- Catalog Section -->
    <section class="bg-dark-secondary py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-4xl md:text-5xl font-black text-light mb-4">
            Katalog <span class="text-primary-light">Mobil</span>
          </h2>
          <p class="text-gray text-lg max-w-2xl mx-auto">
            Temukan sparepart untuk berbagai merek dan tipe mobil favorit Anda
          </p>
        </div>

        <!-- Grid produk -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
          <CatalogCard
            v-for="car in paginatedCars"
            :key="car.id"
            :product="car"
          />
        </div>

        <!-- Pagination -->
        <div class="flex justify-center items-center space-x-3 mt-6">
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="bg-primary-dark text-white px-4 py-2 rounded-lg font-semibold disabled:opacity-50 hover:bg-primary transition-all duration-300"
          >
            ←
          </button>

          <button
            v-for="page in totalPages"
            :key="page"
            @click="goToPage(page)"
            :class="[
              'px-4 py-2 rounded-lg font-semibold transition-all duration-300',
              page === currentPage
                ? 'bg-primary text-white'
                : 'bg-dark text-gray hover:bg-primary-light hover:text-white'
            ]"
          >
            {{ page }}
          </button>

          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="bg-primary-dark text-white px-4 py-2 rounded-lg font-semibold disabled:opacity-50 hover:bg-primary transition-all duration-300"
          >
            →
          </button>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <!-- (bagian bawah tetap sama persis, tidak diubah) -->
    <section class="bg-dark py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl md:text-5xl font-black text-light mb-4">
            Mengapa <span class="text-primary-light">Berkah Onderdil</span>?
          </h2>
          <p class="text-gray text-lg">Kepuasan pelanggan adalah prioritas kami</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            class="bg-dark-secondary border border-gray-700 rounded-xl p-8 hover:border-primary transition-all duration-300 hover:shadow-xl hover:shadow-primary/20"
          >
            <div
              class="bg-gradient-to-br from-primary-dark to-primary w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg"
            >
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-light mb-3 text-center">100% Original</h3>
            <p class="text-gray text-center leading-relaxed">
              Semua produk dijamin original dari pabrikan resmi dengan sertifikat keaslian
            </p>
          </div>

          <div
            class="bg-dark-secondary border border-gray-700 rounded-xl p-8 hover:border-primary transition-all duration-300 hover:shadow-xl hover:shadow-primary/20"
          >
            <div
              class="bg-gradient-to-br from-primary-dark to-primary w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg"
            >
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 10V3L4 14h7v7l9-11h-7z"
                />
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-light mb-3 text-center">Pengiriman Kilat</h3>
            <p class="text-gray text-center leading-relaxed">
              Gratis ongkir untuk pembelian tertentu, pengiriman express ke seluruh Indonesia
            </p>
          </div>

          <div
            class="bg-dark-secondary border border-gray-700 rounded-xl p-8 hover:border-primary transition-all duration-300 hover:shadow-xl hover:shadow-primary/20"
          >
            <div
              class="bg-gradient-to-br from-primary-dark to-primary w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg"
            >
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-light mb-3 text-center">Harga Terbaik</h3>
            <p class="text-gray text-center leading-relaxed">
              Harga kompetitif dengan sistem pembayaran fleksibel dan promo menarik setiap bulan
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-primary-dark via-primary to-primary-light py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-black text-white mb-4">
          Butuh Bantuan Menemukan Sparepart?
        </h2>
        <p class="text-light-secondary text-lg mb-8">
          Tim kami siap membantu Anda 24/7 via WhatsApp atau telepon
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button
            class="bg-white text-primary px-8 py-4 rounded-lg font-bold text-lg hover:bg-light hover:scale-105 transition-all duration-300 shadow-lg flex items-center justify-center gap-2"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
              />
            </svg>
            Chat via WhatsApp
          </button>
          <button
            class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-primary transition-all duration-300"
          >
            Telepon Sekarang
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import CatalogCard from '../components/CatalogCard.vue'
import { useCatalog } from '../composables/useCatalog.js'

// Ambil data & logic dari composable
const { paginatedCars, currentPage, totalPages, nextPage, prevPage, goToPage } = useCatalog()
</script>

<style scoped>
/* semua style-mu tetap sama persis */
.bg-dark {
  background-color: #0B090A;
}
.bg-dark-secondary {
  background-color: #161A1D;
}
.bg-primary {
  background-color: #BA181B;
}
.bg-primary-dark {
  background-color: #660708;
}
.from-primary-dark {
  --tw-gradient-from: #660708;
}
.via-primary {
  --tw-gradient-via: #BA181B;
}
.to-primary {
  --tw-gradient-to: #BA181B;
}
.to-primary-light {
  --tw-gradient-to: #E5383B;
}
.text-primary {
  color: #BA181B;
}
.text-primary-light {
  color: #E5383B;
}
.text-light {
  color: #F5F3F4;
}
.text-light-secondary {
  color: #D3D3D3;
}
.text-gray {
  color: #B1A7A6;
}
.border-gray-700 {
  border-color: #2a2d31;
}
.hover\:border-primary:hover {
  border-color: #BA181B;
}
.hover\:bg-primary-dark:hover {
  background-color: #660708;
}
.hover\:bg-light:hover {
  background-color: #F5F3F4;
}
.shadow-primary\/20 {
  --tw-shadow-color: rgba(186, 24, 27, 0.2);
  --tw-shadow: 0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);
}
</style>
