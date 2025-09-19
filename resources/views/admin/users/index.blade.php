<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetimi - MotoJet Servis</title>
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
        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .badge-staff {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 14px;
            font-weight: bold;
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
                            <h2 class="mb-0">Kullanıcı Yönetimi</h2>
                            <p class="text-muted mb-0">Sistem kullanıcılarını yönetin</p>
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
                                <i class="fas fa-users me-2"></i>
                                Kullanıcı Listesi
                            </h5>
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Yeni Kullanıcı
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Search and Filter -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('users.index') }}" class="d-flex">
                                        <input type="text" 
                                               name="search" 
                                               class="form-control me-2" 
                                               placeholder="Kullanıcı ara..." 
                                               value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route('users.index') }}">
                                        <select name="role" class="form-select" onchange="this.form.submit()">
                                            <option value="">Tüm Roller</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="personel" {{ request('role') == 'personel' ? 'selected' : '' }}>Personel</option>
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-3 text-end">
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh me-2"></i>
                                        Temizle
                                    </a>
                                </div>
                            </div>

                            <!-- Users Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Ad Soyad</th>
                                            <th>Kullanıcı Adı</th>
                                            <th>E-posta</th>
                                            <th>Rol</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                        {{ $user->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <code>{{ $user->username }}</code>
                                                </td>
                                                <td>{{ $user->email ?: '-' }}</td>
                                                <td>
                                                    @if($user->isAdmin())
                                                        <span class="badge badge-admin">Admin</span>
                                                    @else
                                                        <span class="badge badge-staff">Personel</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('users.show', $user) }}" 
                                                           class="btn btn-sm btn-outline-info" 
                                                           title="Görüntüle">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('users.edit', $user) }}" 
                                                           class="btn btn-sm btn-outline-warning" 
                                                           title="Düzenle">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($user->id !== Auth::id())
                                                            <form method="POST" 
                                                                  action="{{ route('users.destroy', $user) }}" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        title="Sil">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-users fa-3x mb-3"></i>
                                                        <p>Henüz kullanıcı bulunmuyor.</p>
                                                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                                                            <i class="fas fa-plus me-2"></i>
                                                            İlk Kullanıcıyı Oluştur
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($users->hasPages())
                                <div class="pagination-container">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <small class="text-muted">
                                                Toplam {{ $users->total() }} kayıttan 
                                                {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} arası gösteriliyor
                                            </small>
                                        </div>
                                        <div class="pagination-links">
                                            {{ $users->appends(request()->query())->links() }}
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
