<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class ImageProxyController extends Controller
{
    public function getImage($imageName)
    {
        try {
            // Keep the full path including 'ownerimages/' prefix
            // The frontend already sends the correct path format
            $cleanImageName = $imageName;
            
            // Log for debugging
            Log::info("Image request: path={$imageName}");
            
            // Try different possible paths
            $possiblePaths = [
                $cleanImageName,                    // Direct path as provided
                "ownerimages/{$cleanImageName}",    // Add ownerimages if not present
                $imageName,                         // Original path as provided
            ];
            
            $imageContent = null;
            $actualPath = null;
            
            foreach ($possiblePaths as $path) {
                Log::info("Trying path: {$path}");
                if (Storage::disk('minio')->exists($path)) {
                    $imageContent = Storage::disk('minio')->get($path);
                    $actualPath = $path;
                    Log::info("Found image at path: {$path}");
                    break;
                }
            }
            
            if (!$imageContent) {
                Log::warning("Image not found in any path: " . json_encode($possiblePaths));
                
                // Return a placeholder image instead of 404
                $placeholderSvg = '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f5f5f5"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14px" fill="#999999" text-anchor="middle" dy=".3em">No Image</text></svg>';
                
                return Response::make($placeholderSvg, 200, [
                    'Content-Type' => 'image/svg+xml',
                    'Cache-Control' => 'public, max-age=3600',
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                    'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                ]);
            }
            
            // Determine the mime type based on file extension
            $extension = pathinfo($actualPath, PATHINFO_EXTENSION);
            $mimeType = match(strtolower($extension)) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
                default => 'image/jpeg', // Default fallback
            };
            
            // Return the image with proper headers
            return Response::make($imageContent, 200, [
                'Content-Type' => $mimeType,
                'Content-Length' => strlen($imageContent),
                'Cache-Control' => 'public, max-age=86400', // Cache for 24 hours
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        } catch (\Exception $e) {
            Log::error("Error serving image {$imageName}: " . $e->getMessage());
            
            // Return placeholder on error
            $placeholderSvg = '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f5f5f5"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14px" fill="#999999" text-anchor="middle" dy=".3em">Error Loading Image</text></svg>';
            
            return Response::make($placeholderSvg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'public, max-age=3600',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        }
    }

    public function listImages()
    {
        try {
            // Get all files recursively
            $allFiles = Storage::disk('minio')->allFiles();
            
            // Filter only image files
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            $images = array_filter($allFiles, function($file) use ($imageExtensions) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                return in_array($extension, $imageExtensions);
            });
            
            return response()->json([
                'images' => array_values($images),
                'count' => count($images),
                'all_files' => $allFiles, // For debugging
                'bucket' => env('MINIO_BUCKET', 'default')
            ]);
        } catch (\Exception $e) {
            Log::error("Error listing images: " . $e->getMessage());
            return response()->json(['error' => 'Error listing images: ' . $e->getMessage()], 500);
        }
    }

    public function testMinioConnection()
    {
        try {
            $exists = Storage::disk('minio')->exists('test.txt');
            $files = Storage::disk('minio')->allFiles();
            
            return response()->json([
                'status' => 'connected',
                'bucket' => env('MINIO_BUCKET'),
                'endpoint' => env('MINIO_ENDPOINT'),
                'files_count' => count($files),
                'sample_files' => array_slice($files, 0, 10),
                'all_files' => $files // For debugging - remove in production
            ]);
        } catch (\Exception $e) {
            Log::error("MinIO connection test failed: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function testSpecificImage($imagePath)
    {
        try {
            $decodedPath = urldecode($imagePath);
            
            // Try different possible paths
            $possiblePaths = [
                $decodedPath,
                "ownerimages/{$decodedPath}",
                str_replace('ownerimages/', '', $decodedPath),
            ];
            
            $results = [];
            foreach ($possiblePaths as $path) {
                $exists = Storage::disk('minio')->exists($path);
                $results[] = [
                    'path' => $path,
                    'exists' => $exists,
                    'size' => $exists ? Storage::disk('minio')->size($path) : 0
                ];
            }
            
            return response()->json([
                'original_path' => $decodedPath,
                'results' => $results
            ]);
        } catch (\Exception $e) {
            Log::error("Error testing specific image {$imagePath}: " . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
