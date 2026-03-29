@extends('layouts.admin')

@section('page-title', 'Fatura Çıktı Ayarları')
@section('page-subtitle', 'Fatura çıktı ayarlarını yönetin')

@section('additional-styles')
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
@endsection

@section('content')

                    <div class="row">

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
@endsection

@section('additional-scripts')
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
@endsection
