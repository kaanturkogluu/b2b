<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servislerim - MotoJet Servis</title>
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
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
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
        
        .badge-payment-approved {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
    </style>
</head>
<body class="theme-staff">
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
                        <a class="nav-link active" href="{{ route('staff.bakim.index') }}">
                            <i class="fas fa-cogs me-2"></i>
                            Tüm Bakımlar
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
                            <h2 class="mb-0">Tüm Servisler</h2>
                            <p class="text-muted mb-0">Tüm bakım kayıtları ve onaylama işlemleri</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-3">Personel</span>
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

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $bakimlar->where('bakim_durumu', 'Devam Ediyor')->count() }}</h4>
                                            <p class="mb-0">Bekleyen Bakım</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $bakimlar->where('bakim_durumu', 'Tamamlandı')->where('odeme_durumu', 1)->count() }}</h4>
                                            <p class="mb-0">Tamamlanan</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $bakimlar->total() }}</h4>
                                            <p class="mb-0">Toplam Bakım</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-cogs fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>
                                Bakım Kayıtları
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Advanced Search and Filter -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-filter me-2"></i>
                                        Filtreleme
                                        <button class="btn btn-sm btn-outline-primary float-end" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </h6>
                                </div>
                                <div class="collapse" id="filterCollapse">
                                    <div class="card-body">
                                        <form method="GET" action="{{ route('staff.bakim.index') }}" id="filterForm">
                                            <div class="row g-3">
                                                <!-- Arama -->
                                                <div class="col-md-4">
                                                    <label class="form-label">Arama</label>
                                                    <input type="text" 
                                                           name="search" 
                                                           class="form-control" 
                                                           placeholder="Plaka, müşteri, telefon, şase..." 
                                                           value="{{ request('search') }}">
                                                </div>
                                                
                                                <!-- Bakım Durumu -->
                                                <div class="col-md-2">
                                                    <label class="form-label">Bakım Durumu</label>
                                                    <select name="bakim_durumu" class="form-select">
                                                        @foreach($filterOptions['bakim_durumu_options'] as $value => $label)
                                                            <option value="{{ $value }}" {{ request('bakim_durumu') == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Ödeme Durumu -->
                                                <div class="col-md-2">
                                                    <label class="form-label">Ödeme Durumu</label>
                                                    <select name="odeme_durumu" class="form-select">
                                                        @foreach($filterOptions['odeme_durumu_options'] as $value => $label)
                                                            <option value="{{ $value }}" {{ request('odeme_durumu') == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Sıralama -->
                                                <div class="col-md-2">
                                                    <label class="form-label">Sıralama</label>
                                                    <select name="sort_by" class="form-select">
                                                        @foreach($filterOptions['sort_options'] as $value => $label)
                                                            <option value="{{ $value }}" {{ request('sort_by') == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Tarih Aralığı -->
                                                <div class="col-md-3">
                                                    <label class="form-label">Başlangıç Tarihi</label>
                                                    <input type="date" 
                                                           name="start_date" 
                                                           class="form-control" 
                                                           value="{{ request('start_date') }}">
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <label class="form-label">Bitiş Tarihi</label>
                                                    <input type="date" 
                                                           name="end_date" 
                                                           class="form-control" 
                                                           value="{{ request('end_date') }}">
                                                </div>
                                                
                                                <!-- Butonlar -->
                                                <div class="col-12">
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-search me-2"></i>
                                                            Filtrele
                                                        </button>
                                                        <a href="{{ route('staff.bakim.index') }}" class="btn btn-outline-secondary">
                                                            <i class="fas fa-refresh me-2"></i>
                                                            Temizle
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Services Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Plaka</th>
                                            <th>Müşteri</th>
                                            <th>Telefon</th>
                                            <th>Bakım Durumu</th>
                                            <th>Oluşturan</th>
                                            <th>Onaylayan Personel</th>
                                            <th>Ödeme Durumu</th>
                                            <th>Ücret</th>
                                            <th>Bakım Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bakimlar as $bakim)
                                            <tr class="@if($bakim->bakim_durumu == 'Devam Ediyor') table-warning @elseif($bakim->bakim_durumu == 'Tamamlandı' && $bakim->odeme_durumu == 0) table-info @elseif($bakim->bakim_durumu == 'Tamamlandı' && $bakim->odeme_durumu == 1) table-success @endif">
                                                <td>{{ $bakim->id }}</td>
                                                <td>
                                                    <strong>{{ $bakim->plaka }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $bakim->sase }}</small>
                                                </td>
                                                <td>{{ $bakim->musteri_adi }}</td>
                                                <td>{{ $bakim->telefon_numarasi }}</td>
                                                <td>
                                                    @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                        @if($bakim->odeme_durumu == 1)
                                                            <span class="badge bg-info">Devam Ediyor (Ödeme Alındı)</span>
                                                        @else
                                                            <span class="badge badge-devam">Devam Ediyor</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-tamamlandi">Tamamlandı</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($bakim->admin)
                                                        <span class="badge bg-primary">{{ $bakim->admin->name }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">Bilinmiyor</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($bakim->tamamlayanPersonel)
                                                        <span class="badge bg-success">{{ $bakim->tamamlayanPersonel->name }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">Onaylanmadı</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                        <span class="badge bg-warning">Bakım Devam Ediyor</span>
                                                    @else
                                                        <span class="badge badge-tamamlandi">Tamamlandı</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ number_format($bakim->ucret, 2) }} ₺</strong>
                                                </td>
                                                <td>{{ $bakim->bakim_tarihi->format('d.m.Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('staff.bakim.show', $bakim) }}" 
                                                           class="btn btn-sm btn-outline-info" 
                                                           title="Görüntüle">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-success" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#completeModal{{ $bakim->id }}"
                                                                    title="Bakımı Onayla">
                                                                <i class="fas fa-check me-1"></i>
                                                                Onayla
                                                            </button>
                                                        @elseif($bakim->bakim_durumu == 'Tamamlandı' && $bakim->odeme_durumu == 0)
                                                            <span class="badge bg-info">Ödeme Bekliyor</span>
                                                        @else
                                                            <span class="badge bg-success">Tamamlandı</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-cogs fa-3x mb-3"></i>
                                                        <p>Henüz bakım kaydı bulunmuyor.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($bakimlar->hasPages())
                                <div class="pagination-container">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <small class="text-muted">
                                                Toplam {{ $bakimlar->total() }} kayıttan 
                                                {{ $bakimlar->firstItem() ?? 0 }}-{{ $bakimlar->lastItem() ?? 0 }} arası gösteriliyor
                                            </small>
                                        </div>
                                        <div class="pagination-links">
                                            {{ $bakimlar->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Maintenance Modals -->
    @foreach($bakimlar as $bakim)
        @if($bakim->bakim_durumu == 'Devam Ediyor')
            <div class="modal fade" id="completeModal{{ $bakim->id }}" tabindex="-1" aria-labelledby="completeModalLabel{{ $bakim->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="completeModalLabel{{ $bakim->id }}">
                                <i class="fas fa-check-circle me-2"></i>
                                Bakımı Onayla
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('staff.bakim.complete', $bakim) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <h6>Bakım Bilgileri</h6>
                                    <p><strong>Plaka:</strong> {{ $bakim->plaka }}</p>
                                    <p><strong>Müşteri:</strong> {{ $bakim->musteri_adi }}</p>
                                    <p><strong>Bakım Tarihi:</strong> {{ $bakim->bakim_tarihi->format('d.m.Y H:i') }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tamamlanma_notu{{ $bakim->id }}" class="form-label">
                                        <i class="fas fa-sticky-note me-1"></i>
                                        Onay Notu (Opsiyonel)
                                    </label>
                                    <textarea class="form-control" 
                                              id="tamamlanma_notu{{ $bakim->id }}" 
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
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-submit form on select change
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('select[name="bakim_durumu"], select[name="odeme_durumu"], select[name="sort_by"]');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>
</body>
</html>
