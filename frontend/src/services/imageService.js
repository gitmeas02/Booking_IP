// Image optimization service
class ImageService {
  constructor() {
    this.cache = new Map();
    this.baseUrl = 'http://localhost:8100';
  }

  // Optimize image URL for better performance
  getOptimizedImageUrl(imagePath, options = {}) {
    const { width = 300, height = 200, quality = 85 } = options;
    
    // Clean the path
    const cleanPath = imagePath.replace(/^\/+/, '');
    
    // Use image proxy for optimization
    return `${this.baseUrl}/api/images/proxy?path=${encodeURIComponent(cleanPath)}&w=${width}&h=${height}&q=${quality}`;
  }

  // Get hotel image URL
  getHotelImageUrl(imageUrl, options = {}) {
    if (!imageUrl) return this.getPlaceholderImage();
    
    const cleanUrl = imageUrl.startsWith('ownerimages/') ? imageUrl : `ownerimages/${imageUrl}`;
    return this.getOptimizedImageUrl(cleanUrl, options);
  }

  // Get room image URL
  getRoomImageUrl(imageUrl, options = {}) {
    if (!imageUrl) return this.getPlaceholderImage();
    
    const cleanUrl = imageUrl.startsWith('ownerimages/') ? imageUrl : `ownerimages/room-images/${imageUrl}`;
    return this.getOptimizedImageUrl(cleanUrl, options);
  }

  // Get placeholder image
  getPlaceholderImage() {
    return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2Y1ZjVmNSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTRweCIgZmlsbD0iIzk5OTk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';
  }

  // Preload images for better performance
  preloadImages(urls) {
    urls.forEach(url => {
      if (!this.cache.has(url)) {
        const img = new Image();
        img.onload = () => this.cache.set(url, true);
        img.src = url;
      }
    });
  }

  // Create responsive image URLs
  getResponsiveImageUrls(imagePath) {
    return {
      thumbnail: this.getOptimizedImageUrl(imagePath, { width: 150, height: 100 }),
      medium: this.getOptimizedImageUrl(imagePath, { width: 400, height: 300 }),
      large: this.getOptimizedImageUrl(imagePath, { width: 800, height: 600 }),
      full: this.getOptimizedImageUrl(imagePath, { width: 1200, height: 900 })
    };
  }
}

export const imageService = new ImageService();
