<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class OptimizedImageController extends Controller
{
    /**
     * Serve optimized images with caching and resizing
     */
    public function proxy(Request $request)
    {
        $path = $request->input('path');
        $width = (int) $request->input('w', 300);
        $height = (int) $request->input('h', 200);
        $quality = (int) $request->input('q', 85);

        // Validate inputs
        if (empty($path)) {
            return response()->json(['error' => 'Path is required'], 400);
        }

        // Limit dimensions for security
        $width = min($width, 1920);
        $height = min($height, 1080);
        $quality = min($quality, 100);

        // Generate cache key
        $cacheKey = 'image_' . md5($path . $width . $height . $quality);

        try {
            // Check cache first
            if (Cache::has($cacheKey)) {
                $cachedImage = Cache::get($cacheKey);
                return Response::make($cachedImage['data'], 200, [
                    'Content-Type' => $cachedImage['mime'],
                    'Cache-Control' => 'public, max-age=86400',
                    'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 86400)
                ]);
            }

            // Clean and validate the path
            $cleanPath = $this->cleanImagePath($path);
            
            // Try different storage locations
            $imageContent = $this->getImageFromStorage($cleanPath);
            
            if (!$imageContent) {
                return $this->getPlaceholderImage($width, $height);
            }

            // For now, just return the original image with proper headers
            // TODO: Add image resizing when Intervention Image is properly installed
            
            // Detect mime type
            $mimeType = $this->getMimeType($imageContent);

            // Cache the image for 24 hours
            Cache::put($cacheKey, [
                'data' => $imageContent,
                'mime' => $mimeType
            ], 86400);

            return Response::make($imageContent, 200, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400',
                'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 86400)
            ]);

        } catch (\Exception $e) {
            Log::error('Image processing error: ' . $e->getMessage());
            return $this->getPlaceholderImage($width, $height);
        }
    }

    /**
     * Clean and validate image path
     */
    private function cleanImagePath($path)
    {
        // Remove leading slashes and clean path
        $cleanPath = ltrim($path, '/');
        
        // Prevent directory traversal
        $cleanPath = str_replace(['../', '..\\'], '', $cleanPath);
        
        return $cleanPath;
    }

    /**
     * Get image from various storage locations
     */
    private function getImageFromStorage($path)
    {
        // Try MinIO first
        try {
            $minioClient = Storage::disk('minio');
            if ($minioClient->exists($path)) {
                return $minioClient->get($path);
            }
        } catch (\Exception $e) {
            Log::warning('MinIO access failed: ' . $e->getMessage());
        }

        // Try different path variations
        $pathVariations = [
            $path,
            'ownerimages/' . $path,
            'ownerimages/room-images/' . $path,
            str_replace('ownerimages/', '', $path),
            str_replace('ownerimages/room-images/', '', $path)
        ];

        foreach ($pathVariations as $variation) {
            try {
                $minioClient = Storage::disk('minio');
                if ($minioClient->exists($variation)) {
                    return $minioClient->get($variation);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    /**
     * Detect mime type from image content
     */
    private function getMimeType($imageContent)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $imageContent);
        finfo_close($finfo);
        
        return $mimeType ?: 'image/jpeg';
    }

    /**
     * Generate placeholder image (simple SVG)
     */
    private function getPlaceholderImage($width, $height)
    {
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                    <rect width="' . $width . '" height="' . $height . '" fill="#f5f5f5"/>
                    <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14" fill="#999999" text-anchor="middle" dy=".3em">No Image</text>
                </svg>';

        return Response::make($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400'
        ]);
    }

    /**
     * Clear image cache
     */
    public function clearCache()
    {
        Cache::flush();
        return response()->json(['success' => true, 'message' => 'Image cache cleared']);
    }
}
