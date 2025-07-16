<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        $startQueries = DB::getQueryLog();
        
        // Enable query logging
        DB::enableQueryLog();
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $endQueries = DB::getQueryLog();
        
        // Calculate performance metrics
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = $endMemory - $startMemory;
        $queryCount = count($endQueries) - count($startQueries);
        
        // Add performance headers
        $response->headers->set('X-Response-Time', round($executionTime, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', $this->formatBytes($memoryUsage));
        $response->headers->set('X-Query-Count', $queryCount);
        
        // Log slow requests
        if ($executionTime > 1000) { // Log if response time > 1 second
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime . 'ms',
                'memory_usage' => $this->formatBytes($memoryUsage),
                'query_count' => $queryCount,
                'user_id' => Auth::check() ? Auth::user()->id : null,
            ]);
        }
        
        // Log high memory usage
        if ($memoryUsage > 10 * 1024 * 1024) { // Log if memory usage > 10MB
            Log::warning('High memory usage detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'memory_usage' => $this->formatBytes($memoryUsage),
                'execution_time' => $executionTime . 'ms',
                'user_id' => Auth::check() ? Auth::user()->id : null,
            ]);
        }
        
        // Log N+1 query problems
        if ($queryCount > 10) {
            Log::warning('High query count detected (possible N+1)', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'query_count' => $queryCount,
                'execution_time' => $executionTime . 'ms',
                'user_id' => Auth::check() ? Auth::user()->id : null,
            ]);
        }
        
        return $response;
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
