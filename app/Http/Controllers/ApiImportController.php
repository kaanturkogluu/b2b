<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiImportController extends Controller
{
    /**
     * API'den veri çekme sayfasını göster
     */
    public function index()
    {
        return view('admin.api-import.index');
    }

    /**
     * API'den veri çek ve göster
     */
    public function fetchData(Request $request)
    {
        try {
            // API'den veri çek
            $response = Http::timeout(30)->get('https://motojetservis.com/motor/api.php');
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Veri kontrolü
                if (!is_array($data)) {
                    return back()->with('error', 'API\'den geçersiz veri formatı döndü.');
                }
                
                // Log'a kaydet
                Log::info('API verisi çekildi', [
                    'url' => 'https://motojetservis.com/motor/api.php',
                    'record_count' => count($data),
                    'status' => $response->status()
                ]);
                
                return view('admin.api-import.show', compact('data'));
            } else {
                return back()->with('error', 'API\'den veri çekilemedi. HTTP Status: ' . $response->status());
            }
            
        } catch (\Exception $e) {
            Log::error('API veri çekme hatası', [
                'error' => $e->getMessage(),
                'url' => 'https://motojetservis.com/motor/api.php'
            ]);
            
            return back()->with('error', 'API\'den veri çekilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * API verilerini bu projeye aktar
     */
    public function importData(Request $request)
    {
        try {
            // API'den veri çek
            $response = Http::timeout(30)->get('https://motojetservis.com/motor/api.php');
            
            if (!$response->successful()) {
                return back()->with('error', 'API\'den veri çekilemedi. HTTP Status: ' . $response->status());
            }
            
            $apiData = $response->json();
            
            // Veri kontrolü
            if (!is_array($apiData)) {
                return back()->with('error', 'API\'den geçersiz veri formatı döndü.');
            }
            
            $importedCount = 0;
            $errors = [];
            
            foreach ($apiData as $item) {
                try {
                    // Veri dönüşümü
                    $bakimData = $this->transformApiData($item);
                    
                    // Bakım kaydı oluştur
                    $bakim = \App\Models\Bakim::create($bakimData);
                    
                    // Parçaları ekle
                    if (isset($item['degisecek_parcalar']) && is_array($item['degisecek_parcalar'])) {
                        foreach ($item['degisecek_parcalar'] as $parca) {
                            \App\Models\DegisecekParca::create([
                                'bakim_id' => $bakim->id,
                                'parca_adi' => $parca['name'] ?? '',
                                'adet' => (int)($parca['quantity'] ?? 1),
                                'birim_fiyat' => (float)($parca['unit_price'] ?? 0),
                                'aciklama' => $parca['description'] ?? null
                            ]);
                        }
                    }
                    
                    $importedCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = "ID {$item['id']} kaydı aktarılamadı: " . $e->getMessage();
                }
            }
            
            $message = "Toplam {$importedCount} kayıt başarıyla aktarıldı.";
            if (!empty($errors)) {
                $message .= " Hata sayısı: " . count($errors);
            }
            
            return back()->with('success', $message)->with('errors', $errors);
            
        } catch (\Exception $e) {
            Log::error('API veri aktarım hatası', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Veri aktarımında hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * API verisini bu projenin formatına dönüştür
     */
    private function transformApiData($apiItem)
    {
        return [
            'plaka' => $apiItem['plaka'] ?? '',
            'sase' => $apiItem['sase'] ?? '',
            'tahmini_teslim_tarihi' => $apiItem['tahmini_teslim_tarihi'] ?? now(),
            'telefon_numarasi' => $apiItem['telefon_numarasi'] ?? '',
            'musteri_adi' => $apiItem['musteri_adi'] ?? '',
            'odeme_durumu' => (int)($apiItem['odeme_durumu'] ?? 0),
            'bakim_durumu' => $apiItem['bakim_durumu'] ?? 'Devam Ediyor',
            'ucret' => (float)($apiItem['ucret'] ?? 0),
            'iscilik_ucreti' => 0, // API'de işçilik ücreti ayrı değil, parça fiyatlarına dahil
            'genel_aciklama' => $apiItem['genel_aciklama'] ?? 'API\'den aktarıldı',
            'admin_id' => 1, // Admin kullanıcısı
            'bakim_tarihi' => $apiItem['bakim_tarihi'] ?? now(),
            'personel_id' => null,
            'created_at' => $apiItem['created_at'] ?? now(),
            'updated_at' => now()
        ];
    }
}