import { defineStore } from "pinia";

export const useBookingStore = defineStore("booking", {
  state: () => ({
    currentBooking: {
      hotelId: null,
      roomId: null,
      checkIn: null,
      checkOut: null,
      guests: {
        adults: 1,
        children: 0,
      },
      totalPrice: 0,
      basePrice: 0,
      appliedDiscount: {
        code: null,
        percentage: 0,
        amount: 0,
      },
      guestInfo: {
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
      },
    },
    bookingHistory: [],
    loading: false,
    error: null,
  }),

  actions: {
    setBookingDetails({
      hotelId,
      roomId,
      checkIn,
      checkOut,
      guests,
      basePrice,
    }) {
      this.currentBooking.hotelId = hotelId;
      this.currentBooking.roomId = roomId;
      this.currentBooking.checkIn = checkIn;
      this.currentBooking.checkOut = checkOut;
      this.currentBooking.guests = { ...guests };
      this.currentBooking.basePrice = basePrice;
      this.calculateTotal();
    },

    applyDiscount(discountPercentage, code) {
      this.currentBooking.appliedDiscount.code = code;
      this.currentBooking.appliedDiscount.percentage = discountPercentage;
      this.currentBooking.appliedDiscount.amount =
        (this.currentBooking.basePrice * discountPercentage) / 100;
      this.calculateTotal();
    },

    removeDiscount() {
      this.currentBooking.appliedDiscount = {
        code: null,
        percentage: 0,
        amount: 0,
      };
      this.calculateTotal();
    },

    calculateTotal() {
      this.currentBooking.totalPrice =
        this.currentBooking.basePrice -
        this.currentBooking.appliedDiscount.amount;
    },

    setGuestInfo(guestInfo) {
      this.currentBooking.guestInfo = { ...guestInfo };
    },

    async confirmBooking() {
      this.loading = true;
      this.error = null;

      try {
        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 1000));

        const booking = {
          id: Date.now(),
          ...this.currentBooking,
          status: "confirmed",
          bookingDate: new Date().toISOString(),
          confirmationNumber:
            "BK" + Math.random().toString(36).substr(2, 9).toUpperCase(),
        };

        this.bookingHistory.push(booking);
        this.resetCurrentBooking();

        return booking;
      } catch (error) {
        this.error = "Failed to confirm booking";
        throw error;
      } finally {
        this.loading = false;
      }
    },

    resetCurrentBooking() {
      this.currentBooking = {
        hotelId: null,
        roomId: null,
        checkIn: null,
        checkOut: null,
        guests: {
          adults: 1,
          children: 0,
        },
        totalPrice: 0,
        basePrice: 0,
        appliedDiscount: {
          code: null,
          percentage: 0,
          amount: 0,
        },
        guestInfo: {
          firstName: "",
          lastName: "",
          email: "",
          phone: "",
        },
      };
    },

    async fetchBookingHistory() {
      this.loading = true;
      try {
        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 500));
        // In real app, this would fetch from API
      } catch (error) {
        this.error = "Failed to fetch booking history";
      } finally {
        this.loading = false;
      }
    },
  },

  getters: {
    hasActiveBooking: (state) => {
      return state.currentBooking.hotelId !== null;
    },

    discountSavings: (state) => {
      return state.currentBooking.appliedDiscount.amount;
    },

    bookingDuration: (state) => {
      if (!state.currentBooking.checkIn || !state.currentBooking.checkOut)
        return 0;
      const checkIn = new Date(state.currentBooking.checkIn);
      const checkOut = new Date(state.currentBooking.checkOut);
      const diffTime = Math.abs(checkOut - checkIn);
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    },

    totalGuests: (state) => {
      return (
        state.currentBooking.guests.adults +
        state.currentBooking.guests.children
      );
    },
  },
});
