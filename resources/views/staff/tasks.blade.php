<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Görevlerim - MotoJet Servis</title>
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
        .task-priority {
            border-left: 4px solid #28a745;
            padding-left: 1rem;
        }
        .task-priority.high {
            border-left-color: #dc3545;
        }
        .task-priority.medium {
            border-left-color: #ffc107;
        }
        .task-priority.low {
            border-left-color: #28a745;
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
                            Dashboard
                        </a>
                        <a class="nav-link active" href="{{ route('staff.tasks') }}">
                            <i class="fas fa-tasks me-2"></i>
                            Görevlerim
                        </a>
                        <a class="nav-link" href="{{ route('staff.bakim.index') }}">
                            <i class="fas fa-cogs me-2"></i>
                            Servisler
                        </a>
                        <a class="nav-link" href="{{ route('staff.timeline') }}">
                            <i class="fas fa-clock me-2"></i>
                            Zaman Çizelgesi
                        </a>
                        <a class="nav-link" href="{{ route('staff.profile') }}">
                            <i class="fas fa-user me-2"></i>
                            Profilim
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
                            <h2 class="mb-0">Görevlerim</h2>
                            <p class="text-muted mb-0">Tüm görevlerinizi ve durumlarını görüntüleyin</p>
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

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-tasks me-2"></i>
                                Tüm Görevlerim
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Search and Filter -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('staff.tasks') }}" class="d-flex">
                                        <input type="text" 
                                               name="search" 
                                               class="form-control me-2" 
                                               placeholder="Plaka ara..." 
                                               value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route('staff.tasks') }}">
                                        <select name="bakim_durumu" class="form-select" onchange="this.form.submit()">
                                            <option value="">Tüm Durumlar</option>
                                            <option value="Devam Ediyor" {{ request('bakim_durumu') == 'Devam Ediyor' ? 'selected' : '' }}>Devam Ediyor</option>
                                            <option value="Tamamlandı" {{ request('bakim_durumu') == 'Tamamlandı' ? 'selected' : '' }}>Tamamlandı</option>
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-3 text-end">
                                    <a href="{{ route('staff.tasks') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh me-2"></i>
                                        Temizle
                                    </a>
                                </div>
                            </div>

                            <!-- Tasks List -->
                            <div class="row">
                                @forelse($tasks as $task)
                                    <div class="col-md-6 mb-4">
                                        <div class="card task-priority {{ $task->bakim_durumu == 'Devam Ediyor' ? 'high' : 'medium' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h6 class="mb-1">{{ $task->plaka }}</h6>
                                                        <p class="text-muted mb-1">{{ $task->musteri_adi }}</p>
                                                        <small class="text-muted">{{ $task->telefon_numarasi }}</small>
                                                    </div>
                                                    @if($task->bakim_durumu == 'Devam Ediyor')
                                                        <span class="badge badge-devam">Devam Ediyor</span>
                                                    @else
                                                        <span class="badge badge-tamamlandi">Tamamlandı</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <small class="text-muted">Bakım Tarihi</small>
                                                        <div class="fw-bold">{{ $task->bakim_tarihi->format('d.m.Y H:i') }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Tahmini Teslim</small>
                                                        <div class="fw-bold">
                                                            {{ $task->tahmini_teslim_tarihi ? $task->tahmini_teslim_tarihi->format('d.m.Y H:i') : 'Belirtilmemiş' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                @if($task->genel_aciklama)
                                                    <div class="mb-3">
                                                        <small class="text-muted">Açıklama</small>
                                                        <div class="fw-bold">{{ $task->genel_aciklama }}</div>
                                                    </div>
                                                @endif
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <!-- Ödeme durumu personel için gizlendi -->
                                                    </div>
                                                    <div>
                                                        <strong class="text-success">{{ number_format($task->ucret, 2) }} ₺</strong>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <div class="btn-group w-100" role="group">
                                                        <a href="{{ route('bakim.show', $task) }}" 
                                                           class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye me-1"></i>Görüntüle
                                                        </a>
                                                        <a href="{{ route('bakim.edit', $task) }}" 
                                                           class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit me-1"></i>Düzenle
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Henüz görev bulunmuyor</h5>
                                            <p class="text-muted">Size atanmış görev bulunmuyor.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            @if($tasks->hasPages())
                                <div class="pagination-container">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <small class="text-muted">
                                                Toplam {{ $tasks->total() }} kayıttan 
                                                {{ $tasks->firstItem() ?? 0 }}-{{ $tasks->lastItem() ?? 0 }} arası gösteriliyor
                                            </small>
                                        </div>
                                        <div class="pagination-links">
                                            {{ $tasks->appends(request()->query())->links() }}
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
</body>
</html>
