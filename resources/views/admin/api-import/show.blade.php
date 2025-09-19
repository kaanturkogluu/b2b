<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Veri Önizleme - MotoJet Servis</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .main-content {
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }
        .badge-devam {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }
        .badge-tamamlandi {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .badge-odeme-bekliyor {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        .badge-odeme-alindi {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .json-viewer {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            max-height: 400px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-motorcycle me-2"></i>
                        MotoJet Servis
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('bakim.index') }}">
                            <i class="fas fa-tools me-2"></i>
                            Servis Yönetimi
                        </a>
                        <a class="nav-link active" href="{{ route('api-import.index') }}">
                            <i class="fas fa-download me-2"></i>
                            API Veri Aktarımı
                        </a>
                        
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a class="nav-link" href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-2"></i>
                                    Kullanıcı Yönetimi
                                </a>
                                <a class="nav-link" href="{{ route('reports.index') }}">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Raporlar
                                </a>
                                <a class="nav-link" href="{{ route('invoice-settings.index') }}">
                                    <i class="fas fa-file-invoice me-2"></i>
                                    Fatura Ayarları
                                </a>
                            @elseif(auth()->user()->role === 'staff')
                                <a class="nav-link" href="{{ route('staff.bakim.index') }}">
                                    <i class="fas fa-list me-2"></i>
                                    Servislerim
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Header -->
                    <div class="header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">API Veri Önizleme</h2>
                            <p class="text-muted mb-0">API'den gelen veriler ({{ is_array($data) ? count($data) : 0 }} kayıt)</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('api-import.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Geri Dön
                            </a>
                            <form method="POST" action="{{ route('api-import.import') }}" class="d-inline" 
                                  onsubmit="return confirm('Tüm API verilerini bu sisteme aktarmak istediğinizden emin misiniz? Bu işlem geri alınamaz!')">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>
                                    Veri Aktar
                                </button>
                            </form>
                            <span class="badge bg-primary me-3">Admin</span>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Çıkış Yap
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- İstatistikler -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">{{ is_array($data) ? count($data) : 0 }}</h5>
                                    <p class="card-text">Toplam Kayıt</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-success">{{ is_array($data) ? collect($data)->where('bakim_durumu', 'Tamamlandı')->count() : 0 }}</h5>
                                    <p class="card-text">Tamamlanan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">{{ is_array($data) ? collect($data)->where('bakim_durumu', 'Devam Ediyor')->count() : 0 }}</h5>
                                    <p class="card-text">Devam Eden</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-info">{{ is_array($data) ? number_format(collect($data)->sum('ucret'), 2) : '0.00' }} ₺</h5>
                                    <p class="card-text">Toplam Ücret</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Veri Tablosu -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>
                                API Verileri
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Plaka</th>
                                            <th>Müşteri</th>
                                            <th>Telefon</th>
                                            <th>Durum</th>
                                            <th>Ödeme</th>
                                            <th>Ücret</th>
                                            <th>Parça Sayısı</th>
                                            <th>Tarih</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(is_array($data))
                                            @foreach($data as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td><strong>{{ $item['plaka'] }}</strong></td>
                                            <td>{{ $item['musteri_adi'] }}</td>
                                            <td>{{ $item['telefon_numarasi'] }}</td>
                                            <td>
                                                @if($item['bakim_durumu'] == 'Tamamlandı')
                                                    <span class="badge badge-tamamlandi">{{ $item['bakim_durumu'] }}</span>
                                                @else
                                                    <span class="badge badge-devam">{{ $item['bakim_durumu'] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item['odeme_durumu'] == 1)
                                                    <span class="badge badge-odeme-alindi">Ödeme Alındı</span>
                                                @else
                                                    <span class="badge badge-odeme-bekliyor">Ödeme Bekliyor</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ number_format($item['ucret'], 2) }} ₺</strong></td>
                                            <td>
                                                @if(isset($item['degisecek_parcalar']) && is_array($item['degisecek_parcalar']))
                                                    {{ count($item['degisecek_parcalar']) }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailModal{{ $item['id'] }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Detay Modal -->
                                        <div class="modal fade" id="detailModal{{ $item['id'] }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Kayıt Detayı - ID: {{ $item['id'] }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Araç Bilgileri</h6>
                                                                <p><strong>Plaka:</strong> {{ $item['plaka'] }}</p>
                                                                <p><strong>Şase:</strong> {{ $item['sase'] }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Müşteri Bilgileri</h6>
                                                                <p><strong>Ad:</strong> {{ $item['musteri_adi'] }}</p>
                                                                <p><strong>Telefon:</strong> {{ $item['telefon_numarasi'] }}</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Servis Bilgileri</h6>
                                                                <p><strong>Durum:</strong> {{ $item['bakim_durumu'] }}</p>
                                                                <p><strong>Ödeme:</strong> {{ $item['odeme_durumu'] ? 'Alındı' : 'Bekliyor' }}</p>
                                                                <p><strong>Ücret:</strong> {{ number_format($item['ucret'], 2) }} ₺</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Tarihler</h6>
                                                                <p><strong>Bakım:</strong> {{ \Carbon\Carbon::parse($item['bakim_tarihi'])->format('d.m.Y') }}</p>
                                                                <p><strong>Teslim:</strong> {{ \Carbon\Carbon::parse($item['tahmini_teslim_tarihi'])->format('d.m.Y') }}</p>
                                                                <p><strong>Oluşturulma:</strong> {{ \Carbon\Carbon::parse($item['created_at'])->format('d.m.Y H:i') }}</p>
                                                            </div>
                                                        </div>

                                                        @if(isset($item['degisecek_parcalar']) && is_array($item['degisecek_parcalar']) && count($item['degisecek_parcalar']) > 0)
                                                        <hr>
                                                        <h6>Değişecek Parçalar</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Parça Adı</th>
                                                                        <th>Adet</th>
                                                                        <th>Birim Fiyat</th>
                                                                        <th>Açıklama</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($item['degisecek_parcalar'] as $parca)
                                                                    <tr>
                                                                        <td>{{ $parca['name'] ?? '' }}</td>
                                                                        <td>{{ $parca['quantity'] ?? 1 }}</td>
                                                                        <td>{{ number_format($parca['unit_price'] ?? 0, 2) }} ₺</td>
                                                                        <td>{{ $parca['description'] ?? '-' }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @endif

                                                        @if(!empty($item['genel_aciklama']))
                                                        <hr>
                                                        <h6>Genel Açıklama</h6>
                                                        <p>{{ $item['genel_aciklama'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center text-muted">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    Veri bulunamadı veya geçersiz format
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- JSON Görüntüleme -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-code me-2"></i>
                                Ham JSON Verisi
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="json-viewer">
                                <pre>{{ is_array($data) ? json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'Veri bulunamadı' }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
