<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitoringMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = round(($endTime - $startTime) * 1000, 2); // ms
        $memoryUsage = round(($endMemory - $startMemory) / 1024 / 1024, 2); // MB
        
        // Log slow queries
        if ($executionTime > 1000) { // 1 saniyeden uzun
            \Log::warning('Slow Request Detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime . 'ms',
                'memory_usage' => $memoryUsage . 'MB',
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
        }
        
        // Add performance headers in development
        if (app()->environment('local')) {
            $response->headers->set('X-Execution-Time', $executionTime . 'ms');
            $response->headers->set('X-Memory-Usage', $memoryUsage . 'MB');
        }
        
        return $response;
    }
}
