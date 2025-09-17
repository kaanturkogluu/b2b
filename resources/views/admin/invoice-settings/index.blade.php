<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura Çıktı Ayarları - Motojet Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            min-height: 100vh;
            display: flex;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.2);
            border-right: 3px solid white;
        }
        
        .main-content {
            flex: 1;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 1px solid #e9ecef;
        }
        
        .content {
            padding: 30px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 20px;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .preview-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .preview-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
        }
        
        .preview-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h4><i class="fas fa-cogs me-2"></i>Admin Panel</h4>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                <li><a href="{{ route('bakim.index') }}"><i class="fas fa-cogs me-2"></i>Servisler</a></li>
                <li><a href="{{ route('users.index') }}"><i class="fas fa-users me-2"></i>Kullanıcılar</a></li>
                <li><a href="{{ route('reports.index') }}"><i class="fas fa-chart-line me-2"></i>Raporlar</a></li>
                <li><a href="{{ route('invoice-settings.index') }}" class="active"><i class="fas fa-file-invoice me-2"></i>Fatura Ayarları</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-white p-0 w-100 text-start" style="border: none; background: none;">
                            <i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Fatura Çıktı Ayarları</h2>
                        <p class="text-muted mb-0">Fatura çıktılarında görünecek şirket bilgilerini düzenleyin</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary">Admin</span>
                    </div>
                </div>
            </div>

            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-invoice me-2"></i>
                                    Fatura Bilgileri
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('invoice-settings.update') }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="company_name" class="form-label">Şirket Adı</label>
                                            <input type="text" 
                                                   class="form-control @error('company_name') is-invalid @enderror" 
                                                   id="company_name" 
                                                   name="company_name" 
                                                   value="{{ old('company_name', $settings->company_name) }}" 
                                                   required>
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Telefon</label>
                                            <input type="text" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="{{ old('phone', $settings->phone) }}" 
                                                   required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Adres</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" 
                                                  name="address" 
                                                  rows="3" 
                                                  required>{{ old('address', $settings->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="city" class="form-label">Şehir</label>
                                            <input type="text" 
                                                   class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" 
                                                   name="city" 
                                                   value="{{ old('city', $settings->city) }}" 
                                                   required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">E-posta</label>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email', $settings->email) }}" 
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('bakim.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Geri Dön
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Ayarları Kaydet
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="preview-section">
                            <h6 class="preview-title">
                                <i class="fas fa-eye me-2"></i>Önizleme
                            </h6>
                            <div class="preview-content">
                                <div class="text-center mb-3">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-height: 40px;">
                                </div>
                                <div class="text-center">
                                    <strong id="preview-company">{{ $settings->company_name }}</strong><br>
                                    <small id="preview-address">{{ $settings->address }}</small><br>
                                    <small id="preview-city">{{ $settings->city }}</small><br>
                                    <small id="preview-phone">{{ $settings->phone }}</small><br>
                                    <small id="preview-email">{{ $settings->email }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Live preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = ['company_name', 'address', 'city', 'phone', 'email'];
            const previews = ['preview-company', 'preview-address', 'preview-city', 'preview-phone', 'preview-email'];
            
            inputs.forEach((inputId, index) => {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previews[index]);
                
                input.addEventListener('input', function() {
                    preview.textContent = this.value;
                });
            });
        });
    </script>
</body>
</html>
