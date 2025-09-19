<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Yönetimi - MotoJet Servis</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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
        
    </style>
</head>
<body class="theme-admin">
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
                            <h2 class="mb-0">Servis Yönetimi</h2>
                            <p class="text-muted mb-0">Bakım servislerini yönetin</p>
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

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>
                                Servis Listesi
                            </h5>
                            <a href="{{ route('bakim.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Yeni Servis
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Advanced Search and Filter -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-filter me-2"></i>
                                        Gelişmiş Filtreleme
                                        <button class="btn btn-sm btn-outline-primary float-end" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </h6>
                                </div>
                                <div class="collapse" id="filterCollapse">
                                    <div class="card-body">
                                        <form method="GET" action="{{ route('bakim.index') }}" id="filterForm">
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
                                                
                                                <!-- Personel filtreleme kaldırıldı -->
                                                
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
                                                
                                                <!-- Ücret Aralığı -->
                                                <div class="col-md-3">
                                                    <label class="form-label">Min. Ücret (₺)</label>
                                                    <input type="number" 
                                                           name="min_ucret" 
                                                           class="form-control" 
                                                           placeholder="0" 
                                                           value="{{ request('min_ucret') }}">
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <label class="form-label">Max. Ücret (₺)</label>
                                                    <input type="number" 
                                                           name="max_ucret" 
                                                           class="form-control" 
                                                           placeholder="999999" 
                                                           value="{{ request('max_ucret') }}">
                                                </div>
                                                
                                                <!-- Butonlar -->
                                                <div class="col-12">
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-search me-2"></i>
                                                            Filtrele
                                                        </button>
                                                        <a href="{{ route('bakim.index') }}" class="btn btn-outline-secondary">
                                                            <i class="fas fa-refresh me-2"></i>
                                                            Temizle
                                                        </a>
                                                        <button type="button" class="btn btn-outline-info" onclick="exportToExcel()">
                                                            <i class="fas fa-file-excel me-2"></i>
                                                            Excel'e Aktar
                                                        </button>
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
                                            <th>Ödeme Durumu</th>
                                            <th>Toplam Ücret</th>
                                            <th>Tamamlayan Personel</th>
                                            <th>Bakım Notu</th>
                                            <th>Bakım Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bakimlar as $bakim)
                                            <tr>
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
                                                        <span class="badge badge-devam">Devam Ediyor</span>
                                                    @else
                                                        <span class="badge badge-tamamlandi">Tamamlandı</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                        <span class="badge bg-warning">Bakım Devam Ediyor</span>
                                                    @elseif($bakim->odeme_durumu == 0)
                                                        <span class="badge badge-odeme-bekliyor">Ödeme Bekliyor</span>
                                                    @else
                                                        <span class="badge badge-odeme-alindi">Ödeme Alındı</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <strong>{{ number_format(($bakim->ucret ?? 0) + ($bakim->iscilik_ucreti ?? 0), 2) }} ₺</strong>
                                                        @if($bakim->iscilik_ucreti > 0)
                                                            <br><small class="text-muted">
                                                                Parça: {{ number_format($bakim->ucret ?? 0, 2) }} ₺<br>
                                                                İşçilik: {{ number_format($bakim->iscilik_ucreti ?? 0, 2) }} ₺
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $bakim->tamamlayanPersonel->name ?? '-' }}</td>
                                                <td>
                                                    @if($bakim->tamamlanma_notu)
                                                        <span class="badge bg-info" 
                                                              data-bs-toggle="tooltip" 
                                                              data-bs-placement="top" 
                                                              title="{{ $bakim->tamamlanma_notu }}">
                                                            <i class="fas fa-sticky-note me-1"></i>
                                                            Not Var
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $bakim->bakim_tarihi->format('d.m.Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('bakim.show', $bakim) }}" 
                                                           class="btn btn-sm btn-outline-info" 
                                                           title="Görüntüle">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('bakim.edit', $bakim) }}" 
                                                           class="btn btn-sm btn-outline-warning" 
                                                           title="Düzenle">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                     
                                                        <form method="POST" 
                                                              action="{{ route('bakim.destroy', $bakim) }}" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Bu servis kaydını silmek istediğinizden emin misiniz?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-outline-danger" 
                                                                    title="Sil">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-cogs fa-3x mb-3"></i>
                                                        <p>Henüz servis kaydı bulunmuyor.</p>
                                                        <a href="{{ route('bakim.create') }}" class="btn btn-primary">
                                                            <i class="fas fa-plus me-2"></i>
                                                            İlk Servis Kaydını Oluştur
                                                        </a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Export to Excel function
        function exportToExcel() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            
            window.open('{{ route("bakim.export.excel") }}?' + params.toString(), '_blank');
        }
        
        // Auto-submit form on select change
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('select[name="bakim_durumu"], select[name="odeme_durumu"], select[name="sort_by"]');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
            
            // Lazy loading for images
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
            
            // Debounced search
            let searchTimeout;
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.form.submit();
                    }, 500);
                });
            }
        });
    </script>
</body>
</html>
