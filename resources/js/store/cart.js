import { defineStore } from 'pinia'
import axios from 'axios'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    paymentMethod: 'Transfer',
  }),

  actions: {
    async fetchCart() {
      try {
        const res = await axios.get('/api/cart')

        // Menyeragamkan properti kuantitas menjadi 'quantity'
        this.items = (res.data.items || []).map(i => ({
          ...i,
          // Menggunakan 'quantity' sebagai nama properti tunggal
          quantity: i.quantity ?? 1
        }))

      } catch (error) {
        console.warn('Gagal fetch dari backend → gunakan local cart', error)
        // Opsional: Jika gagal fetch dari backend, pastikan items tetap array kosong
        if (error.response && error.response.status === 401) {
            this.items = []; 
        }
      }
    },

    async addToCart(sparepart) {
      try {
        await axios.post('/api/cart', {
          sparepart_id: sparepart.id,
          quantity: 1
        })
        await this.fetchCart()
      } catch (error) {
        // Fallback local: menggunakan 'quantity'
        const existing = this.items.find(i => i.id === sparepart.id)
        if (existing) existing.quantity++
        else this.items.push({ ...sparepart, quantity: 1 }) // Gunakan 'quantity'
      }
    },

    increaseQty(id) {
      // Mengubah dari 'qty' menjadi 'quantity'
      const item = this.items.find(i => i.id === id)
      if (item) item.quantity++
    },

    decreaseQty(id) {
      // Mengubah dari 'qty' menjadi 'quantity'
      const item = this.items.find(i => i.id === id)
      if (!item) return
      if (item.quantity > 1) item.quantity--
      else this.items = this.items.filter(i => i.id !== id)
    },

    setPaymentMethod(method) {
      this.paymentMethod = method
    }
  },

  persist: true,
})