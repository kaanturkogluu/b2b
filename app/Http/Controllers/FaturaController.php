<?php

namespace App\Http\Controllers;

use App\Models\Bakim;
use Illuminate\Http\Request;

class FaturaController extends Controller
{
    public function generate(Bakim $bakim)
    {
        // Sadece tamamlanan servisler için fatura oluşturulabilir
        if ($bakim->bakim_durumu != 'Tamamlandı') {
            return back()->with('error', 'Sadece tamamlanan servisler için fatura oluşturulabilir.');
        }

        // Bakım bilgilerini parçaları ile birlikte getir
        $bakim->load(['admin', 'personel', 'degisecekParcalar']);

        // Fatura numarası oluştur (tarih + ID)
        $faturaNo = 'FTR-' . date('Ymd') . '-' . str_pad($bakim->id, 4, '0', STR_PAD_LEFT);

        // Fatura görünümünü döndür
        return view('admin.bakim.fatura', compact('bakim', 'faturaNo'));
    }

    public function view(Bakim $bakim)
    {
        // Sadece tamamlanan servisler için fatura görüntülenebilir
        if ($bakim->bakim_durumu != 'Tamamlandı') {
            return back()->with('error', 'Sadece tamamlanan servisler için fatura görüntülenebilir.');
        }

        // Bakım bilgilerini parçaları ile birlikte getir
        $bakim->load(['admin', 'personel', 'degisecekParcalar']);

        // Fatura numarası oluştur
        $faturaNo = 'FTR-' . date('Ymd') . '-' . str_pad($bakim->id, 4, '0', STR_PAD_LEFT);

        // Fatura görünümünü döndür
        return view('admin.bakim.fatura', compact('bakim', 'faturaNo'));
    }
}