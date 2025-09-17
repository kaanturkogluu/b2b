<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raporlar - Motojet Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 1px solid #e9ecef;
        }
        
        .content {
            padding: 30px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 20px;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: #667eea;
            color: white;
            border: none;
            font-weight: 600;
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3">
                        <h4 class="text-white mb-4">
                            <i class="fas fa-chart-line me-2"></i>Raporlar
                        </h4>
                        <nav class="nav flex-column">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a class="nav-link" href="{{ route('bakim.index') }}">
                                <i class="fas fa-tools me-2"></i>Servis Yönetimi
                            </a>
                            
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a class="nav-link" href="{{ route('users.index') }}">
                                        <i class="fas fa-users me-2"></i>Kullanıcı Yönetimi
                                    </a>
                                    <a class="nav-link" href="{{ route('invoice-settings.index') }}">
                                        <i class="fas fa-file-invoice me-2"></i>Fatura Ayarları
                                    </a>
                                    <a class="nav-link active" href="{{ route('reports.index') }}">
                                        <i class="fas fa-chart-line me-2"></i>Raporlar
                                    </a>
                                @elseif(auth()->user()->role === 'staff')
                                    <a class="nav-link" href="{{ route('staff.bakim.index') }}">
                                        <i class="fas fa-list me-2"></i>Servislerim
                                    </a>
                                @endif
                            @endauth
                            
                            <hr class="text-white-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-white p-0 w-100 text-start" style="border: none; background: none;">
                                    <i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <div class="header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0">Raporlar</h2>
                                <p class="text-muted mb-0">Servis istatistikleri ve analizler</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary">Admin</span>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                <!-- Filter Card -->
                <div class="filter-card">
                    <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filtreler</h5>
                    <form method="GET" action="{{ route('reports.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Bitiş Tarihi</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Servis Durumu</label>
                                <select class="form-select" id="status" name="status">
                                    @foreach($filterOptions['status_options'] as $value => $label)
                                        <option value="{{ $value }}" {{ $status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="payment_status" class="form-label">Ödeme Durumu</label>
                                <select class="form-select" id="payment_status" name="payment_status">
                                    @foreach($filterOptions['payment_options'] as $value => $label)
                                        <option value="{{ $value }}" {{ $paymentStatus == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-2"></i>Filtrele
                                </button>
                                <a href="{{ route('reports.service-report', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="btn btn-outline-primary me-2" target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i>Servis Raporu
                                </a>
                                <a href="{{ route('reports.financial-report', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="btn btn-outline-success me-2" target="_blank">
                                    <i class="fas fa-chart-bar me-2"></i>Mali Rapor
                                </a>
                                <a href="{{ route('reports.staff-report', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="btn btn-outline-info" target="_blank">
                                    <i class="fas fa-users me-2"></i>Personel Raporu
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ $stats['total_services'] }}</div>
                            <div class="stats-label">Toplam Servis</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ $stats['completed_services'] }}</div>
                            <div class="stats-label">Tamamlanan</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ number_format($stats['total_revenue'], 2) }} ₺</div>
                            <div class="stats-label">Toplam Gelir</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ $stats['completion_rate'] }}%</div>
                            <div class="stats-label">Tamamlanma Oranı</div>
                        </div>
                    </div>
                </div>

                <!-- Services Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Servis Listesi ({{ $bakimlar->count() }} kayıt)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($bakimlar->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bakimlar as $bakim)
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
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Seçilen kriterlere uygun servis bulunamadı</h5>
                                <p class="text-muted">Farklı tarih aralığı veya filtre seçenekleri deneyin.</p>
                            </div>
                        @endif
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
