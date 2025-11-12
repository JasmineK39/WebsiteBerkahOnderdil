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
        this.items = res.data.items || []
      } catch {
        console.warn('Gagal fetch dari backend → gunakan local cart')
      }
    },

    async addToCart(sparepart) {
      try {
        const res = await axios.post('/api/cart', {
          sparepart_id: sparepart.id,
          quantity: 1
        })
        await this.fetchCart()
      } catch {
        // fallback local
        const existing = this.items.find(i => i.id === sparepart.id)
        if (existing) existing.qty++
        else this.items.push({ ...sparepart, qty: 1 })
      }
    },

    increaseQty(id) {
      const item = this.items.find(i => i.id === id)
      if (item) item.qty++
    },

    decreaseQty(id) {
      const item = this.items.find(i => i.id === id)
      if (!item) return
      if (item.qty > 1) item.qty--
      else this.items = this.items.filter(i => i.id !== id)
    },

    setPaymentMethod(method) {
      this.paymentMethod = method
    }
  },

  persist: true,
})
