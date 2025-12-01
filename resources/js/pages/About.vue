<template>
    <section class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="w-full bg-gradient-to-r from-[#660708] via-[#A4161A] to-[#BA181B] py-20">
            <div class="max-w-7xl mx-auto px-6 text-center text-white">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-wide">
                    Tentang Berkah Onderdil
                </h1>
                <p class="mt-4 text-lg md:text-xl opacity-90">
                    Solusi terpercaya untuk kebutuhan sparepart dan aksesoris kendaraan Anda.
                </p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-6xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-3xl font-bold text-[#0B090A] mb-4">
                    Siapa Kami?
                </h2>
                <p class="text-gray-700 leading-relaxed text-justify">
                    <strong>Berkah Onderdil</strong> adalah toko sparepart mobil bekas bongkaran
                    (belehan) yang berkomitmen menyediakan komponen berkualitas dengan harga yang
                    jauh lebih terjangkau. Semua sparepart kami berasal dari proses pembongkaran
                    mobil secara langsung, sehingga kualitas dan keaslian barang lebih terjamin
                    dibandingkan produk bekas yang tidak jelas asal-usulnya.
                </p>

                <p class="mt-4 text-gray-700 leading-relaxed text-justify">
                    Setiap sparepart yang kami jual melewati proses pengecekan kondisi secara
                    teliti, sehingga pelanggan mendapatkan komponen yang masih layak pakai,
                    berfungsi normal, dan aman dipasang pada kendaraan. Dengan pengalaman dalam
                    dunia otomotif serta tim yang sudah terbiasa menangani pembongkaran dan
                    penilaian kualitas, kami siap memberikan rekomendasi yang akurat,
                    harga bersahabat, dan pelayanan yang cepat serta ramah.
                </p>

            </div>

            <div>
                <img src="https://lh3.googleusercontent.com/p/AF1QipPw6LHyxVvwzdCut0Eo2a7Mq3JPh0JOWsJksp_2=w284-h284-n-k-no-nu"
                    class="rounded-2xl shadow-xl w-full object-cover" alt="Berkah Onderdil" />
            </div>
        </div>

        <!-- Mission / Why Choose Us -->
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold text-[#0B090A]">
                    Mengapa Memilih Kami?
                </h2>
                <p class="text-gray-600 mt-3">
                    Kami berkomitmen memberikan pengalaman terbaik bagi setiap pelanggan.
                </p>

                <div class="grid md:grid-cols-3 gap-10 mt-12">
                    <div class="bg-gray-100 p-8 rounded-xl shadow-sm border hover:shadow-xl transition">
                        <h3 class="text-xl font-bold text-[#A4161A] mb-3">Produk Berkualitas</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Semua produk kami dipilih melalui proses seleksi ketat untuk
                            memastikan kualitas dan ketahanannya.
                        </p>
                    </div>

                    <div class="bg-gray-100 p-8 rounded-xl shadow-sm border hover:shadow-xl transition">
                        <h3 class="text-xl font-bold text-[#A4161A] mb-3">Harga Bersahabat</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Kami menyediakan harga kompetitif untuk memenuhi kebutuhan Anda
                            tanpa mengorbankan kualitas.
                        </p>
                    </div>

                    <div class="bg-gray-100 p-8 rounded-xl shadow-sm border hover:shadow-xl transition">
                        <h3 class="text-xl font-bold text-[#A4161A] mb-3">Pelayanan Profesional</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Tim kami siap membantu dengan dukungan teknis dan rekomendasi terbaik.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location / Maps Section -->
        <div id="lokasi-toko" class="bg-gray-100 py-16">
            <div class="max-w-6xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-[#0B090A] text-center">
                    Lokasi Berkah Onderdil
                </h2>
                <p class="text-gray-600 mt-3 text-center">
                    Kunjungi toko kami untuk melihat langsung sparepart bongkaran berkualitas.
                </p>

                <div class="mt-10 rounded-2xl overflow-hidden shadow-xl border">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15824.817221106697!2d112.4533165!3d-7.8741135!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd62d6bc7167d77%3A0xe71383a7ef5457a2!2sBerkah%20Onderdil!5e0!3m2!1sen!2sid!4v1708345123456!5m2!1sen!2sid"
                        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-gradient-to-r from-[#161A1D] to-[#0B090A] py-16 mt-10">
            <div class="max-w-4xl mx-auto px-6 text-center text-white">
                <h2 class="text-3xl md:text-4xl font-extrabold">
                    Siap Menemukan Sparepart Terbaik?
                </h2>
                <p class="mt-4 opacity-90">
                    Jelajahi katalog lengkap kami dan dapatkan komponen terbaik untuk kendaraan Anda.
                </p>

                <router-link to="/catalog"
                    class="inline-block mt-8 bg-[#BA181B] hover:bg-[#E5383B] transition px-8 py-3 rounded-lg font-semibold text-white shadow-lg">
                    Lihat Katalog
                </router-link>
            </div>
        </div>
    </section>
</template>

<script setup>
</script>
