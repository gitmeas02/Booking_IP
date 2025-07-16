<template>
  <HeadingBar />
  <RouterView />
</template>

<script setup>
import HeadingBar from '@/components/LandingPage/HeadingBar.vue';
import { lazyLoadImages, prefetchResource } from '@/utils/performance.js';
import { onMounted } from 'vue';
import { RouterView } from 'vue-router';

// Lazy load images on mount
onMounted(() => {
  // Initialize lazy loading for all images with data-src attribute
  lazyLoadImages();
  
  // Prefetch critical resources
  prefetchResource('/hotels', 'fetch');
  prefetchResource('/rooms', 'fetch');
});
</script>

<style>
/* Global performance optimizations */
* {
  /* Enable hardware acceleration for smoother animations */
  transform: translateZ(0);
  backface-visibility: hidden;
}

/* Optimize font loading */
@font-face {
  font-family: 'Inter';
  font-display: swap;
  src: url('/fonts/inter.woff2') format('woff2');
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Reduce motion for accessibility */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Optimize image loading - use HTML attributes loading="lazy" and decoding="async" */

/* Optimize lazy loading placeholder */
.lazy {
  background-color: #f0f0f0;
  background-image: 
    linear-gradient(
      45deg,
      transparent 35%,
      rgba(255,255,255,0.5) 50%,
      transparent 65%
    );
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}
</style>
