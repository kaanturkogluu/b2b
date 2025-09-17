<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personel Paneli - MotoJet Servis</title>
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
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        .task-item {
            border-left: 4px solid #28a745;
            padding-left: 1rem;
            margin-bottom: 1rem;
        }
        .priority-high {
            border-left-color: #dc3545;
        }
        .priority-medium {
            border-left-color: #ffc107;
        }
        .priority-low {
            border-left-color: #28a745;
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
                        <a class="nav-link active" href="#">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-tasks me-2"></i>
                            Görevlerim
                        </a>
                        <a class="nav-link" href="{{ route('staff.bakim.index') }}">
                            <i class="fas fa-cogs me-2"></i>
                            Servisler
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-clock me-2"></i>
                            Zaman Çizelgesi
                        </a>
                        <a class="nav-link" href="#">
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
                            <h2 class="mb-0">Personel Paneli</h2>
                            <p class="text-muted mb-0">Hoş geldiniz, {{ $user->name }}</p>
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

                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-tasks fa-2x mb-3"></i>
                                    <div class="stat-number">5</div>
                                    <div>Aktif Görevler</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-3"></i>
                                    <div class="stat-number">23</div>
                                    <div>Tamamlanan Görevler</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x mb-3"></i>
                                    <div class="stat-number">8.5</div>
                                    <div>Çalışma Saati</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-star fa-2x mb-3"></i>
                                    <div class="stat-number">4.8</div>
                                    <div>Performans Puanı</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks and Activities -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-tasks me-2"></i>
                                        Günlük Görevlerim
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="task-item priority-high">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">Honda CBR 600 - Motor Bakımı</h6>
                                                <p class="text-muted mb-1">Müşteri: Mehmet Yılmaz</p>
                                                <small class="text-muted">Saat: 14:00 - 16:00</small>
                                            </div>
                                            <span class="badge bg-danger">Yüksek Öncelik</span>
                                        </div>
                                    </div>
                                    
                                    <div class="task-item priority-medium">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">Yamaha R1 - Fren Sistemi Kontrolü</h6>
                                                <p class="text-muted mb-1">Müşteri: Ayşe Demir</p>
                                                <small class="text-muted">Saat: 16:30 - 17:30</small>
                                            </div>
                                            <span class="badge bg-warning">Orta Öncelik</span>
                                        </div>
                                    </div>
                                    
                                    <div class="task-item priority-low">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">Kawasaki Ninja - Genel Kontrol</h6>
                                                <p class="text-muted mb-1">Müşteri: Ali Kaya</p>
                                                <small class="text-muted">Saat: 18:00 - 19:00</small>
                                            </div>
                                            <span class="badge bg-success">Düşük Öncelik</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        Bugünkü Program
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success rounded-circle p-2 me-3">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Honda CBR 600</h6>
                                                <small class="text-muted">09:00 - 11:00 (Tamamlandı)</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success rounded-circle p-2 me-3">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Yamaha R1</h6>
                                                <small class="text-muted">11:30 - 13:00 (Tamamlandı)</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-warning rounded-circle p-2 me-3">
                                                <i class="fas fa-clock text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Honda CBR 600</h6>
                                                <small class="text-muted">14:00 - 16:00 (Devam Ediyor)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-tools me-2"></i>
                                        Hızlı İşlemler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('staff.bakim.index') }}" class="btn btn-primary">
                                            <i class="fas fa-cogs me-2"></i>
                                            Servislerim
                                        </a>
                                        <a href="{{ route('staff.bakim.index') }}?bakim_durumu=Devam Ediyor" class="btn btn-warning">
                                            <i class="fas fa-clock me-2"></i>
                                            Devam Eden Servisler
                                        </a>
                                        <a href="{{ route('staff.bakim.index') }}?bakim_durumu=Tamamlandı" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>
                                            Tamamlanan Servisler
                                        </a>
                                        <button class="btn btn-info">
                                            <i class="fas fa-chart-line me-2"></i>
                                            Performans Raporu
                                        </button>
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
