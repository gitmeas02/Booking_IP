<template>
  <div class="pl-14 pr-14 pt-8 w-screen min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="flex justify-between items-center pb-6">
      <div>
        <h2 class="font-semibold text-3xl text-gray-800">
          UNLIMITED PROMOTIONS
        </h2>
        <p class="text-gray-600 mt-1">
          Discover amazing deals and save on your next stay
        </p>
      </div>
      <div class="flex flex-col items-end">
        <div v-if="loading" class="text-sm text-gray-500">
          Loading promotions...
        </div>
        <div v-if="error" class="text-sm text-red-500">{{ error }}</div>
        <div
          v-if="!loading && activePromotions.length > 0"
          class="text-sm text-green-600"
        >
          {{ activePromotions.length }} active deals available
        </div>
      </div>
    </div>

    <!-- Main Promotions Section -->
    <div class="flex flex-col lg:flex-row gap-8 mb-12 h-[600px]">
      <div class="flex-shrink-0">
        <OurSpecial />
      </div>

      <div class="flex-1 h-full">
        <!-- Loading State -->
        <div
          v-if="loading"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 h-full content-start"
        >
          <div
            v-for="n in 6"
            :key="n"
            class="w-full h-[17.5rem] bg-gray-200 animate-pulse rounded-lg"
          ></div>
        </div>

        <!-- Promotions Grid -->
        <div v-else class="space-y-6 h-full flex flex-col">
          <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-1 content-start overflow-auto"
          >
            <LandingCard
              v-for="promotion in displayedPromotions"
              :key="promotion.id"
              :image="promotion.image"
              :label="promotion.title"
              :discount="promotion.discount"
              :code="promotion.code"
              :description="promotion.description"
              :valid-until="promotion.validUntil"
              class="w-full"
              @click="handlePromotionClick(promotion)"
            />
          </div>

          <!-- See More Button -->
          <div
            v-if="activePromotions.length > displayLimit"
            class="text-center flex-shrink-0 pt-4"
          >
            <button
              @click="toggleShowAll"
              class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200 shadow-md hover:shadow-lg"
            >
              {{
                showAll
                  ? "Show Less"
                  : `See More (${remainingCount} more deals)`
              }}
            </button>
          </div>

          <!-- No promotions message -->
          <div
            v-if="activePromotions.length === 0"
            class="text-center py-12 flex-1 flex flex-col justify-center"
          >
            <div class="text-gray-400 text-6xl mb-4">🎯</div>
            <h3 class="text-xl font-medium text-gray-600 mb-2">
              No active promotions
            </h3>
            <p class="text-gray-500">Check back later for exciting deals!</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Best Deal Highlight -->
    <div
      v-if="bestDeal && !loading"
      class="mb-12 p-6 bg-gradient-to-r from-amber-100 to-orange-100 rounded-lg border-l-4 border-amber-500 shadow-md"
    >
      <h3 class="text-xl font-semibold text-gray-800 mb-3">
        🎉 Best Deal Available!
      </h3>
      <div class="flex items-center space-x-6">
        <img
          :src="bestDeal.image"
          alt="Best Deal"
          class="w-20 h-20 object-cover rounded-lg shadow-sm"
        />
        <div class="flex-1">
          <p class="font-medium text-gray-800 text-lg">{{ bestDeal.title }}</p>
          <p class="text-sm text-gray-600 mb-2">{{ bestDeal.description }}</p>
          <div class="flex items-center space-x-4">
            <span
              class="bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium"
            >
              Code: {{ bestDeal.code }}
            </span>
            <span class="text-amber-700 font-bold text-lg"
              >Save {{ bestDeal.discount }}%</span
            >
          </div>
        </div>
        <button
          @click="handlePromotionClick(bestDeal)"
          class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200"
        >
          Apply Now
        </button>
      </div>
    </div>

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
      <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <div class="text-3xl font-bold text-blue-600 mb-2">
          {{ totalPromotions }}
        </div>
        <div class="text-gray-600">Active Promotions</div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <div class="text-3xl font-bold text-green-600 mb-2">
          {{ maxDiscount }}%
        </div>
        <div class="text-gray-600">Maximum Savings</div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <div class="text-3xl font-bold text-purple-600 mb-2">
          {{ availableCoupons.length }}
        </div>
        <div class="text-gray-600">Available Coupons</div>
      </div>
    </div>

    <!-- How It Works Section -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-12">
      <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
        How Our Promotions Work
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
          <div
            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"
          >
            <span class="text-2xl">🔍</span>
          </div>
          <h4 class="font-semibold text-gray-800 mb-2">Browse Deals</h4>
          <p class="text-gray-600 text-sm">
            Explore our collection of exclusive promotions and find the perfect
            deal for your stay.
          </p>
        </div>
        <div class="text-center">
          <div
            class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
          >
            <span class="text-2xl">✅</span>
          </div>
          <h4 class="font-semibold text-gray-800 mb-2">Apply Code</h4>
          <p class="text-gray-600 text-sm">
            Click on any promotion to automatically apply the discount code to
            your booking.
          </p>
        </div>
        <div class="text-center">
          <div
            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"
          >
            <span class="text-2xl">💰</span>
          </div>
          <h4 class="font-semibold text-gray-800 mb-2">Save Money</h4>
          <p class="text-gray-600 text-sm">
            Enjoy instant savings on your hotel booking with our exclusive
            promotional rates.
          </p>
        </div>
      </div>
    </div>

    <!-- Limited Time Offers -->
    <div
      class="bg-gradient-to-r from-red-500 to-pink-500 rounded-lg shadow-lg p-8 mb-12 text-white"
    >
      <div class="text-center">
        <h3 class="text-2xl font-bold mb-2">⏰ Limited Time Offers</h3>
        <p class="text-red-100 mb-4">
          Don't miss out on these time-sensitive deals!
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            v-for="urgentDeal in urgentDeals"
            :key="urgentDeal.id"
            class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center"
          >
            <div class="font-bold text-lg">{{ urgentDeal.title }}</div>
            <div class="text-sm opacity-90">{{ urgentDeal.timeLeft }}</div>
            <div class="text-2xl font-bold mt-2">
              {{ urgentDeal.discount }}% OFF
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Newsletter Subscription -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-8 mb-12 text-white">
      <div class="text-center">
        <h3 class="text-2xl font-bold mb-2">📧 Stay Updated</h3>
        <p class="text-gray-300 mb-6">
          Subscribe to our newsletter and never miss exclusive deals and
          promotions!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
          <input
            v-model="emailInput"
            type="email"
            placeholder="Enter your email address"
            class="flex-1 px-4 py-3 rounded-lg text-white-800 outline-2 outline-offset-2 outline-white focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <button
            @click="subscribeToNewsletter"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-medium transition-colors duration-200"
          >
            Subscribe
          </button>
        </div>
      </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-lg shadow-md p-8">
      <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
        Frequently Asked Questions
      </h3>
      <div class="space-y-4">
        <div
          v-for="(faq, index) in faqs"
          :key="index"
          class="border-b border-gray-200 pb-4"
        >
          <button
            @click="toggleFaq(index)"
            class="flex justify-between items-center w-full text-left font-medium text-gray-800 hover:text-blue-600 transition-colors"
          >
            <span>{{ faq.question }}</span>
            <span
              class="transform transition-transform"
              :class="{ 'rotate-180': faq.isOpen }"
              >▼</span
            >
          </button>
          <div v-if="faq.isOpen" class="mt-3 text-gray-600 text-sm">
            {{ faq.answer }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}
</style>

<script setup>
import { onMounted, computed, ref } from "vue";
import { useRouter } from "vue-router";
import { useSpecialOffersStore } from "@/stores/specialOffers";
import { useBookingStore } from "@/stores/booking";
import OurSpecial from "./OurSpecial.vue";
import LandingCard from "../LandingPage/LandingCard.vue";

const router = useRouter();
const specialOffersStore = useSpecialOffersStore();
const bookingStore = useBookingStore();

const emailInput = ref("");

// Computed properties
const activePromotions = computed(() => specialOffersStore.activePromotions);
const loading = computed(() => specialOffersStore.loading);
const error = computed(() => specialOffersStore.error);
const bestDeal = computed(() => specialOffersStore.bestDeal);
const hasActiveBooking = computed(() => bookingStore.hasActiveBooking);
const totalPromotions = computed(() => specialOffersStore.totalPromotions);
const availableCoupons = computed(() => specialOffersStore.availableCoupons);

// Additional computed properties
const maxDiscount = computed(() => {
  return Math.max(...activePromotions.value.map((p) => p.discount), 0);
});

const urgentDeals = computed(() => {
  return [
    {
      id: "urgent1",
      title: "Flash Sale",
      discount: 40,
      timeLeft: "2 hours left",
    },
    {
      id: "urgent2",
      title: "Weekend Rush",
      discount: 25,
      timeLeft: "1 day left",
    },
  ];
});

const faqs = ref([
  {
    question: "How do I apply a promotion code?",
    answer:
      "Simply click on any promotion card and the discount will be automatically applied to your booking. You can also enter the code manually during checkout.",
    isOpen: false,
  },
  {
    question: "Can I combine multiple promotions?",
    answer:
      "Generally, only one promotion code can be used per booking. The system will automatically apply the best available discount for you.",
    isOpen: false,
  },
  {
    question: "When do promotions expire?",
    answer:
      "Each promotion has its own expiry date displayed on the card. Make sure to use the code before the expiration date to enjoy the discount.",
    isOpen: false,
  },
  {
    question: "Are there any restrictions on promotional codes?",
    answer:
      "Some promotions may have restrictions such as minimum stay requirements, specific dates, or hotel categories. Check the terms and conditions for each offer.",
    isOpen: false,
  },
]);

const showAll = ref(false);
const displayLimit = 6; // Show 6 cards initially (2 rows of 3)

// Methods
const handlePromotionClick = (promotion) => {
  console.log("Promotion clicked:", promotion.title);

  // If there's an active booking, apply the promotion
  if (hasActiveBooking.value) {
    applyPromotion(promotion);
  } else {
    // Navigate to booking page with promotion pre-applied
    router.push({
      name: "ListRoomPage",
      query: {
        promo: promotion.code,
        discount: promotion.discount,
      },
    });
  }
};

const applyPromotion = (promotion) => {
  try {
    bookingStore.applyDiscount(promotion.discount, promotion.code);

    // Show success message with better UX
    const message = `🎉 Promotion "${promotion.title}" applied successfully!\n💰 You're saving ${promotion.discount}% on your booking!`;
    alert(message);

    // Optionally navigate to checkout
    // router.push({ name: 'CheckoutPage' })
  } catch (error) {
    console.error("Error applying promotion:", error);
    alert(
      "Sorry, there was an error applying this promotion. Please try again."
    );
  }
};

// Additional computed properties
const displayedPromotions = computed(() => {
  if (showAll.value) {
    return activePromotions.value;
  }
  return activePromotions.value.slice(0, displayLimit);
});

const remainingCount = computed(() => {
  return Math.max(0, activePromotions.value.length - displayLimit);
});

// Additional methods
const subscribeToNewsletter = () => {
  if (emailInput.value && emailInput.value.includes("@")) {
    alert(
      "Thank you for subscribing! You'll receive exclusive deals and promotions."
    );
    emailInput.value = "";
  } else {
    alert("Please enter a valid email address.");
  }
};

const toggleFaq = (index) => {
  faqs.value[index].isOpen = !faqs.value[index].isOpen;
};

const toggleShowAll = () => {
  showAll.value = !showAll.value;
};

// Lifecycle
onMounted(async () => {
  await specialOffersStore.fetchPromotions();
});
</script>
