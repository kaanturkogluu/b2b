<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bakim;
use App\Models\User;
use App\Models\ActivityLog;

class StaffController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Optimize edilmiş sorgular - tek sorguda tüm veriler
        $stats = DB::table('bakim')
            ->where('tamamlayan_personel_id', $user->id)
            ->selectRaw('
                COUNT(*) as total_services,
                SUM(CASE WHEN bakim_durumu = "Devam Ediyor" THEN 1 ELSE 0 END) as active_tasks,
                SUM(CASE WHEN bakim_durumu = "Tamamlandı" THEN 1 ELSE 0 END) as completed_tasks,
                SUM(CASE WHEN DATE(bakim_tarihi) = CURDATE() THEN 1 ELSE 0 END) as today_services
            ')
            ->first();
        
        // Bugünkü görevler - optimize edilmiş sorgu, son eklenenler ilk görünsün
        $todayTasks = Bakim::select('id', 'plaka', 'musteri_adi', 'bakim_tarihi', 'bakim_durumu')
            ->where('tamamlayan_personel_id', $user->id)
            ->whereDate('bakim_tarihi', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Yaklaşan görevler - optimize edilmiş sorgu, son eklenenler ilk görünsün
        $upcomingTasks = Bakim::select('id', 'plaka', 'musteri_adi', 'bakim_tarihi', 'bakim_durumu')
            ->where('tamamlayan_personel_id', $user->id)
            ->where('bakim_tarihi', '>', now()->endOfDay())
            ->where('bakim_tarihi', '<=', now()->addDays(7))
            ->orderBy('created_at', 'desc')
            ->get();
        
        $data = [
            'stats' => (array) $stats,
            'todayTasks' => $todayTasks,
            'upcomingTasks' => $upcomingTasks
        ];
        
        return view('staff.dashboard', compact('user', 'data'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        
        // Personelin performans istatistikleri
        $myServices = Bakim::where('personel_id', $user->id)->get();
        
        $performance = [
            'total_services' => $myServices->count(),
            'completed_services' => $myServices->where('bakim_durumu', 'Tamamlandı')->count(),
            'completion_rate' => $myServices->count() > 0 ? 
                round(($myServices->where('bakim_durumu', 'Tamamlandı')->count() / $myServices->count()) * 100, 1) : 0,
            'avg_service_time' => $this->calculateAverageServiceTime($myServices),
            'this_month_services' => $myServices->where('created_at', '>=', now()->startOfMonth())->count(),
        ];
        
        return view('staff.profile', compact('user', 'performance'));
    }
    
    public function tasks()
    {
        $user = Auth::user();
        
        // Personelin tüm görevleri - son eklenenler ilk görünsün
        $tasks = Bakim::where('personel_id', $user->id)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        
        return view('staff.tasks', compact('user', 'tasks'));
    }
    
    public function timeline()
    {
        $user = Auth::user();
        
        // Personelin zaman çizelgesi - son eklenenler ilk görünsün
        $timeline = Bakim::where('personel_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->groupBy(function($item) {
                            return $item->created_at->format('Y-m-d');
                        });
        
        return view('staff.timeline', compact('user', 'timeline'));
    }
    
    public function completeMaintenance(Request $request, Bakim $bakim)
    {
        // Servis zaten onaylanmış mı kontrol et
        if ($bakim->bakim_durumu == 'Tamamlandı') {
            return redirect()->route('staff.bakim.index')
                            ->with('error', 'Bu bakım zaten onaylanmış!');
        }

        $request->validate([
            'tamamlanma_notu' => 'nullable|string|max:1000'
        ]);

        $bakim->update([
            'bakim_durumu' => 'Tamamlandı',
            'tamamlayan_personel_id' => Auth::id(),
            'tamamlanma_tarihi' => now(),
            'tamamlanma_notu' => $request->tamamlanma_notu
        ]);

        // Activity log
        ActivityLog::log(
            'bakim_completed',
            "Bakım onaylandı: {$bakim->plaka} - {$bakim->musteri_adi}",
            Auth::id(),
            $bakim->id,
            'App\Models\Bakim',
            [
                'plaka' => $bakim->plaka,
                'musteri_adi' => $bakim->musteri_adi,
                'tamamlanma_notu' => $request->tamamlanma_notu
            ]
        );


        return redirect()->route('staff.bakim.index')
                        ->with('success', 'Bakım başarıyla onaylandı!');
    }

    private function calculateAverageServiceTime($services)
    {
        $completedServices = $services->where('bakim_durumu', 'Tamamlandı');
        
        if ($completedServices->count() == 0) {
            return 0;
        }
        
        $totalHours = 0;
        foreach ($completedServices as $service) {
            if ($service->tahmini_teslim_tarihi && $service->bakim_tarihi) {
                $hours = $service->bakim_tarihi->diffInHours($service->tahmini_teslim_tarihi);
                $totalHours += $hours;
            }
        }
        
        return round($totalHours / $completedServices->count(), 1);
    }

}
