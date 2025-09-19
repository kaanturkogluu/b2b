<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Düzenle - MotoJet Servis</title>
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
        .parca-row {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }
        .parca-row:last-child {
            margin-bottom: 0;
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
                            <h2 class="mb-0">Servis Düzenle</h2>
                            <p class="text-muted mb-0">Servis kaydını düzenleyin</p>
                        </div>
                        <div class="d-flex align-items-center">
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

                    <!-- Flash Messages -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-edit me-2"></i>
                                Servis Bilgileri
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($bakim->bakim_durumu == 'Tamamlandı')
                                <div class="alert alert-warning mb-4">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Dikkat:</strong> Bu bakım tamamlandı olarak işaretlenmiş. Değişiklik yapılamaz, sadece görüntüleme modundasınız.
                                </div>
                            @endif
                            
                            <form id="bakimForm" method="POST" action="{{ route('bakim.update', $bakim) }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Araç Bilgileri -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-car me-2"></i>
                                            Araç Bilgileri
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="plaka" class="form-label">Plaka <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('plaka') is-invalid @enderror" 
                                               id="plaka" 
                                               name="plaka" 
                                               value="{{ old('plaka', $bakim->plaka) }}" 
                                               {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                               required>
                                        @error('plaka')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sase" class="form-label">Şase No <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('sase') is-invalid @enderror" 
                                               id="sase" 
                                               name="sase" 
                                               value="{{ old('sase', $bakim->sase) }}" 
                                               {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                               required>
                                        @error('sase')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Müşteri Bilgileri -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-user me-2"></i>
                                            Müşteri Bilgileri
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="musteri_adi" class="form-label">Müşteri Adı <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('musteri_adi') is-invalid @enderror" 
                                               id="musteri_adi" 
                                               name="musteri_adi" 
                                               value="{{ old('musteri_adi', $bakim->musteri_adi) }}" 
                                               {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                               required>
                                        @error('musteri_adi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telefon_numarasi" class="form-label">Telefon Numarası <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('telefon_numarasi') is-invalid @enderror" 
                                               id="telefon_numarasi" 
                                               name="telefon_numarasi" 
                                               value="{{ old('telefon_numarasi', $bakim->telefon_numarasi) }}" 
                                               {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                               required>
                                        @error('telefon_numarasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Servis Bilgileri -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-cogs me-2"></i>
                                            Servis Bilgileri
                                        </h6>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="bakim_tarihi" class="form-label">Bakım Tarihi <span class="text-danger">*</span></label>
                                        <input type="datetime-local" 
                                               class="form-control @error('bakim_tarihi') is-invalid @enderror" 
                                               id="bakim_tarihi" 
                                               name="bakim_tarihi" 
                                               value="{{ old('bakim_tarihi', $bakim->bakim_tarihi->format('Y-m-d\TH:i')) }}" 
                                               {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                               required>
                                        @error('bakim_tarihi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tahmini_teslim_tarihi" class="form-label">Tahmini Teslim Tarihi <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="datetime-local" 
                                                   class="form-control @error('tahmini_teslim_tarihi') is-invalid @enderror" 
                                                   id="tahmini_teslim_tarihi" 
                                                   name="tahmini_teslim_tarihi" 
                                                   value="{{ old('tahmini_teslim_tarihi', $bakim->tahmini_teslim_tarihi->format('Y-m-d\TH:i')) }}" 
                                                   {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="setTahminiTarih(1)">1 Gün</button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="setTahminiTarih(7)">1 Hafta</button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="setTahminiTarih(30)">1 Ay</button>
                                        </div>
                                        @error('tahmini_teslim_tarihi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="personel_id" class="form-label">Sorumlu Personel (Sadece Tamamlandığında)</label>
                                        <select class="form-select @error('personel_id') is-invalid @enderror" 
                                                id="personel_id" 
                                                name="personel_id"
                                                {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'disabled' : '' }}>
                                            <option value="">Personel Seçin (Opsiyonel)</option>
                                            @foreach($personeller as $personel)
                                                <option value="{{ $personel->id }}" {{ old('personel_id', $bakim->personel_id) == $personel->id ? 'selected' : '' }}>
                                                    {{ $personel->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('personel_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="bakim_durumu" class="form-label">Bakım Durumu <span class="text-danger">*</span></label>
                                        <select class="form-select @error('bakim_durumu') is-invalid @enderror" 
                                                id="bakim_durumu" 
                                                name="bakim_durumu" 
                                                {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'disabled' : '' }}
                                                required>
                                            <option value="Devam Ediyor" {{ old('bakim_durumu', $bakim->bakim_durumu) == 'Devam Ediyor' ? 'selected' : '' }}>Devam Ediyor</option>
                                            <option value="Tamamlandı" {{ old('bakim_durumu', $bakim->bakim_durumu) == 'Tamamlandı' ? 'selected' : '' }}>Tamamlandı</option>
                                        </select>
                                        @error('bakim_durumu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Ücret ve Ödeme -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label for="ucret" class="form-label">Parça Ücreti (₺)</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="ucret_display" 
                                               value="{{ number_format($bakim->ucret, 2) }}" 
                                               readonly
                                               style="background-color: #f8f9fa;">
                                        <input type="hidden" 
                                               id="ucret" 
                                               name="ucret" 
                                               value="{{ $bakim->ucret }}">
                                        <small class="text-muted">Parça fiyatlarından otomatik hesaplanır</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="iscilik_ucreti" class="form-label">İşçilik Ücreti (₺) <span class="text-muted">(Opsiyonel)</span></label>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control @error('iscilik_ucreti') is-invalid @enderror" 
                                               id="iscilik_ucreti" 
                                               name="iscilik_ucreti" 
                                               value="{{ old('iscilik_ucreti', $bakim->iscilik_ucreti ?? '0.00') }}"
                                               placeholder="0.00"
                                               {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                               onchange="calculateTotal()">
                                        @error('iscilik_ucreti')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">İşçilik ücreti girin (opsiyonel)</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="odeme_durumu" class="form-label">Ödeme Durumu <span class="text-danger">*</span></label>
                                        <select class="form-select @error('odeme_durumu') is-invalid @enderror"
                                                id="odeme_durumu" 
                                                name="odeme_durumu" 
                                                {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'disabled' : '' }}
                                                required>
                                            <option value="0" {{ old('odeme_durumu', $bakim->odeme_durumu) == 0 ? 'selected' : '' }}>Ödeme Bekliyor</option>
                                            <option value="1" {{ old('odeme_durumu', $bakim->odeme_durumu) == 1 ? 'selected' : '' }}>Ödeme Alındı</option>
                                        </select>
                                        @error('odeme_durumu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="genel_aciklama" class="form-label">Genel Açıklama (Opsiyonel)</label>
                                        <input type="text" 
                                               class="form-control @error('genel_aciklama') is-invalid @enderror"
                                               id="genel_aciklama" 
                                               name="genel_aciklama" 
                                               value="{{ old('genel_aciklama', $bakim->genel_aciklama) }}" 
                                               {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                               placeholder="Opsiyonel açıklama">
                                        @error('genel_aciklama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Toplam Ücret Gösterimi -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <h6 class="mb-2">
                                                <i class="fas fa-calculator me-2"></i>
                                                Toplam Ücret Hesaplaması
                                            </h6>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small>Parça Ücreti:</small><br>
                                                    <strong id="parca_ucreti_display">{{ number_format($bakim->ucret, 2) }} ₺</strong>
                                                </div>
                                                <div class="col-6">
                                                    <small>İşçilik Ücreti:</small><br>
                                                    <strong id="iscilik_display">{{ number_format($bakim->iscilik_ucreti ?? 0, 2) }} ₺</strong>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between">
                                                <strong>Toplam:</strong>
                                                <strong id="toplam_ucret_display">{{ number_format(($bakim->ucret ?? 0) + ($bakim->iscilik_ucreti ?? 0), 2) }} ₺</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Değişecek Parçalar -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-wrench me-2"></i>
                                            Değişecek Parçalar
                                            @if($bakim->bakim_durumu == 'Tamamlandı')
                                                <span class="badge bg-success ms-2">Tamamlandı - Sadece Görüntüleme</span>
                                            @endif
                                        </h6>
                                        <div id="parcalar-container">
                                            @if($bakim->degisecekParcalar->count() > 0)
                                                @foreach($bakim->degisecekParcalar as $index => $parca)
                                                    <div class="parca-row">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Parça Adı</label>
                                                                <input type="hidden" name="parcalar[{{ $index }}][id]" value="{{ $parca->id }}">
                                                                <input type="text" 
                                                                       class="form-control" 
                                                                       name="parcalar[{{ $index }}][parca_adi]" 
                                                                       value="{{ $parca->parca_adi }}"
                                                                       {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                                                       placeholder="Parça adını girin">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Adet</label>
                                                                <input type="number" 
                                                                       class="form-control" 
                                                                       name="parcalar[{{ $index }}][adet]" 
                                                                       min="1" 
                                                                       value="{{ $parca->adet }}"
                                                                       {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                                                       onchange="calculateTotal()">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Birim Fiyat (₺)</label>
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       class="form-control" 
                                                                       name="parcalar[{{ $index }}][birim_fiyat]" 
                                                                       value="{{ $parca->birim_fiyat }}"
                                                                       {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                                                       placeholder="0.00"
                                                                       onchange="calculateTotal()">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Açıklama (Opsiyonel)</label>
                                                                <input type="text" 
                                                                       class="form-control" 
                                                                       name="parcalar[{{ $index }}][aciklama]" 
                                                                       value="{{ $parca->aciklama }}"
                                                                       {{ $bakim->bakim_durumu == 'Tamamlandı' ? 'readonly' : '' }}
                                                                       placeholder="Opsiyonel açıklama"
                                                                       onchange="calculateTotal()">
                                                            </div>
                                                            @if($bakim->bakim_durumu != 'Tamamlandı')
                                                            <div class="col-md-1 d-flex align-items-end">
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        onclick="removeParca(this)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        @if($bakim->bakim_durumu != 'Tamamlandı')
                                        <div class="text-center mt-3">
                                            <button type="button" class="btn btn-outline-primary" onclick="addParca()">
                                                <i class="fas fa-plus me-2"></i>
                                                Parça Ekle
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Form Buttons -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('bakim.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-arrow-left me-2"></i>
                                                    Geri Dön
                                                </a>
                                                @if($bakim->bakim_durumu != 'Tamamlandı')
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i>
                                                    Güncelle
                                                </button>
                                                @else
                                                <button type="button" class="btn btn-success" disabled>
                                                    <i class="fas fa-check me-2"></i>
                                                    Tamamlandı - Değiştirilemez
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let parcaIndex = {{ $bakim->degisecekParcalar->count() }};

        function addParca() {
            const container = document.getElementById('parcalar-container');
            const parcaRow = document.createElement('div');
            parcaRow.className = 'parca-row';
            parcaRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Parça Adı</label>
                        <input type="text" 
                               class="form-control" 
                               name="parcalar[${parcaIndex}][parca_adi]" 
                               placeholder="Parça adını girin"
                               onchange="calculateTotal()">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Adet</label>
                        <input type="number" 
                               class="form-control" 
                               name="parcalar[${parcaIndex}][adet]" 
                               min="1" 
                               value="1"
                               onchange="calculateTotal()">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Birim Fiyat (₺)</label>
                        <input type="number" 
                               step="0.01" 
                               class="form-control" 
                               name="parcalar[${parcaIndex}][birim_fiyat]" 
                               placeholder="0.00"
                               onchange="calculateTotal()">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Açıklama (Opsiyonel)</label>
                        <input type="text" 
                               class="form-control" 
                               name="parcalar[${parcaIndex}][aciklama]" 
                               placeholder="Opsiyonel açıklama">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" 
                                class="btn btn-sm btn-outline-danger" 
                                onclick="removeParca(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(parcaRow);
            parcaIndex++;
        }

        function removeParca(button) {
            button.closest('.parca-row').remove();
            calculateTotal();
        }

        // Fiyat hesaplama (parça + işçilik)
        function calculateTotal() {
            let parcaTotal = 0;
            const parcaRows = document.querySelectorAll('.parca-row');
            
            // Parça ücretlerini hesapla
            parcaRows.forEach(row => {
                const parcaAdiInput = row.querySelector('input[name*="[parca_adi]"]');
                const adetInput = row.querySelector('input[name*="[adet]"]');
                const birimFiyatInput = row.querySelector('input[name*="[birim_fiyat]"]');
                
                // Sadece parça adı ve adet dolu olanları hesapla
                if (parcaAdiInput && adetInput && birimFiyatInput) {
                    const parcaAdi = parcaAdiInput.value.trim();
                    const adet = parseFloat(adetInput.value) || 0;
                    const birimFiyat = parseFloat(birimFiyatInput.value) || 0;
                    
                    // Boş alanları atla
                    if (parcaAdi && adet > 0) {
                        parcaTotal += adet * birimFiyat;
                    }
                }
            });
            
            // İşçilik ücretini al
            const iscilikUcreti = parseFloat(document.getElementById('iscilik_ucreti').value) || 0;
            
            // Toplam hesapla
            const toplamUcret = parcaTotal + iscilikUcreti;
            
            // Değerleri güncelle
            const formattedParcaTotal = parcaTotal.toFixed(2);
            const formattedIscilik = iscilikUcreti.toFixed(2);
            const formattedTotal = toplamUcret.toFixed(2);
            
            document.getElementById('ucret_display').value = formattedParcaTotal;
            document.getElementById('ucret').value = formattedParcaTotal;
            document.getElementById('parca_ucreti_display').textContent = formattedParcaTotal + ' ₺';
            document.getElementById('iscilik_display').textContent = formattedIscilik + ' ₺';
            document.getElementById('toplam_ucret_display').textContent = formattedTotal + ' ₺';
        }

        // Form submit öncesi kontrol
        function validateForm() {
            const parcaRows = document.querySelectorAll('.parca-row');
            let hasEmptyFields = false;
            let emptyFields = [];

            parcaRows.forEach((row, index) => {
                const parcaAdiInput = row.querySelector('input[name*="[parca_adi]"]');
                const adetInput = row.querySelector('input[name*="[adet]"]');
                const birimFiyatInput = row.querySelector('input[name*="[birim_fiyat]"]');

                if (parcaAdiInput && adetInput && birimFiyatInput) {
                    const parcaAdi = parcaAdiInput.value.trim();
                    const adet = parseFloat(adetInput.value) || 0;
                    const birimFiyat = parseFloat(birimFiyatInput.value) || 0;

                    // Eğer herhangi bir alan dolu ise, diğerleri de dolu olmalı
                    if (parcaAdi || adet > 0 || birimFiyat > 0) {
                        if (!parcaAdi) {
                            hasEmptyFields = true;
                            emptyFields.push(`Parça ${index + 1}: Parça adı boş olamaz`);
                            parcaAdiInput.classList.add('is-invalid');
                        } else {
                            parcaAdiInput.classList.remove('is-invalid');
                        }

                        if (adet <= 0) {
                            hasEmptyFields = true;
                            emptyFields.push(`Parça ${index + 1}: Adet 0'dan büyük olmalı`);
                            adetInput.classList.add('is-invalid');
                        } else {
                            adetInput.classList.remove('is-invalid');
                        }

                        // Birim fiyat 0 olabilir, kontrol kaldırıldı
                    }
                }
            });

            if (hasEmptyFields) {
                alert('Lütfen aşağıdaki hataları düzeltin:\n\n' + emptyFields.join('\n'));
                return false;
            }

            return true;
        }

        // Form submit event listener
        document.getElementById('bakimForm').addEventListener('submit', function(e) {
            console.log('Form submit ediliyor...');
            if (!validateForm()) {
                console.log('Form validasyonu başarısız, submit engellendi');
                e.preventDefault();
                return false;
            }
            console.log('Form validasyonu başarılı, submit devam ediyor');
        });

        // Tahmini teslim tarihi hızlı butonları
        function setTahminiTarih(gun) {
            const now = new Date();
            now.setDate(now.getDate() + gun);
            
            // Tarihi YYYY-MM-DDTHH:MM formatına çevir
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
            document.getElementById('tahmini_teslim_tarihi').value = formattedDate;
        }

        // Eğer hiç parça yoksa bir tane ekle ve ücret hesapla
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelectorAll('.parca-row').length === 0) {
                addParca();
            }
            calculateTotal();
        });
    </script>
</body>
</html>
