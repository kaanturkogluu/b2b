<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - MotoJet Servis</title>
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
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card .card-body {
            padding: 2rem;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('bakim.index') }}">
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
                            <h2 class="mb-0">Admin Paneli</h2>
                            <p class="text-muted mb-0">Hoş geldiniz, {{ $user->name }}</p>
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

                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x mb-3"></i>
                                    <div class="stat-number">{{ \App\Models\User::count() }}</div>
                                    <div>Toplam Kullanıcı</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-cogs fa-2x mb-3"></i>
                                    <div class="stat-number">{{ \App\Models\Bakim::count() }}</div>
                                    <div>Servis Kayıtları</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x mb-3"></i>
                                    <div class="stat-number">{{ \App\Models\Bakim::where('bakim_durumu', 'Devam Ediyor')->count() }}</div>
                                    <div>Bekleyen Servisler</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-3"></i>
                                    <div class="stat-number">{{ \App\Models\Bakim::where('bakim_durumu', 'Tamamlandı')->count() }}</div>
                                    <div>Tamamlanan Servisler</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history me-2"></i>
                                        Son Aktiviteler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        @forelse($recentActivities as $activity)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $activity->description }}</h6>
                                                    <small class="text-muted">
                                                        {{ $activity->user->name ?? 'Sistem' }} - 
                                                        {{ $activity->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                <span class="badge 
                                                    @if($activity->type == 'bakim_created') bg-primary
                                                    @elseif($activity->type == 'bakim_completed') bg-success
                                                    @elseif($activity->type == 'bakim_deleted') bg-danger
                                                    @elseif($activity->type == 'user_created') bg-info
                                                    @elseif($activity->type == 'user_deleted') bg-warning
                                                    @elseif($activity->type == 'payment_approved') bg-success
                                                    @else bg-secondary
                                                    @endif">
                                                    @if($activity->type == 'bakim_created') Yeni Servis
                                                    @elseif($activity->type == 'bakim_completed') Tamamlandı
                                                    @elseif($activity->type == 'bakim_deleted') Servis Silindi
                                                    @elseif($activity->type == 'user_created') Kullanıcı Oluşturuldu
                                                    @elseif($activity->type == 'user_deleted') Kullanıcı Silindi
                                                    @elseif($activity->type == 'payment_approved') Ödeme Onaylandı
                                                    @else Aktivite
                                                    @endif
                                                </span>
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Henüz aktivite bulunmuyor.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-tasks me-2"></i>
                                        Hızlı İşlemler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('bakim.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Yeni Servis Ekle
                                        </a>
                                        <a href="{{ route('users.create') }}" class="btn btn-success">
                                            <i class="fas fa-user-plus me-2"></i>
                                            Kullanıcı Ekle
                                        </a>
                                        <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-users me-2"></i>
                                            Kullanıcıları Yönet
                                        </a>
                                        <a href="{{ route('reports.index') }}" class="btn btn-info">
                                            <i class="fas fa-chart-line me-2"></i>
                                            Rapor Görüntüle
                                        </a>
                                        <a href="{{ route('invoice-settings.index') }}" class="btn btn-warning">
                                            <i class="fas fa-file-invoice me-2"></i>
                                            Fatura Ayarları
                                        </a>
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
