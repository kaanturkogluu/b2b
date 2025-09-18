<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Detayı - MotoJet Servis</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
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
        .info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #28a745;
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
                        <a class="nav-link" href="{{ route('staff.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Ana Sayfa
                        </a>
                        <a class="nav-link" href="{{ route('staff.bakim.index') }}">
                            <i class="fas fa-cogs me-2"></i>
                            Bakım Listesi
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Header -->
                    <div class="header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Servis Detayı</h2>
                            <p class="text-muted mb-0">Servis #{{ $bakim->id }} - {{ $bakim->plaka }}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-3">Personel</span>
                            <a href="{{ route('staff.bakim.index') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                Geri Dön
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Çıkış Yap
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Servis Bilgileri -->
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Servis Bilgileri
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-card p-3 mb-3">
                                                <h6 class="text-primary mb-2">
                                                    <i class="fas fa-car me-2"></i>
                                                    Araç Bilgileri
                                                </h6>
                                                <p class="mb-1"><strong>Plaka:</strong> {{ $bakim->plaka }}</p>
                                                <p class="mb-0"><strong>Şase No:</strong> {{ $bakim->sase }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-card p-3 mb-3">
                                                <h6 class="text-primary mb-2">
                                                    <i class="fas fa-user me-2"></i>
                                                    Müşteri Bilgileri
                                                </h6>
                                                <p class="mb-1"><strong>Ad Soyad:</strong> {{ $bakim->musteri_adi }}</p>
                                                <p class="mb-0"><strong>Telefon:</strong> {{ $bakim->telefon_numarasi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-card p-3 mb-3">
                                                <h6 class="text-primary mb-2">
                                                    <i class="fas fa-calendar me-2"></i>
                                                    Tarih Bilgileri
                                                </h6>
                                                <p class="mb-1"><strong>Bakım Tarihi:</strong> {{ $bakim->bakim_tarihi->format('d.m.Y H:i') }}</p>
                                                <p class="mb-0"><strong>Tahmini Teslim:</strong> {{ $bakim->tahmini_teslim_tarihi->format('d.m.Y H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-card p-3 mb-3">
                                                <h6 class="text-primary mb-2">
                                                    <i class="fas fa-money-bill me-2"></i>
                                                    Ücret Bilgileri
                                                </h6>
                                                <p class="mb-1"><strong>Toplam Ücret:</strong> {{ number_format($bakim->ucret, 2) }} ₺</p>
                                                <p class="mb-0">
                                                    <strong>Ödeme Durumu:</strong>
                                                    @if($bakim->odeme_durumu == 0)
                                                        <span class="badge badge-odeme-bekliyor">Ödeme Bekliyor</span>
                                                    @else
                                                        <span class="badge badge-odeme-alindi">Ödeme Alındı</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($bakim->genel_aciklama)
                                        <div class="info-card p-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-sticky-note me-2"></i>
                                                Genel Açıklama
                                            </h6>
                                            <p class="mb-0">{{ $bakim->genel_aciklama }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Değişecek Parçalar -->
                            @if($bakim->degisecekParcalar->count() > 0)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-wrench me-2"></i>
                                            Değişecek Parçalar
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Parça Adı</th>
                                                        <th>Adet</th>
                                                        <th>Birim Fiyat</th>
                                                        <th>Toplam</th>
                                                        <th>Açıklama</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($bakim->degisecekParcalar as $parca)
                                                        <tr>
                                                            <td>{{ $parca->parca_adi }}</td>
                                                            <td>{{ $parca->adet }}</td>
                                                            <td>{{ number_format($parca->birim_fiyat, 2) }} ₺</td>
                                                            <td><strong>{{ number_format($parca->adet * $parca->birim_fiyat, 2) }} ₺</strong></td>
                                                            <td>{{ $parca->aciklama ?: '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Durum ve İşlemler -->
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-cogs me-2"></i>
                                        Durum ve İşlemler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <h6 class="text-muted mb-2">Bakım Durumu</h6>
                                        @if($bakim->bakim_durumu == 'Devam Ediyor')
                                            <span class="badge badge-devam fs-6">Devam Ediyor</span>
                                        @else
                                            <span class="badge badge-tamamlandi fs-6">Tamamlandı</span>
                                        @endif
                                    </div>

                                    @if($bakim->tamamlayanPersonel)
                                        <div class="text-center mb-4">
                                            <h6 class="text-muted mb-2">Onaylayan Personel</h6>
                                            <span class="badge bg-success fs-6">{{ $bakim->tamamlayanPersonel->name }}</span>
                                            @if($bakim->tamamlanma_tarihi)
                                                <p class="text-muted mt-2 mb-0">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $bakim->tamamlanma_tarihi->format('d.m.Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                    @if($bakim->tamamlanma_notu)
                                        <div class="mb-4">
                                            <h6 class="text-muted mb-2">Onay Notu</h6>
                                            <div class="info-card p-3">
                                                <p class="mb-0">{{ $bakim->tamamlanma_notu }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($bakim->bakim_durumu == 'Devam Ediyor')
                                        <div class="d-grid">
                                            <button type="button" 
                                                    class="btn btn-success btn-lg" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#completeModal">
                                                <i class="fas fa-check me-2"></i>
                                                Bakımı Onayla
                                            </button>
                                        </div>
                                    @else
                                        <div class="alert alert-success text-center">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Bu bakım onaylanmıştır.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Maintenance Modal -->
    @if($bakim->bakim_durumu == 'Devam Ediyor')
        <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="completeModalLabel">
                            <i class="fas fa-check-circle me-2"></i>
                            Bakımı Onayla
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('staff.bakim.complete', $bakim) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <h6>Servis Bilgileri</h6>
                                <p><strong>Plaka:</strong> {{ $bakim->plaka }}</p>
                                <p><strong>Müşteri:</strong> {{ $bakim->musteri_adi }}</p>
                                <p><strong>Bakım Tarihi:</strong> {{ $bakim->bakim_tarihi->format('d.m.Y H:i') }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tamamlanma_notu" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>
                                    Onay Notu (Opsiyonel)
                                </label>
                                <textarea class="form-control" 
                                          id="tamamlanma_notu" 
                                          name="tamamlanma_notu" 
                                          rows="3" 
                                          placeholder="Bakım onayı ile ilgili notlarınızı buraya yazabilirsiniz..."></textarea>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Dikkat:</strong> Bu işlem geri alınamaz. Bakım onaylandı olarak işaretlenecektir.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                İptal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i>
                                Bakımı Onayla
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
