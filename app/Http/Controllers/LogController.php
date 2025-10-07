<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\LoginLog;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Display activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filtreler
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('request_url', 'like', '%' . $search . '%')
                  ->orWhere('ip_address', 'like', '%' . $search . '%');
            });
        }

        $logs = $query->paginate(20);
        
        // Filtre için kullanıcı listesi
        $users = \App\Models\User::select('id', 'name', 'username')->orderBy('name')->get();
        
        // Log tipleri
        $types = ActivityLog::select('type')->distinct()->pluck('type');

        return view('admin.logs.activity', compact('logs', 'users', 'types'));
    }

    /**
     * Display login logs
     */
    public function loginLogs(Request $request)
    {
        $query = LoginLog::with('user')->orderBy('created_at', 'desc');

        // Filtreler
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('success')) {
            $query->where('success', $request->success == '1');
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                  ->orWhere('ip_address', 'like', '%' . $search . '%')
                  ->orWhere('failure_reason', 'like', '%' . $search . '%');
            });
        }

        $logs = $query->paginate(20);
        
        // Filtre için kullanıcı listesi
        $users = \App\Models\User::select('id', 'name', 'username')->orderBy('name')->get();

        return view('admin.logs.login', compact('logs', 'users'));
    }

    /**
     * Display log statistics
     */
    public function statistics()
    {
        // Son 7 günlük giriş istatistikleri
        $loginStats = LoginLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful'),
            DB::raw('SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'desc')
        ->get();

        // En aktif kullanıcılar (son 30 gün)
        $activeUsers = ActivityLog::select(
            'user_id',
            DB::raw('COUNT(*) as activity_count')
        )
        ->with('user:id,name,username')
        ->where('created_at', '>=', now()->subDays(30))
        ->whereNotNull('user_id')
        ->groupBy('user_id')
        ->orderBy('activity_count', 'desc')
        ->limit(10)
        ->get();

        // En çok kullanılan IP adresleri
        $topIPs = ActivityLog::select(
            'ip_address',
            DB::raw('COUNT(*) as request_count')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->whereNotNull('ip_address')
        ->groupBy('ip_address')
        ->orderBy('request_count', 'desc')
        ->limit(10)
        ->get();

        // Başarısız giriş denemeleri (son 24 saat)
        $recentFailedLogins = LoginLog::where('success', false)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.logs.statistics', compact(
            'loginStats',
            'activeUsers',
            'topIPs',
            'recentFailedLogins'
        ));
    }

    /**
     * Show detailed log
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        return view('admin.logs.show', compact('log'));
    }

    /**
     * Show detailed login log
     */
    public function showLoginLog($id)
    {
        $log = LoginLog::with('user')->findOrFail($id);
        return view('admin.logs.show-login', compact('log'));
    }
}
