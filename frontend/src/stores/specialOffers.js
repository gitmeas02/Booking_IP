import { defineStore } from "pinia";

export const useSpecialOffersStore = defineStore("specialOffers", {
  state: () => ({
    promotions: [],
    coupons: [],
    loading: false,
    error: null,
  }),

  actions: {
    async fetchPromotions() {
      this.loading = true;
      this.error = null;

      try {
        // Simulate API call - replace with actual API endpoint
        await new Promise((resolve) => setTimeout(resolve, 500));

        this.promotions = [
          {
            id: 1,
            title: "Summer Special",
            description: "Get 30% off on all bookings",
            image:
              "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
            discount: 30,
            validUntil: "2025-08-31",
            code: "SUMMER30",
            isActive: true,
          },
          {
            id: 2,
            title: "Weekend Getaway",
            description: "Special rates for weekend stays",
            image:
              "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
            discount: 25,
            validUntil: "2025-12-31",
            code: "WEEKEND25",
            isActive: true,
          },
          {
            id: 3,
            title: "Early Bird Special",
            description: "Book 30 days in advance and save",
            image:
              "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
            discount: 20,
            validUntil: "2025-07-31",
            code: "EARLY20",
            isActive: true,
          },
          {
            id: 4,
            title: "Family Package",
            description: "Special rates for family bookings",
            image:
              "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
            discount: 35,
            validUntil: "2025-09-30",
            code: "FAMILY35",
            isActive: true,
          },
          {
            id: 5,
            title: "Extended Stay",
            description: "Stay 7+ nights and get extra discount",
            image:
              "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
            discount: 40,
            validUntil: "2025-10-31",
            code: "STAY40",
            isActive: true,
          },
          {
            id: 6,
            title: "Last Minute Deal",
            description: "Book within 24 hours for instant savings",
            image:
              "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
            discount: 15,
            validUntil: "2025-12-31",
            code: "LASTMIN15",
            isActive: true,
          },
        ];
      } catch (error) {
        this.error = "Failed to fetch promotions";
        console.error("Error fetching promotions:", error);
      } finally {
        this.loading = false;
      }
    },

    async fetchCoupons() {
      this.loading = true;
      this.error = null;

      try {
        // Simulate API call - replace with actual API endpoint
        await new Promise((resolve) => setTimeout(resolve, 300));

        this.coupons = [
          {
            id: 1,
            code: "WELCOME10",
            discount: 10,
            description: "Welcome bonus for new users",
            isUsed: false,
            expiryDate: "2025-12-31",
          },
          {
            id: 2,
            code: "LOYALTY20",
            discount: 20,
            description: "Loyalty program reward",
            isUsed: false,
            expiryDate: "2025-11-30",
          },
        ];
      } catch (error) {
        this.error = "Failed to fetch coupons";
        console.error("Error fetching coupons:", error);
      } finally {
        this.loading = false;
      }
    },

    async applyCoupon(code) {
      const coupon = this.coupons.find((c) => c.code === code && !c.isUsed);
      if (coupon) {
        coupon.isUsed = true;
        return coupon;
      }
      throw new Error("Invalid or expired coupon code");
    },

    getPromotionById(id) {
      return this.promotions.find((promo) => promo.id === id);
    },

    getActivePromotions() {
      return this.promotions.filter((promo) => promo.isActive);
    },
  },

  getters: {
    activePromotions: (state) => {
      return state.promotions.filter((promo) => promo.isActive);
    },

    availableCoupons: (state) => {
      return state.coupons.filter((coupon) => !coupon.isUsed);
    },

    totalPromotions: (state) => state.promotions.length,

    bestDeal: (state) => {
      return state.promotions.reduce(
        (best, current) =>
          current.discount > (best?.discount || 0) ? current : best,
        null
      );
    },
  },
});
