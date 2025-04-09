// store.js
import { defineStore } from 'pinia'

export const useRoomStore = defineStore('room', {
  state: () => ({
    roomPrice: 0,
    selectedRoomId: '',
    selectedDate: '',
    rooms: [] // You can move your rooms here if centralizing
  }),
  actions: {
    setRoomPrice(price) {
      this.roomPrice = price
    },
    setSelectedRoom(id) {
      this.selectedRoomId = id
    },
    setSelectedDate(date) {
      this.selectedDate = date
    },
    setRooms(rooms) {
      this.rooms = rooms
    },
    updateRoomPrice() {
      if (!this.selectedRoomId || !this.selectedDate) return

      const room = this.rooms.find(r => r.id === this.selectedRoomId)
      if (!room) return

      const price = Number(this.roomPrice)
      if (price > 0) {
        room.dynamicPricing = room.dynamicPricing || {}
        room.dynamicPricing[this.selectedDate] = price
      } else {
        if (room.dynamicPricing && room.dynamicPricing[this.selectedDate]) {
          delete room.dynamicPricing[this.selectedDate]
        }
      }
    }
  }
})
