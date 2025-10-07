@extends('layouts.admin')

@section('title', 'Login Log Detay')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Giriş Log Detayı #{{ $log->id }}
                    </h5>
                    <a href="{{ route('logs.login') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Geri
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Temel Bilgiler</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th style="width: 40%">Tarih/Saat:</th>
                                    <td>{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Kullanıcı:</th>
                                    <td>
                                        @if($log->user)
                                            {{ $log->user->name }}<br>
                                            <small class="text-muted">({{ $log->user->username }})</small>
                                        @else
                                            <span class="text-muted">{{ $log->username ?: '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kullanıcı Adı:</th>
                                    <td><code>{{ $log->username }}</code></td>
                                </tr>
                                <tr>
                                    <th>Olay Tipi:</th>
                                    <td>
                                        @if($log->event_type == 'login')
                                            <span class="badge bg-success">Giriş</span>
                                        @elseif($log->event_type == 'logout')
                                            <span class="badge bg-secondary">Çıkış</span>
                                        @else
                                            <span class="badge bg-danger">Başarısız Giriş</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Durum:</th>
                                    <td>
                                        @if($log->success)
                                            <i class="fas fa-check-circle text-success"></i> Başarılı
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i> Başarısız
                                        @endif
                                    </td>
                                </tr>
                                @if(!$log->success && $log->failure_reason)
                                    <tr>
                                        <th>Hata Nedeni:</th>
                                        <td class="text-danger">{{ $log->failure_reason }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Bağlantı Bilgileri</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th style="width: 40%">IP Adresi:</th>
                                    <td><code>{{ $log->ip_address }}</code></td>
                                </tr>
                                <tr>
                                    <th>Session ID:</th>
                                    <td><code>{{ Str::limit($log->session_id, 30) }}</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($log->user_agent)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-muted mb-2">User Agent</h6>
                                <div class="alert alert-secondary mb-0">
                                    <small><code>{{ $log->user_agent }}</code></small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($log->additional_data)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-muted mb-2">Ek Bilgiler</h6>
                                <div class="alert alert-info">
                                    <pre class="mb-0"><code>{{ $log->additional_data }}</code></pre>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

