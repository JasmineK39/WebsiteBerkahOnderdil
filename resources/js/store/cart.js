import { defineStore } from 'pinia'
import axios from 'axios'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    paymentMethod: 'Transfer',
  }),

   actions: {
    addToCart(sparepart) {
      const existing = this.items.find(i => i.id === sparepart.id)
      if (existing) {
        existing.qty++
      } else {
        this.items.push({ ...sparepart, qty: 1 })
      }
    },

      increaseQty(itemId) {
    const item = this.items.find(i => i.id === itemId)
    if (item) item.qty++
  },

  decreaseQty(itemId) {
    const item = this.items.find(i => i.id === itemId)
    if (!item) return
    if (item.qty > 1) {
      item.qty--
    } else {
      // hapus item jika qty = 0
      this.items = this.items.filter(i => i.id !== itemId)
    }
  },

    removeFromCart(id) {
      this.items = this.items.filter(i => i.id !== id)
    },

    clearCart() {
      this.items = []
    },
    
    setPaymentMethod(method) {
      this.paymentMethod = method
    }
  },

  persist: true // simpan ke localStorage supaya tidak hilang saat refresh
})
