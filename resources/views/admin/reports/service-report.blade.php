<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Raporu - Motojet Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        
        .report-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 10px;
        }
        
        .report-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .report-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .table thead th {
            background-color: #667eea;
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 6px 12px;
        }
        
        .badge-devam {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-tamamlandi {
            background-color: #28a745;
            color: white;
        }
        
        .badge-odeme-bekliyor {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-odeme-alindi {
            background-color: #28a745;
            color: white;
        }
        
        .print-controls {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        @media print {
            .print-controls {
                display: none;
            }
            
            body {
                background: white;
            }
            
            .report-header {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="print-controls">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Yazdır
            </button>
            <button class="btn-print" onclick="window.close()">
                <i class="fas fa-times"></i> Kapat
            </button>
        </div>

        <div class="report-header">
            <div class="report-title">Servis Raporu</div>
            <div class="report-subtitle">
                Tarih Aralığı: {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_services'] }}</div>
                <div class="stat-label">Toplam Servis</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['completed_services'] }}</div>
                <div class="stat-label">Tamamlanan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['ongoing_services'] }}</div>
                <div class="stat-label">Devam Eden</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['paid_services'] }}</div>
                <div class="stat-label">Ödeme Alınan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($stats['total_revenue'], 2) }} ₺</div>
                <div class="stat-label">Toplam Gelir</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['completion_rate'] }}%</div>
                <div class="stat-label">Tamamlanma Oranı</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Müşteri</th>
                        <th>Plaka</th>
                        <th>Servis Durumu</th>
                        <th>Ödeme Durumu</th>
                        <th>Tutar</th>
                        <th>Tarih</th>
                        <th>Personel</th>
                        <th>Parça Sayısı</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bakimlar as $bakim)
                    <tr>
                        <td>{{ $bakim->id }}</td>
                        <td>
                            <strong>{{ $bakim->musteri_adi }}</strong><br>
                            <small class="text-muted">{{ $bakim->telefon_numarasi }}</small>
                        </td>
                        <td>{{ $bakim->plaka }}</td>
                        <td>
                            @if($bakim->bakim_durumu == 'Devam Ediyor')
                                <span class="badge badge-devam">Devam Ediyor</span>
                            @else
                                <span class="badge badge-tamamlandi">Tamamlandı</span>
                            @endif
                        </td>
                        <td>
                            @if($bakim->odeme_durumu == 0)
                                <span class="badge badge-odeme-bekliyor">Ödeme Bekliyor</span>
                            @else
                                <span class="badge badge-odeme-alindi">Ödeme Alındı</span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($bakim->ucret, 2) }} ₺</strong></td>
                        <td>{{ $bakim->bakim_tarihi->format('d.m.Y') }}</td>
                        <td>{{ $bakim->personel->name ?? '-' }}</td>
                        <td>{{ $bakim->degisecekParcalar->count() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i><br>
                            <span class="text-muted">Bu tarih aralığında servis bulunamadı</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bakimlar->count() > 0)
        <div class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="stat-card">
                        <h6>Ortalama Servis Değeri</h6>
                        <div class="stat-number">{{ number_format($stats['avg_service_value'], 2) }} ₺</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card">
                        <h6>Ödeme Oranı</h6>
                        <div class="stat-number">{{ $stats['payment_rate'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
