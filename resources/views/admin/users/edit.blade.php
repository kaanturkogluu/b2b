<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Düzenle - MotoJet Servis</title>
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
                        <a class="nav-link active" href="{{ route('users.index') }}">
                            <i class="fas fa-users me-2"></i>
                            Kullanıcılar
                        </a>
                        <a class="nav-link" href="{{ route('bakim.index') }}">
                            <i class="fas fa-cogs me-2"></i>
                            Servisler
                        </a>
                        <a class="nav-link" href="{{ route('reports.index') }}">
                            <i class="fas fa-chart-line me-2"></i>
                            Raporlar
                        </a>
                        <a class="nav-link" href="{{ route('invoice-settings.index') }}">
                            <i class="fas fa-file-invoice me-2"></i>
                            Fatura Ayarları
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
                            <h2 class="mb-0">Kullanıcı Düzenle</h2>
                            <p class="text-muted mb-0">{{ $user->name }} kullanıcısını düzenleyin</p>
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

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-edit me-2"></i>
                                        Kullanıcı Bilgilerini Düzenle
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('users.update', $user) }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" 
                                                       name="name" 
                                                       value="{{ old('name', $user->name) }}" 
                                                       required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="username" class="form-label">Kullanıcı Adı <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('username') is-invalid @enderror" 
                                                       id="username" 
                                                       name="username" 
                                                       value="{{ old('username', $user->username) }}" 
                                                       required>
                                                @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">E-posta</label>
                                                <input type="email" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" 
                                                       name="email" 
                                                       value="{{ old('email', $user->email) }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Opsiyonel - E-posta adresi girmek zorunlu değildir.</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
                                                <select class="form-select @error('role') is-invalid @enderror" 
                                                        id="role" 
                                                        name="role" 
                                                        required>
                                                    @foreach($roles as $key => $value)
                                                        <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="password" class="form-label">Yeni Şifre</label>
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" 
                                                       name="password">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Şifre değiştirmek istemiyorsanız boş bırakın.</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="password_confirmation" 
                                                       name="password_confirmation">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    <strong>Dikkat:</strong>
                                                    <ul class="mb-0 mt-2">
                                                        <li>Şifre değiştirmek istemiyorsanız şifre alanlarını boş bırakın.</li>
                                                        <li>Kendi hesabınızı silmek için önce başka bir admin hesabı oluşturun.</li>
                                                        <li>Rol değişiklikleri hemen etkili olur.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left me-2"></i>
                                                Geri Dön
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>
                                                Değişiklikleri Kaydet
                                            </button>
                                        </div>
                                    </form>
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
