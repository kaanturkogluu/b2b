@extends('layouts.admin')

@section('title', 'Giriş Logları')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Giriş/Çıkış Logları
                    </h5>
                    <div>
                        <a href="{{ route('logs.statistics') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-chart-bar me-1"></i> İstatistikler
                        </a>
                        <a href="{{ route('logs.activity') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-history me-1"></i> Aktivite Logları
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Filtreler --}}
                    <form method="GET" action="{{ route('logs.login') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small">Kullanıcı</label>
                                <select name="user_id" class="form-select form-select-sm">
                                    <option value="">Tümü</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->username }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Olay Tipi</label>
                                <select name="event_type" class="form-select form-select-sm">
                                    <option value="">Tümü</option>
                                    <option value="login" {{ request('event_type') == 'login' ? 'selected' : '' }}>Giriş</option>
                                    <option value="logout" {{ request('event_type') == 'logout' ? 'selected' : '' }}>Çıkış</option>
                                    <option value="failed_login" {{ request('event_type') == 'failed_login' ? 'selected' : '' }}>Başarısız Giriş</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Durum</label>
                                <select name="success" class="form-select form-select-sm">
                                    <option value="">Tümü</option>
                                    <option value="1" {{ request('success') == '1' ? 'selected' : '' }}>Başarılı</option>
                                    <option value="0" {{ request('success') == '0' ? 'selected' : '' }}>Başarısız</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Başlangıç</label>
                                <input type="date" name="date_from" class="form-control form-control-sm" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Bitiş</label>
                                <input type="date" name="date_to" class="form-control form-control-sm" 
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-11">
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       value="{{ request('search') }}" 
                                       placeholder="Kullanıcı adı, IP veya hata mesajında ara...">
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('logs.login') }}" class="btn btn-secondary btn-sm w-100">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Loglar --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Tarih/Saat</th>
                                    <th>Kullanıcı</th>
                                    <th>Olay</th>
                                    <th>IP Adresi</th>
                                    <th>Durum</th>
                                    <th>Hata Nedeni</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr class="{{ !$log->success ? 'table-danger' : '' }}">
                                        <td class="text-nowrap">
                                            <small>{{ $log->created_at->format('d.m.Y H:i:s') }}</small>
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <small>{{ $log->user->name }}</small><br>
                                                <small class="text-muted">({{ $log->username }})</small>
                                            @else
                                                <small class="text-muted">{{ $log->username ?: '-' }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->event_type == 'login')
                                                <span class="badge bg-success">Giriş</span>
                                            @elseif($log->event_type == 'logout')
                                                <span class="badge bg-secondary">Çıkış</span>
                                            @else
                                                <span class="badge bg-danger">Başarısız Giriş</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small><code>{{ $log->ip_address }}</code></small>
                                        </td>
                                        <td>
                                            @if($log->success)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-danger">{{ $log->failure_reason }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('logs.show-login', $log->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Detay">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                            Kayıt bulunamadı
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

