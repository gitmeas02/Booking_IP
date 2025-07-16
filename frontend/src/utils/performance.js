/**
 * Performance utility functions for Vue.js applications
 * Includes debouncing, throttling, and other performance optimizations
 */

/**
 * Debounce function - delays execution until after wait milliseconds have elapsed
 * @param {Function} func - The function to debounce
 * @param {number} wait - The delay in milliseconds
 * @param {boolean} immediate - Whether to execute immediately on the first call
 * @returns {Function} - The debounced function
 */
export function debounce(func, wait, immediate = false) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      timeout = null;
      if (!immediate) func(...args);
    };
    const callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func(...args);
  };
}

/**
 * Throttle function - limits execution to at most once per wait milliseconds
 * @param {Function} func - The function to throttle
 * @param {number} wait - The delay in milliseconds
 * @returns {Function} - The throttled function
 */
export function throttle(func, wait) {
  let inThrottle;
  return function executedFunction(...args) {
    if (!inThrottle) {
      func.apply(this, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, wait);
    }
  };
}

/**
 * Optimized event listener with passive option for better scroll performance
 * @param {Element} element - The element to attach the listener to
 * @param {string} event - The event type
 * @param {Function} handler - The event handler
 * @param {Object} options - Event listener options
 */
export function addOptimizedEventListener(element, event, handler, options = {}) {
  const defaultOptions = {
    passive: ['scroll', 'touchstart', 'touchmove', 'wheel'].includes(event),
    ...options
  };
  
  element.addEventListener(event, handler, defaultOptions);
  
  // Return cleanup function
  return () => element.removeEventListener(event, handler, defaultOptions);
}

/**
 * Intersection Observer with performance optimizations
 * @param {Function} callback - The callback function
 * @param {Object} options - Intersection observer options
 * @returns {IntersectionObserver} - The observer instance
 */
export function createOptimizedIntersectionObserver(callback, options = {}) {
  const defaultOptions = {
    rootMargin: '50px',
    threshold: 0.1,
    ...options
  };
  
  return new IntersectionObserver(callback, defaultOptions);
}

/**
 * Lazy load images with intersection observer
 * @param {string} selector - CSS selector for images to lazy load
 * @param {Object} options - Intersection observer options
 */
export function lazyLoadImages(selector = 'img[data-src]', options = {}) {
  const images = document.querySelectorAll(selector);
  
  if (!images.length) return;
  
  const observer = createOptimizedIntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        img.classList.remove('lazy');
        observer.unobserve(img);
      }
    });
  }, options);
  
  images.forEach(img => observer.observe(img));
  
  return observer;
}

/**
 * Prefetch resources for better performance
 * @param {string} url - The URL to prefetch
 * @param {string} as - The resource type (script, style, image, etc.)
 */
export function prefetchResource(url, as = 'fetch') {
  const link = document.createElement('link');
  link.rel = 'prefetch';
  link.href = url;
  link.as = as;
  document.head.appendChild(link);
}

/**
 * Preload critical resources
 * @param {string} url - The URL to preload
 * @param {string} as - The resource type (script, style, image, etc.)
 */
export function preloadResource(url, as = 'fetch') {
  const link = document.createElement('link');
  link.rel = 'preload';
  link.href = url;
  link.as = as;
  document.head.appendChild(link);
}

/**
 * Efficient scroll position tracking
 * @param {Function} callback - The callback function
 * @param {number} throttleMs - Throttle delay in milliseconds
 * @returns {Function} - Cleanup function
 */
export function trackScrollPosition(callback, throttleMs = 16) {
  const throttledCallback = throttle(callback, throttleMs);
  
  const cleanup = addOptimizedEventListener(window, 'scroll', throttledCallback);
  
  return cleanup;
}

/**
 * Measure and log performance metrics
 * @param {string} name - The performance mark name
 * @param {Function} func - The function to measure
 * @returns {*} - The function result
 */
export async function measurePerformance(name, func) {
  const startMark = `${name}-start`;
  const endMark = `${name}-end`;
  
  performance.mark(startMark);
  
  try {
    const result = await func();
    performance.mark(endMark);
    performance.measure(name, startMark, endMark);
    
    // Log performance in development
    if (import.meta.env.DEV) {
      const measure = performance.getEntriesByName(name)[0];
      console.log(`${name}: ${measure.duration.toFixed(2)}ms`);
    }
    
    return result;
  } catch (error) {
    performance.mark(endMark);
    performance.measure(name, startMark, endMark);
    throw error;
  } finally {
    // Clean up marks
    performance.clearMarks(startMark);
    performance.clearMarks(endMark);
    performance.clearMeasures(name);
  }
}

/**
 * Check if user prefers reduced motion
 * @returns {boolean} - True if user prefers reduced motion
 */
export function prefersReducedMotion() {
  return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Batch DOM updates to improve performance
 * @param {Function} updates - Function containing DOM updates
 */
export function batchDOMUpdates(updates) {
  requestAnimationFrame(() => {
    updates();
  });
}

/**
 * Virtual scrolling utility for large lists
 * @param {Array} items - The items to render
 * @param {number} itemHeight - Height of each item
 * @param {number} containerHeight - Height of the container
 * @param {number} scrollTop - Current scroll position
 * @returns {Object} - Visible items and positions
 */
export function calculateVirtualScrollItems(items, itemHeight, containerHeight, scrollTop) {
  const visibleCount = Math.ceil(containerHeight / itemHeight);
  const buffer = 5; // Extra items to render for smooth scrolling
  
  const startIndex = Math.max(0, Math.floor(scrollTop / itemHeight) - buffer);
  const endIndex = Math.min(items.length, startIndex + visibleCount + buffer * 2);
  
  const visibleItems = items.slice(startIndex, endIndex);
  const offsetY = startIndex * itemHeight;
  
  return {
    visibleItems,
    startIndex,
    endIndex,
    offsetY,
    totalHeight: items.length * itemHeight
  };
}

/**
 * Optimize bundle size by dynamic imports
 * @param {Function} importFunc - The dynamic import function
 * @param {number} timeout - Timeout in milliseconds
 * @returns {Promise} - The imported module
 */
export function optimizedImport(importFunc, timeout = 10000) {
  return Promise.race([
    importFunc(),
    new Promise((_, reject) => 
      setTimeout(() => reject(new Error('Import timeout')), timeout)
    )
  ]);
}

/**
 * Memory-efficient event emitter
 */
export class OptimizedEventEmitter {
  constructor() {
    this.events = new Map();
  }
  
  on(event, callback) {
    if (!this.events.has(event)) {
      this.events.set(event, new Set());
    }
    this.events.get(event).add(callback);
    
    // Return cleanup function
    return () => this.off(event, callback);
  }
  
  off(event, callback) {
    if (this.events.has(event)) {
      this.events.get(event).delete(callback);
      if (this.events.get(event).size === 0) {
        this.events.delete(event);
      }
    }
  }
  
  emit(event, ...args) {
    if (this.events.has(event)) {
      this.events.get(event).forEach(callback => callback(...args));
    }
  }
  
  clear() {
    this.events.clear();
  }
}

export default {
  debounce,
  throttle,
  addOptimizedEventListener,
  createOptimizedIntersectionObserver,
  lazyLoadImages,
  prefetchResource,
  preloadResource,
  trackScrollPosition,
  measurePerformance,
  prefersReducedMotion,
  batchDOMUpdates,
  calculateVirtualScrollItems,
  optimizedImport,
  OptimizedEventEmitter
};
