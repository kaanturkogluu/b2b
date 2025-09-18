<?php

namespace App\Http\Controllers;

use App\Models\Bakim;
use App\Models\DegisecekParca;
use App\Models\User;
use App\Models\InvoiceSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BakimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cache key oluştur
        $cacheKey = 'bakim_index_' . md5(serialize($request->all()));
        
        // Cache'den veri al veya veritabanından çek
        $bakimlar = cache()->remember($cacheKey, 300, function() use ($request) { // 5 dakika cache
            $query = Bakim::select([
                'id', 'plaka', 'sase', 'musteri_adi', 'telefon_numarasi', 
                'bakim_durumu', 'odeme_durumu', 'ucret', 'genel_aciklama',
                'admin_id', 'personel_id', 'tamamlayan_personel_id', 
                'bakim_tarihi', 'tahmini_teslim_tarihi', 'created_at'
            ])
            ->with([
                'admin:id,name,username',
                'personel:id,name,username',
                'tamamlayanPersonel:id,name,username',
                'degisecekParcalar:id,bakim_id,parca_adi,adet,birim_fiyat'
            ]);
            
            // Arama filtresi - Full-text search için optimize edildi
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('plaka', 'like', "{$search}%") // Prefix search daha hızlı
                      ->orWhere('musteri_adi', 'like', "%{$search}%")
                      ->orWhere('telefon_numarasi', 'like', "{$search}%")
                      ->orWhere('sase', 'like', "{$search}%");
                });
            }
            
            // Filtreler - Index kullanımı için optimize edildi
            if ($request->filled('bakim_durumu')) {
                $query->where('bakim_durumu', $request->bakim_durumu);
            }
            
            if ($request->filled('odeme_durumu')) {
                $query->where('odeme_durumu', $request->odeme_durumu);
            }
            
            if ($request->filled('personel_id')) {
                $query->where('personel_id', $request->personel_id);
            }
            
            // Tarih aralığı - Index kullanımı için optimize edildi
            if ($request->filled('start_date')) {
                $query->where('bakim_tarihi', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->where('bakim_tarihi', '<=', $request->end_date);
            }
            
            // Ücret aralığı - Index kullanımı için optimize edildi
            if ($request->filled('min_ucret')) {
                $query->where('ucret', '>=', $request->min_ucret);
            }
            
            if ($request->filled('max_ucret')) {
                $query->where('ucret', '<=', $request->max_ucret);
            }
            
            // Sıralama - Index kullanımı için optimize edildi
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSortFields = ['created_at', 'bakim_tarihi', 'ucret', 'plaka', 'musteri_adi'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            } else {
                $query->orderBy('created_at', 'desc');
            }
            
            return $query->paginate(10)->appends($request->query());
        });
        
        // Personelleri cache'le
        $personeller = cache()->remember('active_personeller', 3600, function() { // 1 saat cache
            return User::select('id', 'name', 'username')
                      ->where('role', 'personel')
                      ->where('is_active', true)
                      ->orderBy('name')
                      ->get();
        });
        
        $filterOptions = $this->getFilterOptions();
            
        return view('admin.bakim.index', compact('bakimlar', 'personeller', 'filterOptions'));
    }

    /**
     * Display a listing of the resource for staff.
     */
    public function staffIndex(Request $request)
    {
        $user = Auth::user();
        
        // Cache key oluştur
        $cacheKey = 'staff_bakim_index_' . $user->id . '_' . md5(serialize($request->all()));
        
        // Cache'den veri al veya veritabanından çek
        $bakimlar = cache()->remember($cacheKey, 300, function() use ($request, $user) { // 5 dakika cache
            $query = Bakim::select([
                'id', 'plaka', 'sase', 'musteri_adi', 'telefon_numarasi', 
                'bakim_durumu', 'odeme_durumu', 'ucret', 'genel_aciklama',
                'admin_id', 'personel_id', 'tamamlayan_personel_id', 
                'bakim_tarihi', 'tahmini_teslim_tarihi', 'created_at'
            ])
            ->with([
                'admin:id,name,username',
                'personel:id,name,username',
                'tamamlayanPersonel:id,name,username',
                'degisecekParcalar:id,bakim_id,parca_adi,adet,birim_fiyat'
            ])
            ->where('personel_id', $user->id);
            
            // Arama filtresi - Full-text search için optimize edildi
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('plaka', 'like', "{$search}%") // Prefix search daha hızlı
                      ->orWhere('musteri_adi', 'like', "%{$search}%")
                      ->orWhere('telefon_numarasi', 'like', "{$search}%")
                      ->orWhere('sase', 'like', "{$search}%");
                });
            }
            
            // Filtreler - Index kullanımı için optimize edildi
            if ($request->filled('bakim_durumu')) {
                $query->where('bakim_durumu', $request->bakim_durumu);
            }
            
            if ($request->filled('odeme_durumu')) {
                $query->where('odeme_durumu', $request->odeme_durumu);
            }
            
            // Tarih aralığı - Index kullanımı için optimize edildi
            if ($request->filled('start_date')) {
                $query->where('bakim_tarihi', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->where('bakim_tarihi', '<=', $request->end_date);
            }
            
            // Sıralama - Index kullanımı için optimize edildi
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSortFields = ['created_at', 'bakim_tarihi', 'ucret', 'plaka', 'musteri_adi'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            } else {
                $query->orderBy('created_at', 'desc');
            }
            
            return $query->paginate(10)->appends($request->query());
        });
        
        $filterOptions = $this->getFilterOptions();
            
        return view('staff.bakim.index', compact('bakimlar', 'filterOptions'));
    }

    /**
     * Display the specified resource for staff.
     */
    public function staffShow(Bakim $bakim)
    {
        $bakim->load(['admin', 'personel', 'tamamlayanPersonel', 'degisecekParcalar']);
        
        return view('staff.bakim.show', compact('bakim'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bakim.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Bakim store method çağrıldı', ['request_data' => $request->all()]);
        $request->validate([
            'plaka' => 'required|string|max:255',
            'sase' => 'required|string|max:255',
            'tahmini_teslim_tarihi' => 'required|date',
            'telefon_numarasi' => 'required|string|max:255',
            'musteri_adi' => 'required|string|max:255',
            'ucret' => 'required|numeric|min:0',
            'genel_aciklama' => 'nullable|string|max:255',
            'bakim_tarihi' => 'required|date',
            'parcalar' => 'nullable|array',
            'parcalar.*.parca_adi' => 'required_with:parcalar|string|max:255',
            'parcalar.*.adet' => 'required_with:parcalar|integer|min:1',
            'parcalar.*.birim_fiyat' => 'required_with:parcalar|numeric|min:0',
            'parcalar.*.aciklama' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $bakim = Bakim::create([
                'plaka' => $request->plaka,
                'sase' => $request->sase,
                'tahmini_teslim_tarihi' => $request->tahmini_teslim_tarihi,
                'telefon_numarasi' => $request->telefon_numarasi,
                'musteri_adi' => $request->musteri_adi,
                'odeme_durumu' => 0,
                'bakim_durumu' => 'Devam Ediyor',
                'ucret' => $request->ucret,
                'genel_aciklama' => $request->genel_aciklama ?? 'Beklemede',
                'admin_id' => Auth::id(),
                'bakim_tarihi' => $request->bakim_tarihi,
                'personel_id' => $request->personel_id ?? null
            ]);

            if ($request->has('parcalar')) {
                foreach ($request->parcalar as $parca) {
                    // Sadece parça adı ve adet dolu olanları kaydet (birim fiyat 0 olabilir)
                    if (!empty($parca['parca_adi']) && 
                        !empty($parca['adet']) && 
                        $parca['adet'] > 0) {
                        DegisecekParca::create([
                            'bakim_id' => $bakim->id,
                            'parca_adi' => trim($parca['parca_adi']),
                            'adet' => (int)$parca['adet'],
                            'birim_fiyat' => (float)($parca['birim_fiyat'] ?? 0),
                            'aciklama' => !empty($parca['aciklama']) ? trim($parca['aciklama']) : null
                        ]);
                    }
                }
            }

            // Activity log
            ActivityLog::log(
                'bakim_created',
                "Yeni bakım kaydı oluşturuldu: {$bakim->plaka} - {$bakim->musteri_adi}",
                Auth::id(),
                $bakim->id,
                'App\Models\Bakim',
                [
                    'plaka' => $bakim->plaka,
                    'musteri_adi' => $bakim->musteri_adi,
                    'ucret' => $bakim->ucret
                ]
            );

            // Cache'i temizle
            $this->clearBakimCache();

            DB::commit();
            return redirect()->route('bakim.index')->with('success', 'Bakım kaydı başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Bakım kaydı oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bakim $bakim)
    {
        $bakim->load(['admin', 'personel', 'degisecekParcalar']);
        return view('admin.bakim.show', compact('bakim'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bakim $bakim)
    {
        $personeller = User::where('role', 'staff')->get();
        $bakim->load('degisecekParcalar');
        return view('admin.bakim.edit', compact('bakim', 'personeller'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bakim $bakim)
    {
        // Tamamlandı durumunda güncelleme yapılamaz
        if ($bakim->bakim_durumu == 'Tamamlandı') {
            return back()->with('error', 'Tamamlandı olarak işaretlenmiş bakımlar değiştirilemez.');
        }
        
        $request->validate([
            'plaka' => 'required|string|max:255',
            'sase' => 'required|string|max:255',
            'tahmini_teslim_tarihi' => 'required|date',
            'telefon_numarasi' => 'required|string|max:255',
            'musteri_adi' => 'required|string|max:255',
            'odeme_durumu' => 'required|integer|in:0,1',
            'bakim_durumu' => 'required|in:Devam Ediyor,Tamamlandı',
            'ucret' => 'required|numeric|min:0',
            'genel_aciklama' => 'nullable|string|max:255',
            'bakim_tarihi' => 'required|date',
            'personel_id' => 'nullable|exists:users,id',
            'parcalar' => 'nullable|array',
            'parcalar.*.parca_adi' => 'required_with:parcalar|string|max:255',
            'parcalar.*.adet' => 'required_with:parcalar|integer|min:1',
            'parcalar.*.birim_fiyat' => 'required_with:parcalar|numeric|min:0',
            'parcalar.*.aciklama' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Eğer bakım tamamlandıysa ve personel_id verilmişse, personel ataması yap
            $personelId = $bakim->personel_id;
            if ($request->bakim_durumu == 'Tamamlandı' && $request->personel_id) {
                $personelId = $request->personel_id;
            }

            $bakim->update([
                'plaka' => $request->plaka,
                'sase' => $request->sase,
                'tahmini_teslim_tarihi' => $request->tahmini_teslim_tarihi,
                'telefon_numarasi' => $request->telefon_numarasi,
                'musteri_adi' => $request->musteri_adi,
                'odeme_durumu' => $request->odeme_durumu,
                'bakim_durumu' => $request->bakim_durumu,
                'ucret' => $request->ucret,
                'genel_aciklama' => $request->genel_aciklama,
                'bakim_tarihi' => $request->bakim_tarihi,
                'personel_id' => $personelId
            ]);

            // Parçaları güncelle (mevcut parçaları koru, sadece güncelle veya yeni ekle)
            if ($request->has('parcalar')) {
                $existingParcalar = $bakim->degisecekParcalar->keyBy('id');
                $submittedParcalar = collect($request->parcalar);
                
                // Mevcut parçaları güncelle veya yeni ekle
                foreach ($submittedParcalar as $index => $parca) {
                    // Sadece parça adı ve adet dolu olanları işle
                    if (!empty($parca['parca_adi']) && 
                        !empty($parca['adet']) && 
                        $parca['adet'] > 0) {
                        
                        $parcaData = [
                            'parca_adi' => trim($parca['parca_adi']),
                            'adet' => (int)$parca['adet'],
                            'birim_fiyat' => (float)($parca['birim_fiyat'] ?? 0),
                            'aciklama' => !empty($parca['aciklama']) ? trim($parca['aciklama']) : null
                        ];
                        
                        // Eğer parça ID'si varsa güncelle, yoksa yeni oluştur
                        if (isset($parca['id']) && $existingParcalar->has($parca['id'])) {
                            $existingParcalar[$parca['id']]->update($parcaData);
                        } else {
                            DegisecekParca::create(array_merge($parcaData, ['bakim_id' => $bakim->id]));
                        }
                    }
                }
                
                // Not: Parçalar silinmez, sadece güncellenir veya yeni eklenir
                // Bu sayede ödeme alındıktan sonra da parça bilgileri korunur
            }

            DB::commit();
            return redirect()->route('bakim.index')->with('success', 'Bakım kaydı başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Bakım kaydı güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Print the specified resource.
     */
    public function print(Bakim $bakim)
    {
        $bakim->load(['admin', 'personel', 'degisecekParcalar']);
        
        // Fatura ayarlarını al
        $invoiceSettings = InvoiceSetting::getSettings();
        
        return view('admin.bakim.print', compact('bakim', 'invoiceSettings'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bakim $bakim)
    {
        try {
            $bakim->delete();
            return redirect()->route('bakim.index')->with('success', 'Bakım kaydı başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Bakım kaydı silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Export filtered data to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Bakim::with(['admin', 'personel', 'degisecekParcalar']);
        
        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('plaka', 'like', "%{$search}%")
                  ->orWhere('musteri_adi', 'like', "%{$search}%")
                  ->orWhere('telefon_numarasi', 'like', "%{$search}%")
                  ->orWhere('sase', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('bakim_durumu')) {
            $query->where('bakim_durumu', $request->bakim_durumu);
        }
        
        if ($request->filled('odeme_durumu')) {
            $query->where('odeme_durumu', $request->odeme_durumu);
        }
        
        if ($request->filled('personel_id')) {
            $query->where('personel_id', $request->personel_id);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('bakim_tarihi', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('bakim_tarihi', '<=', $request->end_date);
        }
        
        if ($request->filled('min_ucret')) {
            $query->where('ucret', '>=', $request->min_ucret);
        }
        
        if ($request->filled('max_ucret')) {
            $query->where('ucret', '<=', $request->max_ucret);
        }
        
        $bakimlar = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'bakim_listesi_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($bakimlar) {
            $file = fopen('php://output', 'w');
            
            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'ID',
                'Plaka',
                'Şase',
                'Müşteri Adı',
                'Telefon',
                'Bakım Durumu',
                'Ödeme Durumu',
                'Ücret (₺)',
                'Personel',
                'Bakım Tarihi',
                'Tahmini Teslim',
                'Genel Açıklama',
                'Oluşturulma Tarihi'
            ]);
            
            // Data
            foreach ($bakimlar as $bakim) {
                fputcsv($file, [
                    $bakim->id,
                    $bakim->plaka,
                    $bakim->sase,
                    $bakim->musteri_adi,
                    $bakim->telefon_numarasi,
                    $bakim->bakim_durumu,
                    $bakim->odeme_durumu ? 'Ödeme Alındı' : 'Ödeme Bekliyor',
                    number_format($bakim->ucret, 2),
                    $bakim->personel->name ?? '-',
                    $bakim->bakim_tarihi->format('d.m.Y'),
                    $bakim->tahmini_teslim_tarihi->format('d.m.Y'),
                    $bakim->genel_aciklama,
                    $bakim->created_at->format('d.m.Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear bakım related cache
     */
    private function clearBakimCache()
    {
        // Bakım listesi cache'lerini temizle
        cache()->forget('active_personeller');
        
        // Pattern ile cache temizleme
        $keys = cache()->getRedis()->keys('*bakim_index*');
        foreach ($keys as $key) {
            cache()->forget(str_replace(config('cache.prefix') . ':', '', $key));
        }
        
        $keys = cache()->getRedis()->keys('*staff_bakim_index*');
        foreach ($keys as $key) {
            cache()->forget(str_replace(config('cache.prefix') . ':', '', $key));
        }
        
        $keys = cache()->getRedis()->keys('*staff_dashboard*');
        foreach ($keys as $key) {
            cache()->forget(str_replace(config('cache.prefix') . ':', '', $key));
        }
    }

    /**
     * Get filter options for views
     */
    private function getFilterOptions()
    {
        return [
            'bakim_durumu_options' => [
                '' => 'Tüm Durumlar',
                'Devam Ediyor' => 'Devam Ediyor',
                'Tamamlandı' => 'Tamamlandı'
            ],
            'odeme_durumu_options' => [
                '' => 'Tüm Ödeme Durumları',
                '0' => 'Ödeme Bekliyor',
                '1' => 'Ödeme Alındı'
            ],
            'sort_options' => [
                'created_at' => 'Oluşturulma Tarihi',
                'bakim_tarihi' => 'Bakım Tarihi',
                'ucret' => 'Ücret',
                'plaka' => 'Plaka',
                'musteri_adi' => 'Müşteri Adı'
            ],
            'sort_order_options' => [
                'desc' => 'Azalan',
                'asc' => 'Artan'
            ]
        ];
    }

}
