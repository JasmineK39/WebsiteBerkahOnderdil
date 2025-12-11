<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div v-if="loading" class="text-center text-gray-600 text-lg">Memuat data...</div>
      <div v-else-if="error" class="text-center text-red-600">{{ error }}</div>

      <div
        v-else-if="product"
        class="bg-white shadow-lg rounded-lg p-6 grid grid-cols-1 lg:grid-cols-2 gap-8"
      >
        <!-- Gambar Produk -->
        <div>
          <img
            :src="product.image"
            class="w-full h-96 object-cover rounded-lg"
          />
        </div>

        <!-- Detail Produk -->
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ product.name }}
          </h1>

          <div class="flex items-center gap-3 mb-4">
            <div v-if="product.average_rating > 0" class="flex items-center space-x-2">
                    <div v-html="renderStarRating(product.average_rating)" class="flex text-yellow-500 text-2xl leading-none"></div>
                    <span class="text-xl font-bold text-gray-800">{{ product.average_rating.toFixed(1) }}</span>
                    <a href="#reviews" class="text-sm text-blue-600 hover:text-blue-800 transition duration-150">
                        ({{ product.reviews_count }} ulasan)
                    </a>
            </div>
            <div v-else class="text-gray-500 text-sm italic">
                Belum ada ulasan
            </div>
          </div>

          <p class="text-gray-500 text-sm mb-4">
            Brand: {{ product.brand }} | Type: {{ product.type }}
          </p>

          <div class="flex items-center gap-3 mb-4">
            <span
              class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-sm font-semibold"
            >
              Grade {{ product.grade }}
            </span>
            <span
              class="px-3 py-1 rounded-full text-sm font-semibold"
              :class="
                product.stock > 0
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'
              "
            >
              {{ product.stock > 0 ? `Stok ${product.stock}` : 'Stok Habis' }}
            </span>
          </div>

          <p class="text-3xl font-bold text-blue-600 mb-6">
            Rp {{ formatPrice(product.price) }}
          </p>

          <!-- Deskripsi Produk -->
          <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h2>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
              {{ product.description }}
            </p>
          </div>

          <!-- Tombol Aksi -->
          <div class="flex flex-col sm:flex-row gap-4">
            <button
              class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition"
              @click="addToCart(product)"
            >
              Tambah ke Keranjang
            </button>
              <button
                    @click="chatViaWhatsApp"
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition text-center"
                  >
                    Chat via WhatsApp
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '../store/cart'
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const cart = useCartStore()
const router = useRouter()
const route = useRoute()
const product = ref(null)
const loading = ref(true)
const error = ref(null)

const renderStarRating = (rating, starClass = 'text-lg') => {
    if (typeof rating !== 'number') rating = 0;
    const normalizedRating = Math.max(0, Math.min(5, rating));
    let starsHtml = '';
    
    // Ikon Unicode
    const FULL_STAR = '★'; 
    const EMPTY_STAR = '☆'; 
    
    const roundedRating = Math.round(normalizedRating * 2) / 2; // Pembulatan ke 0.5 terdekat
    
    // Hitung bintang penuh dan setengah
    const fullStars = Math.floor(roundedRating); 
    const hasHalfStar = (roundedRating % 1) !== 0; 
    
    // Tambahkan bintang penuh
    for (let i = 0; i < fullStars; i++) {
        starsHtml += FULL_STAR;
    }

    // Tambahkan bintang kosong sisanya
    const remainingStars = 5 - (hasHalfStar ? fullStars + 1 : fullStars);
    
    // Untuk tampilan sederhana, kita tidak menggunakan setengah bintang Unicode (karena sulit di-CSS)
    // Kita gunakan bintang penuh jika rating dibulatkan ke atas.
    
    // Tambahkan bintang kosong sisanya (Total 5 bintang)
    for (let i = 0; i < (5 - fullStars - (hasHalfStar ? 1 : 0)); i++) {
         starsHtml += EMPTY_STAR;
    }
    
    // Karena kita tidak bisa menggunakan bintang setengah di Unicode dengan mudah,
    // kita akan menggunakan pembulatan ke atas untuk tampilan yang lebih baik (user prefer to see more stars)
    // Di sini saya kembali ke logika sederhana untuk rendering string
    
    // Logika Finalisasi Bintang: Menggunakan 5 ikon penuh/kosong
    let finalHtml = '';
    let i = 0;
    for (i = 0; i < fullStars; i++) {
        finalHtml += FULL_STAR;
    }
    // Jika ada setengah, tambahkan bintang penuh lagi (praktik visual yang umum)
    if(hasHalfStar) {
        finalHtml += FULL_STAR;
        i++;
    }
    // Tambahkan bintang kosong sisanya
    for (let j = i; j < 5; j++) {
        finalHtml += EMPTY_STAR;
    }


    return `<span class="${starClass} tracking-tighter">${finalHtml}</span>`;
};

function addToCart(sparepart) {
  cart.addToCart(sparepart)      // Tambahkan ke keranjang (Pinia)
  router.push('/cart')            // Arahkan ke halaman keranjang
}

function chatViaWhatsApp() {
  // Nomor WA penjual (pakai format internasional tanpa tanda +)
  const phoneNumber = '6281326553304'

  // Pastikan data produk sudah ada
  if (!product.value) return

  // Pesan otomatis yang muncul di WhatsApp
  const message = `
Halo, saya tertarik dengan produk berikut:

🛠 *${product.value.name}*
Brand: ${product.value.brand}
Type: ${product.value.type}
Grade: ${product.value.grade}
Harga: Rp ${formatPrice(product.value.price)}
Stok: ${product.value.stock > 0 ? product.value.stock : 'Habis'}
${product.value.image ? `Gambar: ${window.location.origin}/storage/${product.value.image}` : ''}

Mohon info ketersediaannya ya, terima kasih!
  `.trim()

  // Encode pesan agar bisa dibaca di URL
  const encodedMessage = encodeURIComponent(message)
  const waUrl = `https://wa.me/${phoneNumber}?text=${encodedMessage}`

  // Buka WhatsApp di tab baru
  window.open(waUrl, '_blank')
}

async function fetchProductDetail() {
  try {
    const res = await axios.get(`/api/spareparts/${route.params.id}`)
    
    const data = res.data;
    
    // PERBAIKAN UTAMA: Konversi STRING yang dihasilkan number_format() ke FLOAT.
    // Ini harus dilakukan jika Anda mempertahankan number_format() di Model Laravel.
    if (data.average_rating && typeof data.average_rating === 'string') {
        data.average_rating = parseFloat(data.average_rating);
    } else if (!data.average_rating) {
        // Handle jika rating adalah null/undefined (produk baru)
        data.average_rating = 0.0;
    }
    // Pastikan reviews_count adalah integer (jika diperlukan)
    data.reviews_count = data.reviews_count ? parseInt(data.reviews_count) : 0;


    product.value = data;

  } catch (err) {
    console.error(err)
    error.value = 'Gagal memuat detail produk.'
  } finally {
    loading.value = false
  }
}

const formatPrice = (price) => {
  return price?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}

onMounted(fetchProductDetail)
</script>

<style scoped>
  .tracking-tighter {
  letter-spacing: -0.2em; 
}
</style>
