@extends('layouts.admin')

@section('page-title', 'Admin Paneli')

@section('content')
        <!-- Stats Cards -->
        <div class="row stats-row mb-4">
            <div class="col-6 col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-3"></i>
                        <div class="stat-number">{{ \App\Models\User::count() }}</div>
                        <div>Toplam Kullanıcı</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-cogs fa-2x mb-3"></i>
                        <div class="stat-number">{{ \App\Models\Bakim::count() }}</div>
                        <div>Servis Kayıtları</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-3"></i>
                        <div class="stat-number">{{ \App\Models\Bakim::where('bakim_durumu', 'Devam Ediyor')->count() }}</div>
                        <div>Bekleyen Servisler</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x mb-3"></i>
                        <div class="stat-number">{{ \App\Models\Bakim::where('bakim_durumu', 'Tamamlandı')->count() }}</div>
                        <div>Tamamlanan Servisler</div>
                    </div>
                </div>
            </div>
        </div>

                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history me-2"></i>
                                        Son Aktiviteler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        @forelse($recentActivities as $activity)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $activity->description }}</h6>
                                                    <small class="text-muted">
                                                        {{ $activity->user->name ?? 'Sistem' }} - 
                                                        {{ $activity->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                <span class="badge 
                                                    @if($activity->type == 'bakim_created') bg-primary
                                                    @elseif($activity->type == 'bakim_completed') bg-success
                                                    @elseif($activity->type == 'bakim_deleted') bg-danger
                                                    @elseif($activity->type == 'user_created') bg-info
                                                    @elseif($activity->type == 'user_deleted') bg-warning
                                                    @elseif($activity->type == 'payment_approved') bg-success
                                                    @else bg-secondary
                                                    @endif">
                                                    @if($activity->type == 'bakim_created') Yeni Servis
                                                    @elseif($activity->type == 'bakim_completed') Tamamlandı
                                                    @elseif($activity->type == 'bakim_deleted') Servis Silindi
                                                    @elseif($activity->type == 'user_created') Kullanıcı Oluşturuldu
                                                    @elseif($activity->type == 'user_deleted') Kullanıcı Silindi
                                                    @elseif($activity->type == 'payment_approved') Ödeme Onaylandı
                                                    @else Aktivite
                                                    @endif
                                                </span>
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Henüz aktivite bulunmuyor.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Hızlı İşlemler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2 quick-actions">
                        <a href="{{ route('bakim.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Yeni Servis Ekle
                        </a>
                        <a href="{{ route('users.create') }}" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>
                            Kullanıcı Ekle
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>
                            Kullanıcıları Yönet
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-info">
                            <i class="fas fa-chart-line me-2"></i>
                            Rapor Görüntüle
                        </a>
                        <a href="{{ route('invoice-settings.index') }}" class="btn btn-warning">
                            <i class="fas fa-file-invoice me-2"></i>
                            Fatura Ayarları
                        </a>
                    </div>
                </div>
            </div>
        </div>
@endsection
