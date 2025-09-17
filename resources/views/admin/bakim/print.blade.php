<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Bilgileri - {{ $bakim->plaka }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
            background: white;
        }
        
        .header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .company-info {
            float: left;
            width: 60%;
        }
        
        .invoice-info {
            float: right;
            width: 35%;
            text-align: right;
        }
        
        .logo-container {
            text-align: right;
            margin-bottom: 10px;
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

        .logo-image {
            max-height: 80px;
            max-width: 150px;
            object-fit: contain;
        }

        /* Print specific styles */
        @media print {
            body {
                margin: 0;
                padding: 15px;
                font-size: 11px;
            }
            
            .header {
                page-break-inside: avoid;
            }
            
            .parts-table {
                page-break-inside: avoid;
            }
            
            .total-section {
                page-break-inside: avoid;
            }
            
            .footer {
                page-break-inside: avoid;
            }
        }

        /* Print button styles */
        .print-controls {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .print-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }

        .print-btn:hover {
            background-color: #0056b3;
        }

        @media print {
            .print-controls {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Yazdır
        </button>
        <button class="print-btn" onclick="window.close()">
            <i class="fas fa-times"></i> Kapat
        </button>
    </div>

    <div class="header clearfix">
        <div class="company-info">
            <div class="logo">{{ $invoiceSettings->company_name }}</div>
            <div>Adres: {{ $invoiceSettings->address }}</div>
            <div>Şehir: {{ $invoiceSettings->city }}</div>
            <div>Tel: {{ $invoiceSettings->phone }}</div>
            <div>Email: {{ $invoiceSettings->email }}</div>
        </div>
        
        <div class="invoice-info">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Motojet Servis" class="logo-image">
            </div>
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


    @if($bakim->degisecekParcalar->count() > 0)
    <table class="parts-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 50%;">Parça Adı</th>
                <th style="width: 15%;" class="text-center">Adet</th>
                <th style="width: 15%;" class="text-right">Birim Fiyat</th>
                <th style="width: 15%;" class="text-right">Toplam</th>
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
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="total-section">
        <div class="total-row grand-total">
            <span class="total-label">TOPLAM TUTAR:</span>
            <span class="total-value">{{ number_format($bakim->ucret, 2) }} ₺</span>
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

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Optional: Auto print after 1 second
            // setTimeout(function() { window.print(); }, 1000);
        }
    </script>
</body>
</html>
