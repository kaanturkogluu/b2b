<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class HandleNotFoundMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // 404 hatası durumunda kullanıcıyı uygun dashboard'a yönlendir
        if ($response->getStatusCode() === 404 && Auth::check()) {
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Aradığınız sayfa bulunamadı. Admin paneline yönlendirildiniz.');
            } elseif ($user->isStaff()) {
                return redirect()->route('staff.dashboard')->with('error', 'Aradığınız sayfa bulunamadı. Personel paneline yönlendirildiniz.');
            }
        }
        
        return $response;
    }
}
