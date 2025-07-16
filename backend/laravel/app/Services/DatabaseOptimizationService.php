<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseOptimizationService
{
    /**
     * Optimize database queries by enabling foreign key checks
     */
    public static function optimizeConnections()
    {
        try {
            // Enable foreign key constraints
            DB::statement('SET foreign_key_checks=1');
            
            // Optimize MySQL settings for better performance
            DB::statement('SET SQL_MODE=""');
            DB::statement('SET SESSION query_cache_type = ON');
            DB::statement('SET SESSION query_cache_size = 1048576');
            
            Log::info('Database optimization applied');
        } catch (\Exception $e) {
            Log::error('Database optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Optimize table indexes
     */
    public static function optimizeIndexes()
    {
        try {
            $tables = [
                'owner_applications' => ['user_id', 'status', 'created_at'],
                'room_types' => ['application_id', 'created_at'],
                'room_images' => ['room_type_id'],
                'amenities_room_type' => ['room_id', 'amenity_id'],
                'bookings' => ['room_id', 'user_id', 'check_in_date', 'check_out_date'],
                'room_prices' => ['room_type_id', 'date'],
                'application_photos' => ['application_id'],
                'application_amenities' => ['application_id', 'amenity_id'],
            ];

            foreach ($tables as $table => $columns) {
                foreach ($columns as $column) {
                    try {
                        DB::statement("CREATE INDEX IF NOT EXISTS idx_{$table}_{$column} ON {$table} ({$column})");
                    } catch (\Exception $e) {
                        // Index might already exist, continue
                        Log::debug("Index idx_{$table}_{$column} might already exist");
                    }
                }
            }

            Log::info('Database indexes optimized');
        } catch (\Exception $e) {
            Log::error('Index optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Analyze query performance
     */
    public static function analyzeQueries()
    {
        $slowQueries = DB::select('SHOW FULL PROCESSLIST');
        $slowLogEnabled = DB::select('SHOW VARIABLES LIKE "slow_query_log"');
        
        return [
            'active_queries' => count($slowQueries),
            'slow_log_enabled' => $slowLogEnabled[0]->Value ?? 'OFF',
            'performance_schema' => DB::select('SELECT @@performance_schema')[0]->{'@@performance_schema'} ?? 0,
        ];
    }

    /**
     * Clean up old data
     */
    public static function cleanupOldData()
    {
        try {
            $cleanupResults = [];
            
            // Clean up expired bookings (older than 1 year)
            $expiredBookings = DB::table('bookings')
                ->where('check_out_date', '<', now()->subYear())
                ->where('status', 'completed')
                ->count();
            
            if ($expiredBookings > 0) {
                DB::table('bookings')
                    ->where('check_out_date', '<', now()->subYear())
                    ->where('status', 'completed')
                    ->delete();
                
                $cleanupResults['expired_bookings'] = $expiredBookings;
            }
            
            // Clean up old room prices (older than 2 years)
            $oldPrices = DB::table('room_prices')
                ->where('date', '<', now()->subYears(2))
                ->count();
            
            if ($oldPrices > 0) {
                DB::table('room_prices')
                    ->where('date', '<', now()->subYears(2))
                    ->delete();
                
                $cleanupResults['old_prices'] = $oldPrices;
            }
            
            // Clean up orphaned images
            $orphanedImages = DB::table('room_images')
                ->leftJoin('room_types', 'room_images.room_type_id', '=', 'room_types.id')
                ->whereNull('room_types.id')
                ->count();
            
            if ($orphanedImages > 0) {
                DB::table('room_images')
                    ->leftJoin('room_types', 'room_images.room_type_id', '=', 'room_types.id')
                    ->whereNull('room_types.id')
                    ->delete();
                
                $cleanupResults['orphaned_images'] = $orphanedImages;
            }
            
            Log::info('Database cleanup completed', $cleanupResults);
            return $cleanupResults;
            
        } catch (\Exception $e) {
            Log::error('Database cleanup failed: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Optimize table structure
     */
    public static function optimizeTableStructure()
    {
        try {
            $tables = [
                'owner_applications',
                'room_types',
                'room_images',
                'amenities_room_type',
                'bookings',
                'room_prices',
                'application_photos',
                'application_amenities',
            ];

            foreach ($tables as $table) {
                try {
                    DB::statement("OPTIMIZE TABLE {$table}");
                } catch (\Exception $e) {
                    Log::warning("Failed to optimize table {$table}: " . $e->getMessage());
                }
            }

            Log::info('Table structure optimization completed');
        } catch (\Exception $e) {
            Log::error('Table optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Get database statistics
     */
    public static function getDatabaseStats()
    {
        try {
            $stats = [];
            
            // Get table sizes
            $tableSizes = DB::select("
                SELECT 
                    table_name,
                    round(((data_length + index_length) / 1024 / 1024), 2) as size_mb,
                    table_rows
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
                ORDER BY (data_length + index_length) DESC
            ");
            
            $stats['table_sizes'] = $tableSizes;
            
            // Get connection info
            $connectionInfo = DB::select('SHOW STATUS LIKE "Connections"');
            $stats['total_connections'] = $connectionInfo[0]->Value ?? 0;
            
            // Get query cache info
            $queryCacheInfo = DB::select('SHOW STATUS LIKE "Qcache%"');
            $stats['query_cache'] = collect($queryCacheInfo)->pluck('Value', 'Variable_name')->toArray();
            
            return $stats;
            
        } catch (\Exception $e) {
            Log::error('Database stats error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
