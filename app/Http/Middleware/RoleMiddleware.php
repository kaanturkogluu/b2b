<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($role === 'admin' && !$user->isAdmin()) {
            // Admin sayfasına erişimi olmayan kullanıcıları kendi dashboard'larına yönlendir
            if ($user->isStaff()) {
                return redirect()->route('staff.dashboard')->with('error', 'Bu sayfaya erişim yetkiniz yok. Personel paneline yönlendirildiniz.');
            } else {
                return redirect()->route('dashboard')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
            }
        }
        
        if ($role === 'staff' && !$user->isStaff()) {
            // Staff sayfasına erişimi olmayan kullanıcıları kendi dashboard'larına yönlendir
            if ($user->isAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Bu sayfaya erişim yetkiniz yok. Admin paneline yönlendirildiniz.');
            } else {
                return redirect()->route('dashboard')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
            }
        }

        return $next($request);
    }
}
