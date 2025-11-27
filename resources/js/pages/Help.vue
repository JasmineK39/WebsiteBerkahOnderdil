<template>
  <section class="max-w-6xl mx-auto px-6 py-12">
    <!-- Judul Halaman -->
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-[#660708]">Pusat Bantuan</h1>
      <p class="mt-2 text-gray-600">
        Temukan jawaban atas pertanyaanmu atau hubungi kami jika butuh bantuan lebih lanjut.
      </p>
    </div>

    <!-- FAQ -->
    <div class="bg-white shadow-lg rounded-2xl p-6 mb-10">
      <h2 class="text-2xl font-semibold text-[#A4161A] mb-4">Pertanyaan yang Sering Diajukan (FAQ)</h2>

      <div class="space-y-4">
        <div>
          <h3 class="font-semibold text-[#660708]">1. Apakah semua sparepart yang dijual baru?</h3>
          <p class="text-gray-700">Ya, semua sparepart yang kami jual adalah produk baru dan asli.</p>
        </div>

        <div>
          <h3 class="font-semibold text-[#660708]">2. Apakah tersedia layanan pemasangan?</h3>
          <p class="text-gray-700">Untuk saat ini belum, namun kami dapat merekomendasikan bengkel terdekat.</p>
        </div>

        <div>
          <h3 class="font-semibold text-[#660708]">3. Pengiriman dilakukan dengan apa?</h3>
          <p class="text-gray-700">
            Kami menggunakan ekspedisi terpercaya seperti J&T, JNE, SiCepat, atau bisa COD area sekitar Sukoharjo.
          </p>
        </div>

        <div>
          <h3 class="font-semibold text-[#660708]">4. Apakah bisa cek ketersediaan stok?</h3>
          <p class="text-gray-700">Tentu! Hubungi WhatsApp kami, kami akan cekkan secara langsung.</p>
        </div>
      </div>
    </div>

    <!-- Bantuan Kontak -->
    <div class="bg-white shadow-lg rounded-2xl p-6">
      <h2 class="text-2xl font-semibold text-[#A4161A] mb-4">Butuh Bantuan?</h2>
      <p class="text-gray-700 mb-4">
        Jika pertanyaanmu belum terjawab, kamu bisa menghubungi kami secara langsung.
      </p>

      <div class="space-y-3">
        <p class="flex items-center space-x-3">
          <svg class="w-6 h-6 text-[#660708]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l3 7-1.34 2.68a2 2 0 001.8 2.92H19"></path>
            <circle cx="9" cy="20" r="1"></circle>
            <circle cx="16" cy="20" r="1"></circle>
          </svg>
          <span>Jam Operasional: Senin–Minggu · 09.00–21.00</span>
        </p>

        <p class="flex items-center space-x-3">
          <svg class="w-6 h-6 text-[#660708]" fill="currentColor" viewBox="0 0 24 24">
            <path
              d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.25-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" />
          </svg>
          <span>WhatsApp: 0813-2655-3304</span>
        </p>
      </div>

      <a href="https://wa.me/6281326553304"
        class="mt-6 inline-block bg-[#660708] hover:bg-[#E5383B] text-white px-5 py-3 rounded-xl transition-all">
        Chat via WhatsApp
      </a>
    </div>
  </section>
</template>

<script>
export default {
  name: "HelpPage",
};
</script>

<style scoped>
</style>
