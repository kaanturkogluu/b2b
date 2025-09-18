<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Kullanıcı adı alanı zorunludur.',
            'password.required' => 'Şifre alanı zorunludur.',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'username' => 'Kullanıcı adı veya şifre hatalı.',
        ])->onlyInput('username');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * Show dashboard based on user role
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Son aktiviteleri getir
            $recentActivities = ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            return view('admin.dashboard', compact('user', 'recentActivities'));
        } elseif ($user->isStaff()) {
            return view('staff.dashboard', compact('user'));
        }
        
        return redirect()->route('login');
    }
}
