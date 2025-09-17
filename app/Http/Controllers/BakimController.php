<?php

namespace App\Http\Controllers;

use App\Models\Bakim;
use App\Models\DegisecekParca;
use App\Models\User;
use App\Models\InvoiceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BakimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bakimlar = Bakim::with(['admin', 'personel', 'degisecekParcalar'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.bakim.index', compact('bakimlar'));
    }

    /**
     * Display a listing of the resource for staff.
     */
    public function staffIndex()
    {
        $bakimlar = Bakim::with(['admin', 'personel', 'degisecekParcalar'])
            ->where('personel_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('staff.bakim.index', compact('bakimlar'));
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
                'personel_id' => Auth::id() // Başlangıçta admin atanır, tamamlandığında değişir
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

}
