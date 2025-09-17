<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura - {{ $faturaNo }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .company-info {
            float: left;
            width: 50%;
        }
        
        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            color: #666;
        }
        
        .customer-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .customer-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .service-info {
            margin: 20px 0;
        }
        
        .service-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .parts-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .parts-table th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .parts-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .parts-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        
        .total-row {
            margin: 5px 0;
        }
        
        .total-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        
        .total-value {
            display: inline-block;
            width: 100px;
            text-align: right;
        }
        
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        
        .payment-info {
            background-color: #e7f3ff;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .logo {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div class="company-info">
            <div class="logo">MOTOJET SERVİS</div>
            <div>Adres: Servis Mahallesi, Teknik Cad. No:123</div>
            <div>Şehir: İstanbul, Türkiye</div>
            <div>Tel: +90 212 555 0123</div>
            <div>Email: info@motojetservis.com</div>
        </div>
        
        <div class="invoice-info">
            <div class="invoice-title">FATURA</div>
            <div class="invoice-number">Fatura No: {{ $faturaNo }}</div>
            <div class="invoice-date">Tarih: {{ date('d.m.Y', strtotime($bakim->created_at)) }}</div>
            <div class="invoice-date">Servis Tarihi: {{ $bakim->bakim_tarihi->format('d.m.Y H:i') }}</div>
        </div>
    </div>

    <div class="customer-info">
        <div class="customer-title">MÜŞTERİ BİLGİLERİ</div>
        <div><strong>Müşteri Adı:</strong> {{ $bakim->musteri_adi }}</div>
        <div><strong>Telefon:</strong> {{ $bakim->telefon_numarasi }}</div>
        <div><strong>Plaka:</strong> {{ $bakim->plaka }}</div>
        <div><strong>Şase No:</strong> {{ $bakim->sase }}</div>
    </div>

    <div class="service-info">
        <div class="service-title">SERVİS BİLGİLERİ</div>
        <div><strong>Servis Durumu:</strong> {{ $bakim->bakim_durumu }}</div>
        <div><strong>Ödeme Durumu:</strong> {{ $bakim->odeme_durumu ? 'Ödeme Alındı' : 'Ödeme Bekliyor' }}</div>
        @if($bakim->personel)
        <div><strong>Sorumlu Personel:</strong> {{ $bakim->personel->name }}</div>
        @endif
        @if($bakim->genel_aciklama && $bakim->genel_aciklama != 'Beklemede')
        <div><strong>Açıklama:</strong> {{ $bakim->genel_aciklama }}</div>
        @endif
    </div>

    @if($bakim->degisecekParcalar->count() > 0)
    <table class="parts-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 40%;">Parça Adı</th>
                <th style="width: 10%;" class="text-center">Adet</th>
                <th style="width: 15%;" class="text-right">Birim Fiyat</th>
                <th style="width: 15%;" class="text-right">Toplam</th>
                <th style="width: 15%;">Açıklama</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bakim->degisecekParcalar as $index => $parca)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $parca->parca_adi }}</td>
                <td class="text-center">{{ $parca->adet }}</td>
                <td class="text-right">{{ number_format($parca->birim_fiyat, 2) }} ₺</td>
                <td class="text-right">{{ number_format($parca->adet * $parca->birim_fiyat, 2) }} ₺</td>
                <td>{{ $parca->aciklama ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Ara Toplam:</span>
            <span class="total-value">{{ number_format($bakim->ucret, 2) }} ₺</span>
        </div>
        <div class="total-row">
            <span class="total-label">KDV (%18):</span>
            <span class="total-value">{{ number_format($bakim->ucret * 0.18, 2) }} ₺</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">GENEL TOPLAM:</span>
            <span class="total-value">{{ number_format($bakim->ucret * 1.18, 2) }} ₺</span>
        </div>
    </div>

    @if($bakim->odeme_durumu)
    <div class="payment-info">
        <strong>✓ ÖDEME ALINMIŞTIR</strong><br>
        Ödeme Tarihi: {{ $bakim->updated_at->format('d.m.Y H:i') }}
    </div>
    @else
    <div class="payment-info">
        <strong>⚠ ÖDEME BEKLİYOR</strong><br>
        Lütfen ödeme yapınız.
    </div>
    @endif

    <div class="footer">
        <div class="text-center">
            <strong>Teşekkür ederiz!</strong><br>
            Motojet Servis - Güvenilir Teknik Servis Hizmetleri<br>
            Bu fatura elektronik ortamda oluşturulmuştur.
        </div>
    </div>
</body>
</html>
