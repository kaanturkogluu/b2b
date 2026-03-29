@extends('layouts.staff')

@section('page-title', 'Personel Paneli')

@section('content')
                    <!-- Welcome Card -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="fas fa-motorcycle fa-4x text-success mb-4"></i>
                                    <h3 class="mb-3">Hoş Geldiniz, {{ $user->name }}!</h3>
                                    <p class="text-muted mb-4">Tüm bakım kayıtlarını görüntüleyebilir ve onaylayabilirsiniz.</p>
                                    <a href="{{ route('staff.bakim.index') }}" class="btn btn-success btn-lg">
                                        <i class="fas fa-cogs me-2"></i>
                                        Tüm Bakımları Görüntüle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
