@extends('layouts.admin')

@section('title', 'Log Detay')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Aktivite Log Detayı #{{ $log->id }}
                    </h5>
                    <a href="{{ route('logs.activity') }}" class="btn btn-light btn-sm">
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
                                            {{ $log->user->name }} ({{ $log->user->username }})
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tip:</th>
                                    <td><span class="badge bg-secondary">{{ $log->type }}</span></td>
                                </tr>
                                <tr>
                                    <th>Açıklama:</th>
                                    <td>{{ $log->description }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">İstek Bilgileri</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th style="width: 40%">IP Adresi:</th>
                                    <td><code>{{ $log->ip_address }}</code></td>
                                </tr>
                                <tr>
                                    <th>Session ID:</th>
                                    <td><code>{{ Str::limit($log->session_id, 30) }}</code></td>
                                </tr>
                                <tr>
                                    <th>HTTP Method:</th>
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
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>URL:</th>
                                    <td><small><code>{{ $log->request_url }}</code></small></td>
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

                    @if($log->metadata)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-muted mb-2">Metadata (Ek Bilgiler)</h6>
                                <div class="alert alert-info">
                                    <pre class="mb-0"><code>{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($log->related_type && $log->related_id)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-muted mb-2">İlişkili Kayıt</h6>
                                <div class="alert alert-warning">
                                    <strong>Tip:</strong> {{ $log->related_type }}<br>
                                    <strong>ID:</strong> {{ $log->related_id }}
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

