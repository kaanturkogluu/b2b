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
            border-left: 4px solid #667eea;
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
                        <a class="nav-link active" href="{{ route('bakim.index') }}">
                            <i class="fas fa-tools me-2"></i>
                            Servis Yönetimi
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
                            <h2 class="mb-0">Servis Detayı</h2>
                            <p class="text-muted mb-0">Servis #{{ $bakim->id }} - {{ $bakim->plaka }}</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('bakim.print', $bakim) }}" class="btn btn-info" target="_blank">
                                <i class="fas fa-print me-2"></i>
                                Yazdır
                            </a>
                            <a href="{{ route('bakim.edit', $bakim) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>
                                Düzenle
                            </a>
                            <a href="{{ route('bakim.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Geri Dön
                            </a>
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
                                                    <i class="fas fa-user-tie me-2"></i>
                                                    Personel Bilgileri
                                                </h6>
                                                <p class="mb-1"><strong>Sorumlu Personel:</strong> {{ $bakim->personel->name ?? 'Belirtilmemiş' }}</p>
                                                <p class="mb-0"><strong>Kayıt Eden:</strong> {{ $bakim->admin->name ?? 'Belirtilmemiş' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($bakim->genel_aciklama)
                                        <div class="info-card p-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-comment me-2"></i>
                                                Genel Açıklama
                                            </h6>
                                            <p class="mb-0">{{ $bakim->genel_aciklama }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Değişecek Parçalar -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-wrench me-2"></i>
                                        Değişecek Parçalar
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($bakim->degisecekParcalar->count() > 0)
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
                                                            <td><strong>{{ number_format($parca->toplam_fiyat, 2) }} ₺</strong></td>
                                                            <td>{{ $parca->aciklama ?: '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="table-primary">
                                                        <th colspan="3">Toplam Parça Tutarı</th>
                                                        <th>{{ number_format($bakim->degisecekParcalar->sum('toplam_fiyat'), 2) }} ₺</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-wrench fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Bu serviste değişecek parça bulunmuyor.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Durum ve Özet -->
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        Durum Özeti
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <h3 class="text-primary">{{ number_format($bakim->ucret, 2) }} ₺</h3>
                                        <p class="text-muted">Toplam Ücret</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Bakım Durumu</label>
                                        <div>
                                            @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                <span class="badge badge-devam fs-6">Devam Ediyor</span>
                                            @else
                                                <span class="badge badge-tamamlandi fs-6">Tamamlandı</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ödeme Durumu</label>
                                        <div>
                                            @if($bakim->odeme_durumu == 0)
                                                <span class="badge badge-odeme-bekliyor fs-6">Ödeme Bekliyor</span>
                                            @else
                                                <span class="badge badge-odeme-alindi fs-6">Ödeme Alındı</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kayıt Tarihi</label>
                                        <p class="mb-0">{{ $bakim->created_at->format('d.m.Y H:i') }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Son Güncelleme</label>
                                        <p class="mb-0">{{ $bakim->updated_at->format('d.m.Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- İşlemler -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-tools me-2"></i>
                                        Hızlı İşlemler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('bakim.edit', $bakim) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>
                                            Düzenle
                                        </a>
                                        
                                        @if($bakim->odeme_durumu == 0)
                                            <form method="POST" action="{{ route('bakim.update', $bakim) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="odeme_durumu" value="1">
                                                <input type="hidden" name="plaka" value="{{ $bakim->plaka }}">
                                                <input type="hidden" name="sase" value="{{ $bakim->sase }}">
                                                <input type="hidden" name="tahmini_teslim_tarihi" value="{{ $bakim->tahmini_teslim_tarihi->format('Y-m-d\TH:i') }}">
                                                <input type="hidden" name="telefon_numarasi" value="{{ $bakim->telefon_numarasi }}">
                                                <input type="hidden" name="musteri_adi" value="{{ $bakim->musteri_adi }}">
                                                <input type="hidden" name="bakim_durumu" value="{{ $bakim->bakim_durumu }}">
                                                <input type="hidden" name="ucret" value="{{ $bakim->ucret }}">
                                                <input type="hidden" name="genel_aciklama" value="{{ $bakim->genel_aciklama }}">
                                                <input type="hidden" name="bakim_tarihi" value="{{ $bakim->bakim_tarihi->format('Y-m-d\TH:i') }}">
                                                <input type="hidden" name="personel_id" value="{{ $bakim->personel_id }}">
                                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Ödeme alındı olarak işaretlemek istediğinizden emin misiniz?')">
                                                    <i class="fas fa-check me-2"></i>
                                                    Ödeme Alındı
                                                </button>
                                            </form>
                                        @endif

                                        @if($bakim->bakim_durumu == 'Devam Ediyor')
                                            <form method="POST" action="{{ route('bakim.update', $bakim) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="bakim_durumu" value="Tamamlandı">
                                                <input type="hidden" name="plaka" value="{{ $bakim->plaka }}">
                                                <input type="hidden" name="sase" value="{{ $bakim->sase }}">
                                                <input type="hidden" name="tahmini_teslim_tarihi" value="{{ $bakim->tahmini_teslim_tarihi->format('Y-m-d\TH:i') }}">
                                                <input type="hidden" name="telefon_numarasi" value="{{ $bakim->telefon_numarasi }}">
                                                <input type="hidden" name="musteri_adi" value="{{ $bakim->musteri_adi }}">
                                                <input type="hidden" name="odeme_durumu" value="{{ $bakim->odeme_durumu }}">
                                                <input type="hidden" name="ucret" value="{{ $bakim->ucret }}">
                                                <input type="hidden" name="genel_aciklama" value="{{ $bakim->genel_aciklama }}">
                                                <input type="hidden" name="bakim_tarihi" value="{{ $bakim->bakim_tarihi->format('Y-m-d\TH:i') }}">
                                                <input type="hidden" name="personel_id" value="{{ $bakim->personel_id }}">
                                                <button type="submit" class="btn btn-info w-100" onclick="return confirm('Bakımı tamamlandı olarak işaretlemek istediğinizden emin misiniz?')">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    Bakımı Tamamla
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('bakim.destroy', $bakim) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Bu servis kaydını silmek istediğinizden emin misiniz?')">
                                                <i class="fas fa-trash me-2"></i>
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </div>
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
