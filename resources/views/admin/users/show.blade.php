@extends('layouts.admin')

@section('page-title', 'Kullanıcı Detayları')
@section('page-subtitle', $user->name . ' kullanıcısının detayları')

@section('additional-styles')
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
@endsection

@section('content')

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
@endsection
