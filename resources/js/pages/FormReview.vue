<template>
  <div class="max-w-lg mx-auto mt-10 bg-white shadow-lg rounded-lg p-6 border border-pink-200">
    <h2 class="text-center text-2xl font-extrabold text-red-700 mb-6 uppercase tracking-widest">
      Review Customer
    </h2>

    <form @submit.prevent="submitForm" class="space-y-4">
    <div>
        <label class="block font-medium mb-1">Email</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="nama@email.com"
          class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-pink-300"
        />
    </div>

    <div>
        <label class="block font-medium mb-1">Jenis Mobil</label>
        <input
          v-model="form.jenismobil"
          type="text"
          class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-red-400"
        />
    </div>

    <div>
        <label class="block font-medium mb-1">Nama sparepart</label>
        <textarea
          v-model="form.namasparepart"
          rows="3"
          class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-red-400"
        ></textarea>
    </div>

    <div>
        <label class="block font-medium mb-1">Grade</label>
        <select
            v-model="form.grade"
            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-red-400"
        >
            <option disabled value="">Pilih Grade</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
        </select>
    </div>

    <div>
        <label class="block font-medium mb-1">Kategori sparepart</label>
        <textarea
          v-model="form.kategorisparepart"
          rows="3"
          class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-red-400"
        ></textarea>

    </div>

          <div>
        <label class="block font-medium mb-1">Rating</label>
        <select
          v-model="form.rating"
          class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-red-400"
        >
          <option disabled value="">Rate 1 - 5</option>
          <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
        </select>
        <p class="text-center text-sm text-gray-500 mt-1">
          Masukkan angka antara 1 dan 5.
        </p>
      </div>

    <div class="text-center">
        <button
          type="submit"
          class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-pink-200 transition"
        >
          Submit
        </button>
    </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  email: '',
  jenismobil: '',
  namasparepart: '',
  grade: '',
  kategorisparepart: '',
})

const submitForm = () => {
  alert(`Terima kasih atas review Anda!\nRating: ${form.value.rating}`)
}
</script>
