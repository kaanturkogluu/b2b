@extends('layouts.admin')

@section('title', 'Log İstatistikleri')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Log İstatistikleri
                    </h5>
                    <div>
                        <a href="{{ route('logs.activity') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-history me-1"></i> Aktivite Logları
                        </a>
                        <a href="{{ route('logs.login') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i> Giriş Logları
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Giriş İstatistikleri (Son 7 Gün) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Son 7 Günlük Giriş İstatistikleri</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tarih</th>
                                    <th>Toplam Giriş</th>
                                    <th>Başarılı</th>
                                    <th>Başarısız</th>
                                    <th>Başarı Oranı</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginStats as $stat)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($stat->date)->format('d.m.Y') }}</td>
                                        <td><strong>{{ $stat->total }}</strong></td>
                                        <td><span class="text-success">{{ $stat->successful }}</span></td>
                                        <td><span class="text-danger">{{ $stat->failed }}</span></td>
                                        <td>
                                            @php
                                                $successRate = $stat->total > 0 ? ($stat->successful / $stat->total) * 100 : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar 
                                                    {{ $successRate >= 80 ? 'bg-success' : ($successRate >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $successRate }}%">
                                                    {{ number_format($successRate, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Veri bulunamadı</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- En Aktif Kullanıcılar (Son 30 Gün) --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">En Aktif Kullanıcılar (Son 30 Gün)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kullanıcı</th>
                                    <th>Aktivite Sayısı</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeUsers as $index => $activity)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($activity->user)
                                                {{ $activity->user->name }}<br>
                                                <small class="text-muted">({{ $activity->user->username }})</small>
                                            @else
                                                <span class="text-muted">Silinmiş Kullanıcı</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $activity->activity_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Veri bulunamadı</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- En Çok Kullanılan IP Adresleri (Son 7 Gün) --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">En Çok Kullanılan IP Adresleri (Son 7 Gün)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>IP Adresi</th>
                                    <th>İstek Sayısı</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topIPs as $index => $ipData)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><code>{{ $ipData->ip_address }}</code></td>
                                        <td>
                                            <span class="badge bg-info">{{ $ipData->request_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Veri bulunamadı</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Başarısız Giriş Denemeleri (Son 24 Saat) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Son 24 Saatteki Başarısız Giriş Denemeleri
                    </h6>
                </div>
                <div class="card-body">
                    @if($recentFailedLogins->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Dikkat!</strong> Son 24 saatte {{ $recentFailedLogins->count() }} başarısız giriş denemesi tespit edildi.
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Tarih/Saat</th>
                                    <th>Kullanıcı Adı</th>
                                    <th>IP Adresi</th>
                                    <th>Hata Nedeni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentFailedLogins as $log)
                                    <tr>
                                        <td class="text-nowrap">
                                            <small>{{ $log->created_at->format('d.m.Y H:i:s') }}</small>
                                        </td>
                                        <td>
                                            <code>{{ $log->username }}</code>
                                        </td>
                                        <td>
                                            <code>{{ $log->ip_address }}</code>
                                        </td>
                                        <td>
                                            <small class="text-danger">{{ $log->failure_reason }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-success py-4">
                                            <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                                            Son 24 saatte başarısız giriş denemesi yok!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

