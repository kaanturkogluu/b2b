<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaman Çizelgesi - MotoJet Servis</title>
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
        .timeline-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 2rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: -2rem;
            width: 2px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .timeline-item:last-child::before {
            display: none;
        }
        .timeline-icon {
            position: absolute;
            left: 0.5rem;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: 3px solid white;
            box-shadow: 0 0 0 3px #e9ecef;
        }
        .timeline-content {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .timeline-date {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .badge-devam {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }
        .badge-tamamlandi {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
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
                            Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('staff.tasks') }}">
                            <i class="fas fa-tasks me-2"></i>
                            Görevlerim
                        </a>
                        <a class="nav-link" href="{{ route('staff.bakim.index') }}">
                            <i class="fas fa-cogs me-2"></i>
                            Servisler
                        </a>
                        <a class="nav-link active" href="{{ route('staff.timeline') }}">
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
                            <h2 class="mb-0">Zaman Çizelgesi</h2>
                            <p class="text-muted mb-0">Tüm görevlerinizin kronolojik sıralaması</p>
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

                    <!-- Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Görev Zaman Çizelgesi
                            </h5>
                        </div>
                        <div class="card-body">
                            @forelse($timeline as $date => $tasks)
                                <div class="timeline-item">
                                    <div class="timeline-icon"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-date">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            {{ \Carbon\Carbon::parse($date)->format('d F Y, l') }}
                                        </div>
                                        
                                        <div class="row">
                                            @foreach($tasks as $task)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <h6 class="mb-1">{{ $task->plaka }}</h6>
                                                                @if($task->bakim_durumu == 'Devam Ediyor')
                                                                    <span class="badge badge-devam">Devam Ediyor</span>
                                                                @else
                                                                    <span class="badge badge-tamamlandi">Tamamlandı</span>
                                                                @endif
                                                            </div>
                                                            
                                                            <p class="text-muted mb-2">
                                                                <i class="fas fa-user me-1"></i>
                                                                {{ $task->musteri_adi }}
                                                            </p>
                                                            
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <small class="text-muted">Başlangıç</small>
                                                                    <div class="fw-bold">{{ $task->bakim_tarihi->format('H:i') }}</div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted">Bitiş</small>
                                                                    <div class="fw-bold">
                                                                        {{ $task->tahmini_teslim_tarihi ? $task->tahmini_teslim_tarihi->format('H:i') : 'Belirtilmemiş' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            @if($task->genel_aciklama)
                                                                <div class="mt-2">
                                                                    <small class="text-muted">Açıklama</small>
                                                                    <div class="fw-bold">{{ $task->genel_aciklama }}</div>
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                                <div>
                                                                    <strong class="text-success">{{ number_format($task->ucret, 2) }} ₺</strong>
                                                                </div>
                                                                <div>
                                                                    <a href="{{ route('bakim.show', $task) }}" 
                                                                       class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Henüz görev bulunmuyor</h5>
                                    <p class="text-muted">Size atanmış görev bulunmuyor.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
