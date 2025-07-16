<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class CacheService
{
    /**
     * Cache TTL in seconds
     */
    const DEFAULT_TTL = 3600; // 1 hour
    const LONG_TTL = 86400; // 24 hours
    const SHORT_TTL = 300; // 5 minutes

    /**
     * Cache a result with automatic key generation
     */
    public static function remember(string $key, callable $callback, int $ttl = self::DEFAULT_TTL)
    {
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::error('Cache error: ' . $e->getMessage());
            return $callback();
        }
    }

    /**
     * Cache property list with intelligent invalidation
     */
    public static function cachePropertyList(int $userId, callable $callback)
    {
        $key = "owner_properties_{$userId}";
        return self::remember($key, $callback, self::LONG_TTL);
    }

    /**
     * Cache room types for a property
     */
    public static function cacheRoomTypes(int $propertyId, callable $callback)
    {
        $key = "property_rooms_{$propertyId}";
        return self::remember($key, $callback, self::DEFAULT_TTL);
    }

    /**
     * Cache amenities list
     */
    public static function cacheAmenities(callable $callback)
    {
        $key = "amenities_list";
        return self::remember($key, $callback, self::LONG_TTL);
    }

    /**
     * Cache room availability
     */
    public static function cacheRoomAvailability(int $roomId, string $dateRange, callable $callback)
    {
        $key = "room_availability_{$roomId}_{$dateRange}";
        return self::remember($key, $callback, self::SHORT_TTL);
    }

    /**
     * Invalidate property-related caches
     */
    public static function invalidatePropertyCache(int $userId, int $propertyId = null)
    {
        $patterns = [
            "owner_properties_{$userId}",
            "property_rooms_{$propertyId}",
            "room_availability_*",
        ];

        foreach ($patterns as $pattern) {
            if (strpos($pattern, '*') !== false) {
                // For wildcard patterns, we need to use Redis or implement a cache tagging system
                self::invalidatePattern($pattern);
            } else {
                Cache::forget($pattern);
            }
        }
    }

    /**
     * Invalidate cache patterns (requires Redis)
     */
    private static function invalidatePattern(string $pattern)
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = Redis::connection();
                $keys = $redis->keys($pattern);
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Pattern cache invalidation failed: ' . $e->getMessage());
        }
    }

    /**
     * Warm up commonly accessed caches
     */
    public static function warmupCache()
    {
        try {
            // Warm up amenities cache
            self::cacheAmenities(function() {
                return \App\Models\Amenity::select('id', 'name', 'icon')->get();
            });

            Log::info('Cache warmup completed');
        } catch (\Exception $e) {
            Log::error('Cache warmup failed: ' . $e->getMessage());
        }
    }

    /**
     * Clear all application caches
     */
    public static function clearAllCaches()
    {
        try {
            Cache::flush();
            Log::info('All caches cleared');
        } catch (\Exception $e) {
            Log::error('Cache clear failed: ' . $e->getMessage());
        }
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats()
    {
        try {
            $stats = [
                'driver' => config('cache.default'),
                'hit_ratio' => self::calculateHitRatio(),
                'memory_usage' => self::getMemoryUsage(),
                'active_keys' => self::getActiveKeyCount(),
            ];

            return $stats;
        } catch (\Exception $e) {
            Log::error('Cache stats error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Calculate cache hit ratio
     */
    private static function calculateHitRatio()
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = Redis::connection();
                $info = $redis->info('stats');
                
                if (isset($info['keyspace_hits']) && isset($info['keyspace_misses'])) {
                    $hits = $info['keyspace_hits'];
                    $misses = $info['keyspace_misses'];
                    $total = $hits + $misses;
                    
                    return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
                }
            }
            return 'N/A';
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    /**
     * Get memory usage
     */
    private static function getMemoryUsage()
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = Redis::connection();
                $info = $redis->info('memory');
                return $info['used_memory_human'] ?? 'N/A';
            }
            return 'N/A';
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    /**
     * Get active key count
     */
    private static function getActiveKeyCount()
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = Redis::connection();
                $info = $redis->info('keyspace');
                
                // Parse keyspace info for db0
                if (isset($info['db0'])) {
                    preg_match('/keys=(\d+)/', $info['db0'], $matches);
                    return $matches[1] ?? 0;
                }
            }
            return 'N/A';
        } catch (\Exception $e) {
            return 'Error';
        }
    }
}
