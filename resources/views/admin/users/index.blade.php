@extends('layouts.admin')

@section('page-title', 'Kullanıcı Yönetimi')
@section('page-subtitle', 'Sistem kullanıcılarını yönetin')

@section('additional-styles')
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
@endsection

@section('content')

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
@endsection
