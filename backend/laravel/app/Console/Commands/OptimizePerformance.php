<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;
use App\Services\DatabaseOptimizationService;
use Illuminate\Support\Facades\Log;

class OptimizePerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize-performance {--warmup : Warm up caches} {--cleanup : Clean up old data} {--indexes : Optimize indexes} {--stats : Show performance stats}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize application performance through caching, database optimization, and cleanup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting performance optimization...');
        
        if ($this->option('warmup')) {
            $this->warmupCaches();
        }
        
        if ($this->option('cleanup')) {
            $this->cleanupData();
        }
        
        if ($this->option('indexes')) {
            $this->optimizeIndexes();
        }
        
        if ($this->option('stats')) {
            $this->showStats();
        }
        
        if (!$this->option('warmup') && !$this->option('cleanup') && !$this->option('indexes') && !$this->option('stats')) {
            // Run all optimizations by default
            $this->warmupCaches();
            $this->optimizeIndexes();
            $this->cleanupData();
            $this->showStats();
        }
        
        $this->info('Performance optimization completed!');
    }
    
    /**
     * Warm up application caches
     */
    private function warmupCaches()
    {
        $this->info('Warming up caches...');
        
        try {
            CacheService::warmupCache();
            $this->info('✓ Caches warmed up successfully');
        } catch (\Exception $e) {
            $this->error('✗ Cache warmup failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Clean up old data
     */
    private function cleanupData()
    {
        $this->info('Cleaning up old data...');
        
        try {
            $results = DatabaseOptimizationService::cleanupOldData();
            
            if (isset($results['error'])) {
                $this->error('✗ Cleanup failed: ' . $results['error']);
            } else {
                $this->info('✓ Cleanup completed');
                
                foreach ($results as $type => $count) {
                    $this->line("  - Removed {$count} {$type}");
                }
            }
        } catch (\Exception $e) {
            $this->error('✗ Cleanup failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Optimize database indexes
     */
    private function optimizeIndexes()
    {
        $this->info('Optimizing database indexes...');
        
        try {
            DatabaseOptimizationService::optimizeConnections();
            DatabaseOptimizationService::optimizeIndexes();
            DatabaseOptimizationService::optimizeTableStructure();
            $this->info('✓ Database optimization completed');
        } catch (\Exception $e) {
            $this->error('✗ Database optimization failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Show performance statistics
     */
    private function showStats()
    {
        $this->info('Performance Statistics:');
        $this->newLine();
        
        // Cache statistics
        $cacheStats = CacheService::getCacheStats();
        $this->info('Cache Statistics:');
        $this->line("  Driver: {$cacheStats['driver']}");
        $this->line("  Hit Ratio: {$cacheStats['hit_ratio']}%");
        $this->line("  Memory Usage: {$cacheStats['memory_usage']}");
        $this->line("  Active Keys: {$cacheStats['active_keys']}");
        $this->newLine();
        
        // Database statistics
        $dbStats = DatabaseOptimizationService::getDatabaseStats();
        
        if (isset($dbStats['error'])) {
            $this->error('Database stats error: ' . $dbStats['error']);
        } else {
            $this->info('Database Statistics:');
            $this->line("  Total Connections: {$dbStats['total_connections']}");
            
            $this->newLine();
            $this->info('Top 5 Largest Tables:');
            $this->table(
                ['Table', 'Size (MB)', 'Rows'],
                collect($dbStats['table_sizes'])->take(5)->map(function($table) {
                    return [$table->table_name, $table->size_mb, $table->table_rows];
                })->toArray()
            );
            
            if (isset($dbStats['query_cache'])) {
                $this->newLine();
                $this->info('Query Cache:');
                foreach ($dbStats['query_cache'] as $key => $value) {
                    $this->line("  {$key}: {$value}");
                }
            }
        }
        
        // Memory usage
        $this->newLine();
        $this->info('Memory Usage:');
        $this->line('  Current: ' . round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB');
        $this->line('  Peak: ' . round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB');
        $this->line('  Limit: ' . ini_get('memory_limit'));
    }
}
