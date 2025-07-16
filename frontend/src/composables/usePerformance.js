/**
 * Vue 3 Composition API composable for performance monitoring and optimization
 */

import { debounce, measurePerformance, throttle, trackScrollPosition } from '@/utils/performance.js';
import { computed, onMounted, onUnmounted, ref } from 'vue';

/**
 * Composable for debounced and throttled functions
 * @param {Function} fn - The function to optimize
 * @param {number} delay - Delay in milliseconds
 * @param {string} type - 'debounce' or 'throttle'
 * @returns {Function} - The optimized function
 */
export function useOptimizedFunction(fn, delay = 300, type = 'debounce') {
  const optimizedFn = type === 'debounce' ? debounce(fn, delay) : throttle(fn, delay);
  return optimizedFn;
}

/**
 * Composable for performance monitoring
 * @param {string} componentName - Name of the component
 * @returns {Object} - Performance tracking functions
 */
export function usePerformanceMonitoring(componentName) {
  const startTime = ref(0);
  const loadTime = ref(0);
  const isLoading = ref(false);

  const startMeasure = (measureName = 'default') => {
    isLoading.value = true;
    startTime.value = performance.now();
    if (import.meta.env.DEV) {
      console.log(`[${componentName}] Starting ${measureName}...`);
    }
  };

  const endMeasure = (measureName = 'default') => {
    const endTime = performance.now();
    loadTime.value = endTime - startTime.value;
    isLoading.value = false;
    
    if (import.meta.env.DEV) {
      console.log(`[${componentName}] ${measureName} completed in ${loadTime.value.toFixed(2)}ms`);
    }
  };

  const measureAsync = async (fn, measureName = 'async-operation') => {
    return await measurePerformance(`${componentName}-${measureName}`, fn);
  };

  return {
    startMeasure,
    endMeasure,
    measureAsync,
    loadTime,
    isLoading
  };
}

/**
 * Composable for optimized scroll handling
 * @param {Function} onScroll - Scroll handler function
 * @param {number} throttleMs - Throttle delay in milliseconds
 * @returns {Object} - Scroll tracking utilities
 */
export function useOptimizedScroll(onScroll, throttleMs = 16) {
  const scrollY = ref(0);
  const scrollDirection = ref('down');
  const isScrolling = ref(false);
  let scrollTimeout;
  let lastScrollY = 0;

  const handleScroll = () => {
    const currentScrollY = window.scrollY;
    scrollY.value = currentScrollY;
    scrollDirection.value = currentScrollY > lastScrollY ? 'down' : 'up';
    lastScrollY = currentScrollY;
    
    isScrolling.value = true;
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
      isScrolling.value = false;
    }, 150);

    if (onScroll) {
      onScroll(currentScrollY, scrollDirection.value);
    }
  };

  const throttledScroll = throttle(handleScroll, throttleMs);
  let cleanup;

  onMounted(() => {
    cleanup = trackScrollPosition(throttledScroll, throttleMs);
  });

  onUnmounted(() => {
    if (cleanup) cleanup();
    clearTimeout(scrollTimeout);
  });

  return {
    scrollY,
    scrollDirection,
    isScrolling
  };
}

/**
 * Composable for optimized click handling
 * @param {Function} onClick - Click handler function
 * @param {number} debounceMs - Debounce delay in milliseconds
 * @returns {Function} - Optimized click handler
 */
export function useOptimizedClick(onClick, debounceMs = 300) {
  const isClicking = ref(false);
  
  const handleClick = async (...args) => {
    if (isClicking.value) return;
    
    isClicking.value = true;
    
    try {
      await onClick(...args);
    } finally {
      // Reset click state after debounce period
      setTimeout(() => {
        isClicking.value = false;
      }, debounceMs);
    }
  };

  return {
    handleClick,
    isClicking
  };
}

/**
 * Composable for lazy loading with intersection observer
 * @param {Function} onIntersect - Callback when element intersects
 * @param {Object} options - Intersection observer options
 * @returns {Object} - Lazy loading utilities
 */
export function useLazyLoading(onIntersect, options = {}) {
  const target = ref(null);
  const isIntersecting = ref(false);
  const hasIntersected = ref(false);
  let observer;

  const defaultOptions = {
    rootMargin: '50px',
    threshold: 0.1,
    ...options
  };

  onMounted(() => {
    if (target.value) {
      observer = new IntersectionObserver(
        ([entry]) => {
          isIntersecting.value = entry.isIntersecting;
          
          if (entry.isIntersecting && !hasIntersected.value) {
            hasIntersected.value = true;
            onIntersect();
          }
        },
        defaultOptions
      );

      observer.observe(target.value);
    }
  });

  onUnmounted(() => {
    if (observer) {
      observer.disconnect();
    }
  });

  return {
    target,
    isIntersecting,
    hasIntersected
  };
}

/**
 * Composable for optimized image loading
 * @param {string} imageSrc - Image source URL
 * @param {string} placeholderSrc - Placeholder image source
 * @returns {Object} - Image loading utilities
 */
export function useOptimizedImage(imageSrc, placeholderSrc = '') {
  const imageRef = ref(null);
  const isLoaded = ref(false);
  const isError = ref(false);
  const currentSrc = ref(placeholderSrc);

  const loadImage = () => {
    if (!imageSrc) return;

    const img = new Image();
    
    img.onload = () => {
      isLoaded.value = true;
      currentSrc.value = imageSrc;
      
      // Smooth transition
      if (imageRef.value) {
        imageRef.value.style.opacity = '1';
      }
    };

    img.onerror = () => {
      isError.value = true;
      currentSrc.value = placeholderSrc;
    };

    img.src = imageSrc;
  };

  const { target, hasIntersected } = useLazyLoading(loadImage);

  onMounted(() => {
    if (imageRef.value) {
      imageRef.value.style.opacity = '0';
      imageRef.value.style.transition = 'opacity 0.3s ease';
    }
  });

  return {
    imageRef,
    target,
    isLoaded,
    isError,
    currentSrc,
    hasIntersected
  };
}

/**
 * Composable for API request optimization
 * @param {Function} apiCall - API call function
 * @param {Object} options - Configuration options
 * @returns {Object} - API utilities
 */
export function useOptimizedAPI(apiCall, options = {}) {
  const data = ref(null);
  const error = ref(null);
  const isLoading = ref(false);
  const cache = new Map();

  const {
    cacheKey = null,
    cacheTime = 5 * 60 * 1000, // 5 minutes
    retry = 3,
    retryDelay = 1000
  } = options;

  const execute = async (...args) => {
    const key = cacheKey || JSON.stringify(args);
    
    // Check cache first
    if (cache.has(key)) {
      const cached = cache.get(key);
      if (Date.now() - cached.timestamp < cacheTime) {
        data.value = cached.data;
        return cached.data;
      }
    }

    isLoading.value = true;
    error.value = null;

    let lastError;
    for (let i = 0; i < retry; i++) {
      try {
        const result = await apiCall(...args);
        data.value = result;
        
        // Cache the result
        cache.set(key, {
          data: result,
          timestamp: Date.now()
        });
        
        return result;
      } catch (err) {
        lastError = err;
        if (i < retry - 1) {
          await new Promise(resolve => setTimeout(resolve, retryDelay));
        }
      }
    }

    error.value = lastError;
    throw lastError;
  };

  const clearCache = () => {
    cache.clear();
  };

  // Cleanup on unmount
  onUnmounted(() => {
    clearCache();
  });

  return {
    data,
    error,
    isLoading,
    execute,
    clearCache
  };
}

/**
 * Composable for virtual scrolling
 * @param {Array} items - Items to render
 * @param {number} itemHeight - Height of each item
 * @param {number} containerHeight - Height of the container
 * @returns {Object} - Virtual scrolling utilities
 */
export function useVirtualScroll(items, itemHeight, containerHeight) {
  const scrollTop = ref(0);
  const containerRef = ref(null);

  const visibleItems = computed(() => {
    const start = Math.floor(scrollTop.value / itemHeight);
    const end = Math.min(
      items.value.length,
      start + Math.ceil(containerHeight / itemHeight) + 1
    );

    return items.value.slice(start, end).map((item, index) => ({
      ...item,
      index: start + index,
      top: (start + index) * itemHeight
    }));
  });

  const totalHeight = computed(() => items.value.length * itemHeight);

  const handleScroll = throttle((e) => {
    scrollTop.value = e.target.scrollTop;
  }, 16);

  return {
    containerRef,
    visibleItems,
    totalHeight,
    handleScroll
  };
}

export default {
  useOptimizedFunction,
  usePerformanceMonitoring,
  useOptimizedScroll,
  useOptimizedClick,
  useLazyLoading,
  useOptimizedImage,
  useOptimizedAPI,
  useVirtualScroll
};
