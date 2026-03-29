@extends('layouts.admin')

@section('page-title', 'Servis Yönetimi')
@section('page-subtitle', 'Bakım servislerini yönetin')

@section('content')

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>
                                Servis Listesi
                            </h5>
                            <a href="{{ route('bakim.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Yeni Servis
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Plaka Arama -->
                            <div class="mb-4">
                                <form method="GET" action="{{ route('bakim.index') }}" class="row g-3">
                                    <div class="col-12 col-md-10">
                                        <input type="text" 
                                               name="search" 
                                               class="form-control form-control-lg" 
                                               placeholder="Plaka ile ara..." 
                                               value="{{ request('search') }}"
                                               autofocus>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <button type="submit" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-search me-2"></i>
                                            Ara
                                        </button>
                                    </div>
                                    @if(request('search'))
                                        <div class="col-12">
                                            <a href="{{ route('bakim.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-2"></i>
                                                Aramayı Temizle
                                            </a>
                                        </div>
                                    @endif
                                </form>
                            </div>

                            <!-- Services Table - Desktop -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Plaka</th>
                                            <th>Müşteri</th>
                                            <th>Telefon</th>
                                            <th>Bakım Durumu</th>
                                            <th>Ödeme Durumu</th>
                                            <th>Toplam Ücret</th>
                                            <th>Tamamlayan Personel</th>
                                            <th>Bakım Notu</th>
                                            <th>Bakım Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bakimlar as $bakim)
                                            <tr>
                                                <td>{{ $bakim->id }}</td>
                                                <td>
                                                    <strong>{{ $bakim->plaka }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $bakim->sase }}</small>
                                                </td>
                                                <td>{{ $bakim->musteri_adi }}</td>
                                                <td>{{ $bakim->telefon_numarasi }}</td>
                                                <td>
                                                    @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                        <span class="badge badge-devam">Devam Ediyor</span>
                                                    @else
                                                        <span class="badge badge-tamamlandi">Tamamlandı</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                        <span class="badge bg-warning">Bakım Devam Ediyor</span>
                                                    @elseif($bakim->odeme_durumu == 0)
                                                        <span class="badge badge-odeme-bekliyor">Ödeme Bekliyor</span>
                                                    @else
                                                        <span class="badge badge-odeme-alindi">Ödeme Alındı</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        @php
                                                            $parcaToplami = $bakim->degisecekParcalar->sum(function($parca) { return $parca->adet * $parca->birim_fiyat; });
                                                            $genelToplam = $parcaToplami + ($bakim->iscilik_ucreti ?? 0);
                                                        @endphp
                                                        <strong>{{ number_format($genelToplam, 2) }} ₺</strong>
                                                        @if($bakim->iscilik_ucreti > 0)
                                                            <br><small class="text-muted">
                                                                Parça: {{ number_format($parcaToplami, 2) }} ₺<br>
                                                                İşçilik: {{ number_format($bakim->iscilik_ucreti ?? 0, 2) }} ₺
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $bakim->tamamlayanPersonel->name ?? '-' }}</td>
                                                <td>
                                                    @if($bakim->tamamlanma_notu)
                                                        <span class="badge bg-info" 
                                                              data-bs-toggle="tooltip" 
                                                              data-bs-placement="top" 
                                                              title="{{ $bakim->tamamlanma_notu }}">
                                                            <i class="fas fa-sticky-note me-1"></i>
                                                            Not Var
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $bakim->bakim_tarihi->format('d.m.Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('bakim.show', $bakim) }}" 
                                                           class="btn btn-sm btn-outline-info" 
                                                           title="Görüntüle">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('bakim.edit', $bakim) }}" 
                                                           class="btn btn-sm btn-outline-warning" 
                                                           title="Düzenle">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                     
                                                        <form method="POST" 
                                                              action="{{ route('bakim.destroy', $bakim) }}" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Bu servis kaydını silmek istediğinizden emin misiniz?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-outline-danger" 
                                                                    title="Sil">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-cogs fa-3x mb-3"></i>
                                                        <p>Henüz servis kaydı bulunmuyor.</p>
                                                        <a href="{{ route('bakim.create') }}" class="btn btn-primary">
                                                            <i class="fas fa-plus me-2"></i>
                                                            İlk Servis Kaydını Oluştur
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Services Cards - Mobile -->
                            <div class="d-md-none">
                                @forelse($bakimlar as $bakim)
                                    <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <strong>{{ $bakim->plaka }}</strong>
                                                <small class="text-muted">#{{ $bakim->id }}</small>
                                            </h6>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('bakim.show', $bakim) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Görüntüle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('bakim.edit', $bakim) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" 
                                                      action="{{ route('bakim.destroy', $bakim) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bu servis kaydını silmek istediğinizden emin misiniz?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Sil">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-1"><strong>Müşteri:</strong> {{ $bakim->musteri_adi }}</p>
                                                    <p class="mb-1"><strong>Telefon:</strong> {{ $bakim->telefon_numarasi }}</p>
                                                    <p class="mb-1"><strong>Şase:</strong> {{ $bakim->sase }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1"><strong>Bakım Durumu:</strong>
                                                        @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                            <span class="badge badge-devam">Devam Ediyor</span>
                                                        @else
                                                            <span class="badge badge-tamamlandi">Tamamlandı</span>
                                                        @endif
                                                    </p>
                                                    <p class="mb-1"><strong>Ödeme:</strong>
                                                        @if($bakim->bakim_durumu == 'Devam Ediyor')
                                                            <span class="badge bg-warning">Bakım Devam Ediyor</span>
                                                        @elseif($bakim->odeme_durumu == 0)
                                                            <span class="badge badge-odeme-bekliyor">Ödeme Bekliyor</span>
                                                        @else
                                                            <span class="badge badge-odeme-alindi">Ödeme Alındı</span>
                                                        @endif
                                                    </p>
                                                    <p class="mb-1"><strong>Tarih:</strong> {{ $bakim->bakim_tarihi->format('d.m.Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <p class="mb-1"><strong>Toplam Ücret:</strong> 
                                                        <span class="text-success fw-bold">{{ number_format(($bakim->ucret ?? 0) + ($bakim->iscilik_ucreti ?? 0), 2) }} ₺</span>
                                                    </p>
                                                    @if($bakim->iscilik_ucreti > 0)
                                                        <small class="text-muted">
                                                            Parça: {{ number_format($bakim->ucret ?? 0, 2) }} ₺ | 
                                                            İşçilik: {{ number_format($bakim->iscilik_ucreti ?? 0, 2) }} ₺
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($bakim->tamamlayanPersonel)
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <p class="mb-1"><strong>Tamamlayan:</strong> {{ $bakim->tamamlayanPersonel->name }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($bakim->tamamlanma_notu)
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <p class="mb-1"><strong>Not:</strong> {{ $bakim->tamamlanma_notu }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-cogs fa-3x mb-3"></i>
                                            <p>Henüz servis kaydı bulunmuyor.</p>
                                            <a href="{{ route('bakim.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                İlk Servis Kaydını Oluştur
                                            </a>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            @if($bakimlar->hasPages())
                                <div class="pagination-container">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <small class="text-muted">
                                                Toplam {{ $bakimlar->total() }} kayıttan 
                                                {{ $bakimlar->firstItem() ?? 0 }}-{{ $bakimlar->lastItem() ?? 0 }} arası gösteriliyor
                                            </small>
                                        </div>
                                        <div class="pagination-links">
                                            {{ $bakimlar->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
@endsection

@section('additional-scripts')
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endsection
