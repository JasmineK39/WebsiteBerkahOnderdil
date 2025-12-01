<template>
  <footer class="bg-linear-to-b from-[#660708] to-[#310304] text-[#D3D3D3] mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <!-- About Section -->
        <div>
          <div class="flex items-center space-x-3 mb-4">
            <div class="bg-linear-to-br from-[#BA181B] to-[#E5383B] p-2 rounded-lg shadow-lg">
              <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94L14.4 2.81c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
              </svg>
            </div>
            <div>
              <h3 class="text-white font-bold text-xl">BERKAH ONDERDIL</h3>
              <p class="text-xs text-[#B1A7A6]">Toko Sparepart Termurah di Sukoharjo</p>
            </div>
          </div>

          <p class="text-sm leading-relaxed mb-4">
            Kami adalah penyedia sparepart mobil terpercaya dengan koleksi lengkap dan harga kompetitif. Melayani jual
            beli onderdil mobil bekas berkualitas.
          </p>

          <!-- Sosial Media -->
          <div class="flex space-x-3">

            <!-- Facebook -->
            <a href="https://www.facebook.com/share/1AFXneyn8N/" target="_blank"
              class="bg-[#660708] hover:bg-[#E5383B] p-2 rounded-lg transition-all">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
              </svg>
            </a>

            <!-- Instagram -->
            <a href="https://www.instagram.com/berkah_sparepart_sukoharjo?igsh=dDN0bnhhcG51bDFm" target="_blank"
              class="bg-[#660708] hover:bg-[#E5383B] p-2 rounded-lg transition-all">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
              </svg>
            </a>

            <!-- WhatsApp -->
            <a href="https://wa.me/6281326553304" target="_blank"
              class="bg-[#660708] hover:bg-[#E5383B] p-2 rounded-lg transition-all">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.25-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" />
              </svg>
            </a>

            <!-- TikTok -->
            <a href="https://www.tiktok.com/@bismilah.laris?_r=1&_t=ZS-91iXeLzboyu" target="_blank"
              class="bg-[#660708] hover:bg-[#E5383B] p-2 rounded-lg transition-all">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M12.5 2c.7 0 1.4 0 2.1.1.2 1 .6 1.9 1.2 2.7.6.8 1.5 1.4 2.4 1.7.5.2 1 .3 1.5.3v3.2c-1.3-.1-2.6-.5-3.8-1.1v5.8c0 1.3-.3 2.5-.9 3.6-.6 1.1-1.5 2-2.6 2.6-1.1.6-2.3.9-3.6.9-1.3 0-2.5-.3-3.6-.9-1.1-.6-2-1.5-2.6-2.6-.6-1.1-.9-2.3-.9-3.6s.3-2.5.9-3.6c.6-1.1 1.5-2 2.6-2.6 1.1-.6 2.3-.9 3.6-.9.5 0 1 .1 1.4.2v3.3c-.3-.1-.7-.2-1-.2-1.1 0-2.1.4-2.8 1.2-.7.8-1.2 1.8-1.2 2.8s.4 2.1 1.2 2.8c.8.7 1.8 1.2 2.8 1.2s2.1-.4 2.8-1.2c.7-.8 1.2-1.8 1.2-2.8V2z" />
              </svg>
            </a>

          </div>
        </div>


        <!-- Customer Service -->
        <div>
          <h3 class="text-white font-bold text-lg mb-4 border-b-2 border-[#BA181B] pb-2 inline-block">Customer Service
          </h3>
          <ul class="space-y-3">
            <li>
              <router-link to="/help" class="hover:text-[#E5383B] transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Help / FAQS</span>
              </router-link>
            </li>
            <li>
              <a href="https://wa.me/6281326553304" class="hover:text-[#E5383B] transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Contact Us</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Tentang Kami -->
        <div>
          <h3 class="text-white font-bold text-lg mb-4 border-b-2 border-[#BA181B] pb-2 inline-block">Tentang Kami</h3>
          <ul class="space-y-3">
            <li>
              <router-link to="/about" class="hover:text-[#E5383B] transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Tentang Berkah Onderdil</span>
              </router-link>
            </li>
            <li>
              <router-link to="/about#lokasi-toko" class="hover:text-[#E5383B] transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Lokasi Toko</span>
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h3 class="text-white font-bold text-lg mb-4 border-b-2 border-[#BA181B] pb-2 inline-block">Hubungi Kami</h3>
          <div class="space-y-4">
            <div class="flex items-start space-x-3">
              <div class="bg-[#660708] p-2 rounded-lg shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div class="text-sm">
                <p class="text-white font-semibold mb-1">Alamat</p>
                <p>Jl. Sari RT.04 RW.01<br />Mranggen, Polokarto<br />Sukoharjo, Jawa Tengah</p>
              </div>
            </div>

            <div class="flex items-start space-x-3">
              <div class="bg-[#660708] p-2 rounded-lg shrink-0">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                </svg>
              </div>
              <div class="text-sm">
                <p class="text-white font-semibold mb-1">Telepon</p>
                <p>081 326 553 304</p>
                <p class="text-[#B1A7A6] text-xs mt-1">Senin - Sabtu: 08:00 - 17:00</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="border-t border-[#660708] pt-6 mt-8">
        <div class="flex flex-col md:flex-row items-center justify-between text-sm">
          <p class="text-[#B1A7A6]">
            &copy; 2025 <span class="text-white font-semibold">Berkah Onderdil</span>. Semua hak dilindungi.
          </p>
          <div class="flex items-center space-x-4 mt-4 md:mt-0">
            <span class="text-[#B1A7A6]">Powered by</span>
            <span class="text-white font-semibold">Limacue</span>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
<style scoped></style>