<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    /**
     * Routes to exclude from logging (to prevent excessive logs)
     */
    protected $excludedRoutes = [
        'livewire*',
        '_debugbar*',
        'telescope*',
    ];

    /**
     * HTTP methods to log
     */
    protected $loggedMethods = [
        'POST', 'PUT', 'PATCH', 'DELETE'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip logging for excluded routes
        if ($this->shouldSkip($request)) {
            return $response;
        }

        // Log only specific HTTP methods (POST, PUT, PATCH, DELETE)
        // GET isteklerini loglamıyoruz - çok fazla olur
        if (in_array($request->method(), $this->loggedMethods)) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Check if request should be skipped
     */
    protected function shouldSkip(Request $request): bool
    {
        $path = $request->path();
        
        foreach ($this->excludedRoutes as $pattern) {
            if (fnmatch($pattern, $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the request
     */
    protected function logRequest(Request $request, Response $response): void
    {
        try {
            $routeName = $request->route() ? $request->route()->getName() : 'unknown';
            $action = $this->determineAction($request);
            
            ActivityLog::create([
                'type' => 'http_request',
                'description' => $this->generateDescription($request, $action),
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request_method' => $request->method(),
                'request_url' => $request->fullUrl(),
                'session_id' => session()->getId(),
                'related_id' => null,
                'related_type' => null,
                'metadata' => [
                    'route_name' => $routeName,
                    'action' => $action,
                    'status_code' => $response->getStatusCode(),
                    'request_data' => $this->sanitizeRequestData($request),
                ]
            ]);
        } catch (\Exception $e) {
            // Loglama hatası sistemin çalışmasını engellememeli
            \Log::error('ActivityLog middleware error: ' . $e->getMessage());
        }
    }

    /**
     * Determine the action from request
     */
    protected function determineAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route() ? $request->route()->getName() : '';

        // Route ismine göre belirle
        if (str_contains($routeName, 'store')) {
            return 'created';
        } elseif (str_contains($routeName, 'update')) {
            return 'updated';
        } elseif (str_contains($routeName, 'destroy')) {
            return 'deleted';
        }

        // HTTP methoduna göre belirle
        return match($method) {
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'action'
        };
    }

    /**
     * Generate human readable description
     */
    protected function generateDescription(Request $request, string $action): string
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'Misafir';
        $routeName = $request->route() ? $request->route()->getName() : 'unknown route';
        
        return "{$userName} - {$request->method()} isteği: {$routeName} ({$action})";
    }

    /**
     * Sanitize request data (remove passwords, sensitive info)
     */
    protected function sanitizeRequestData(Request $request): array
    {
        $data = $request->except([
            'password',
            'password_confirmation',
            '_token',
            '_method'
        ]);

        // Limit data size to prevent huge logs
        $jsonData = json_encode($data);
        if (strlen($jsonData) > 5000) {
            return ['_note' => 'Data too large to log'];
        }

        return $data;
    }
}
