@extends('layouts.admin')

@section('page-title', 'Yeni Kullanıcı Oluştur')
@section('page-subtitle', 'Sisteme yeni kullanıcı ekleyin')

@section('content')

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Kullanıcı Bilgileri
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('users.store') }}">
                                        @csrf
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" 
                                                       name="name" 
                                                       value="{{ old('name') }}" 
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
                                                       value="{{ old('username') }}" 
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
                                                       value="{{ old('email') }}">
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
                                                    <option value="">Rol Seçin</option>
                                                    @foreach($roles as $key => $value)
                                                        <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
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
                                                <label for="password" class="form-label">Şifre <span class="text-danger">*</span></label>
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" 
                                                       name="password" 
                                                       required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">En az 6 karakter olmalıdır.</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="password_confirmation" class="form-label">Şifre Tekrar <span class="text-danger">*</span></label>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="password_confirmation" 
                                                       name="password_confirmation" 
                                                       required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <strong>Bilgi:</strong>
                                                    <ul class="mb-0 mt-2">
                                                        <li>Kullanıcı adı benzersiz olmalıdır.</li>
                                                        <li>E-posta adresi (boş bırakılabilir) .</li>
                                                        <li>Admin rolü tüm sisteme erişim sağlar.</li>
                                                        <li>Personel rolü sınırlı erişim sağlar.</li>
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
                                                Kullanıcı Oluştur
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
@endsection
