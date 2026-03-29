@extends('layouts.admin')

@section('title', 'Aktivite Logları')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Aktivite Logları
                    </h5>
                    <div>
                        <a href="{{ route('logs.statistics') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-chart-bar me-1"></i> İstatistikler
                        </a>
                        <a href="{{ route('logs.login') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i> Giriş Logları
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Filtreler --}}
                    <form method="GET" action="{{ route('logs.activity') }}" class="mb-4">
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
                                <label class="form-label small">Tip</label>
                                <select name="type" class="form-select form-select-sm">
                                    <option value="">Tümü</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">IP Adresi</label>
                                <input type="text" name="ip_address" class="form-control form-control-sm" 
                                       value="{{ request('ip_address') }}" placeholder="IP ara...">
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
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-11">
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       value="{{ request('search') }}" 
                                       placeholder="Açıklama, URL veya IP'de ara...">
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('logs.activity') }}" class="btn btn-secondary btn-sm w-100">
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
                                    <th>Tip</th>
                                    <th>Açıklama</th>
                                    <th>IP</th>
                                    <th>Method</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td class="text-nowrap">
                                            <small>{{ $log->created_at->format('d.m.Y H:i:s') }}</small>
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <small>{{ $log->user->name }}</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $log->type }}</span>
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($log->description, 100) }}</small>
                                        </td>
                                        <td>
                                            <small><code>{{ $log->ip_address }}</code></small>
                                        </td>
                                        <td>
                                            @if($log->request_method)
                                                <span class="badge 
                                                    @if($log->request_method == 'GET') bg-info
                                                    @elseif($log->request_method == 'POST') bg-success
                                                    @elseif($log->request_method == 'PUT' || $log->request_method == 'PATCH') bg-warning
                                                    @elseif($log->request_method == 'DELETE') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ $log->request_method }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('logs.show', $log->id) }}" 
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

