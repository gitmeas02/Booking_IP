/**
 * Centralized image URL utility for consistent image handling across the application
 */

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8100/api';

/**
 * Generate image URL using Laravel image proxy
 * @param {string} imagePath - The image path from database
 * @param {Object} options - Optional parameters for image optimization
 * @returns {string} - Complete image URL
 */
export function getImageUrl(imagePath, options = {}) {
  if (!imagePath) {
    return getPlaceholderImage();
  }

  // Handle different URL formats
  if (imagePath.startsWith('http')) {
    return imagePath;
  }

  // Keep the full path including 'ownerimages/' prefix
  // Database stores: ownerimages/room-images/room_1_xxx.jpg
  // API expects: ownerimages/room-images/room_1_xxx.jpg
  let cleanImagePath = imagePath;

  // Use Laravel image proxy - API_BASE_URL already includes /api
  const fullUrl = `${API_BASE_URL}/images/${cleanImagePath}`;
  
  // Debug logging
  console.log('Image URL generation:', {
    original: imagePath,
    cleaned: cleanImagePath,
    final: fullUrl
  });

  return fullUrl;
}

/**
 * Generate room image URL with proper path handling
 * @param {string} imagePath - The image path from database
 * @returns {string} - Complete image URL
 */
export function getRoomImageUrl(imagePath) {
  return getImageUrl(imagePath);
}

/**
 * Generate hotel image URL with proper path handling
 * @param {string} imagePath - The image path from database
 * @returns {string} - Complete image URL
 */
export function getHotelImageUrl(imagePath) {
  return getImageUrl(imagePath);
}

/**
 * Generate thumbnail URL
 * @param {string} imagePath - The image path from database
 * @returns {string} - Complete thumbnail URL
 */
export function getThumbnailUrl(imagePath) {
  return getImageUrl(imagePath);
}

/**
 * Get placeholder image for missing/broken images
 * @returns {string} - Data URL for placeholder image
 */
export function getPlaceholderImage() {
  return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2Y1ZjVmNSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTRweCIgZmlsbD0iIzk5OTk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';
}

/**
 * Preload images for better performance
 * @param {string[]} imageUrls - Array of image URLs to preload
 */
export function preloadImages(imageUrls) {
  imageUrls.forEach(url => {
    const img = new Image();
    img.src = url;
  });
}

/**
 * Handle image loading errors
 * @param {Event} event - The error event
 */
export function handleImageError(event) {
  console.error('Image failed to load:', event.target.src);
  event.target.src = getPlaceholderImage();
}

/**
 * Create responsive image URLs for different screen sizes
 * @param {string} imagePath - The base image path
 * @returns {Object} - Object with different image sizes
 */
export function getResponsiveImageUrls(imagePath) {
  const baseUrl = getImageUrl(imagePath);
  
  return {
    thumbnail: baseUrl, // For now, all point to same URL
    medium: baseUrl,    // Can be enhanced with actual resizing
    large: baseUrl,
    original: baseUrl
  };
}

/**
 * Validate image format
 * @param {File} file - The file to validate
 * @returns {boolean} - True if valid image format
 */
export function isValidImageFormat(file) {
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
  return allowedTypes.includes(file.type);
}

/**
 * Validate image size
 * @param {File} file - The file to validate
 * @param {number} maxSize - Maximum size in bytes (default: 5MB)
 * @returns {boolean} - True if within size limit
 */
export function isValidImageSize(file, maxSize = 5 * 1024 * 1024) {
  return file.size <= maxSize;
}

/**
 * Test image loading
 * @param {string} url - The image URL to test
 * @returns {Promise} - Promise that resolves/rejects based on image loading
 */
export function testImageLoad(url) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.onload = () => {
      console.log('✅ Image loaded successfully:', url);
      resolve(url);
    };
    img.onerror = () => {
      console.log('❌ Image failed to load:', url);
      reject(new Error(`Failed to load image: ${url}`));
    };
    img.src = url;
  });
}

export default {
  getImageUrl,
  getRoomImageUrl,
  getHotelImageUrl,
  getThumbnailUrl,
  getPlaceholderImage,
  preloadImages,
  handleImageError,
  getResponsiveImageUrls,
  isValidImageFormat,
  isValidImageSize,
  testImageLoad
};
