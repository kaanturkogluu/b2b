<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Detayları - MotoJet Servis</title>
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
        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .badge-staff {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .avatar-lg {
            width: 80px;
            height: 80px;
            font-size: 32px;
            font-weight: bold;
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
                        <a class="nav-link" href="{{ route('bakim.index') }}">
                            <i class="fas fa-tools me-2"></i>
                            Servis Yönetimi
                        </a>
                        
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a class="nav-link active" href="{{ route('users.index') }}">
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
                            <h2 class="mb-0">Kullanıcı Detayları</h2>
                            <p class="text-muted mb-0">{{ $user->name }} kullanıcısının detayları</p>
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

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user me-2"></i>
                                        Kullanıcı Bilgileri
                                    </h5>
                                    <div class="btn-group">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit me-2"></i>
                                            Düzenle
                                        </a>
                                        @if($user->id !== Auth::id())
                                            <form method="POST" 
                                                  action="{{ route('users.destroy', $user) }}" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash me-2"></i>
                                                    Sil
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center mb-4">
                                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <h5>{{ $user->name }}</h5>
                                            @if($user->isAdmin())
                                                <span class="badge badge-admin fs-6">Admin</span>
                                            @else
                                                <span class="badge badge-staff fs-6">Personel</span>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <label class="form-label fw-bold">Kullanıcı Adı:</label>
                                                    <p class="form-control-plaintext">
                                                        <code>{{ $user->username }}</code>
                                                    </p>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="form-label fw-bold">E-posta:</label>
                                                    <p class="form-control-plaintext">{{ $user->email ?: 'Belirtilmemiş' }}</p>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="form-label fw-bold">Kayıt Tarihi:</label>
                                                    <p class="form-control-plaintext">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="form-label fw-bold">Son Güncelleme:</label>
                                                    <p class="form-control-plaintext">{{ $user->updated_at->format('d.m.Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        İstatistikler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 mb-3">
                                            <div class="border-end">
                                                <h4 class="text-primary mb-1">{{ $user->id }}</h4>
                                                <small class="text-muted">Kullanıcı ID</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <h4 class="text-success mb-1">
                                                @if($user->isAdmin())
                                                    {{ \App\Models\User::where('role', 'admin')->count() }}
                                                @else
                                                    {{ \App\Models\User::where('role', 'personel')->count() }}
                                                @endif
                                            </h4>
                                            <small class="text-muted">Aynı Rolde</small>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="d-grid">
                                        <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Kullanıcı Listesi
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Bilgiler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <small>
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <strong>İpucu:</strong> Bu kullanıcının şifresini değiştirmek için düzenle butonuna tıklayın.
                                        </small>
                                    </div>
                                    
                                    @if($user->id === Auth::id())
                                        <div class="alert alert-warning">
                                            <small>
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Dikkat:</strong> Bu sizin hesabınız. Kendi hesabınızı silemezsiniz.
                                            </small>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
