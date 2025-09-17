<?php

namespace App\Http\Controllers;

use App\Models\Bakim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->get('status', 'all');
        $paymentStatus = $request->get('payment_status', 'all');

        // Base query
        $query = Bakim::with(['admin', 'personel', 'degisecekParcalar'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Apply filters
        if ($status !== 'all') {
            $query->where('bakim_durumu', $status);
        }

        if ($paymentStatus !== 'all') {
            $query->where('odeme_durumu', $paymentStatus);
        }

        $bakimlar = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $stats = $this->calculateStats($bakimlar);

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        return view('admin.reports.index', compact(
            'bakimlar', 
            'stats', 
            'filterOptions', 
            'startDate', 
            'endDate', 
            'status', 
            'paymentStatus'
        ));
    }

    /**
     * Generate detailed service report.
     */
    public function serviceReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $bakimlar = Bakim::with(['admin', 'personel', 'degisecekParcalar'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = $this->calculateStats($bakimlar);

        return view('admin.reports.service-report', compact('bakimlar', 'stats', 'startDate', 'endDate'));
    }

    /**
     * Generate financial report.
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $bakimlar = Bakim::with(['admin', 'personel'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $financialStats = $this->calculateFinancialStats($bakimlar);

        return view('admin.reports.financial-report', compact('bakimlar', 'financialStats', 'startDate', 'endDate'));
    }

    /**
     * Generate staff performance report.
     */
    public function staffReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $staffStats = DB::table('bakim')
            ->join('users', 'bakim.personel_id', '=', 'users.id')
            ->where('users.role', 'staff')
            ->whereBetween('bakim.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                'users.name',
                'users.username',
                DB::raw('COUNT(bakim.id) as total_services'),
                DB::raw('SUM(CASE WHEN bakim.bakim_durumu = "Tamamlandı" THEN 1 ELSE 0 END) as completed_services'),
                DB::raw('SUM(bakim.ucret) as total_revenue'),
                DB::raw('AVG(bakim.ucret) as avg_service_value')
            )
            ->groupBy('users.id', 'users.name', 'users.username')
            ->orderBy('total_services', 'desc')
            ->get();

        return view('admin.reports.staff-report', compact('staffStats', 'startDate', 'endDate'));
    }

    /**
     * Calculate general statistics.
     */
    private function calculateStats($bakimlar)
    {
        return [
            'total_services' => $bakimlar->count(),
            'completed_services' => $bakimlar->where('bakim_durumu', 'Tamamlandı')->count(),
            'ongoing_services' => $bakimlar->where('bakim_durumu', 'Devam Ediyor')->count(),
            'paid_services' => $bakimlar->where('odeme_durumu', 1)->count(),
            'unpaid_services' => $bakimlar->where('odeme_durumu', 0)->count(),
            'total_revenue' => $bakimlar->sum('ucret'),
            'avg_service_value' => $bakimlar->count() > 0 ? $bakimlar->avg('ucret') : 0,
            'completion_rate' => $bakimlar->count() > 0 ? 
                round(($bakimlar->where('bakim_durumu', 'Tamamlandı')->count() / $bakimlar->count()) * 100, 2) : 0,
            'payment_rate' => $bakimlar->count() > 0 ? 
                round(($bakimlar->where('odeme_durumu', 1)->count() / $bakimlar->count()) * 100, 2) : 0
        ];
    }

    /**
     * Calculate financial statistics.
     */
    private function calculateFinancialStats($bakimlar)
    {
        $totalRevenue = $bakimlar->sum('ucret');
        $paidRevenue = $bakimlar->where('odeme_durumu', 1)->sum('ucret');
        $unpaidRevenue = $bakimlar->where('odeme_durumu', 0)->sum('ucret');

        return [
            'total_revenue' => $totalRevenue,
            'paid_revenue' => $paidRevenue,
            'unpaid_revenue' => $unpaidRevenue,
            'payment_rate' => $totalRevenue > 0 ? round(($paidRevenue / $totalRevenue) * 100, 2) : 0,
            'avg_service_value' => $bakimlar->count() > 0 ? $bakimlar->avg('ucret') : 0,
            'highest_service_value' => $bakimlar->max('ucret'),
            'lowest_service_value' => $bakimlar->min('ucret'),
            'monthly_trend' => $this->getMonthlyTrend($bakimlar)
        ];
    }

    /**
     * Get monthly trend data.
     */
    private function getMonthlyTrend($bakimlar)
    {
        return $bakimlar->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('ucret')
            ];
        });
    }

    /**
     * Get filter options.
     */
    private function getFilterOptions()
    {
        return [
            'status_options' => [
                'all' => 'Tüm Durumlar',
                'Devam Ediyor' => 'Devam Ediyor',
                'Tamamlandı' => 'Tamamlandı'
            ],
            'payment_options' => [
                'all' => 'Tüm Ödemeler',
                '0' => 'Ödeme Bekliyor',
                '1' => 'Ödeme Alındı'
            ]
        ];
    }
}