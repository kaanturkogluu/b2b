@extends('layouts.app')

@section('title', 'Admin Paneli - MotoJet Servis')
@section('body-class', 'theme-admin')
@section('user-role', 'Admin')
@section('user-badge-class', 'bg-primary')
@section('sidebar-gradient', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)')
@section('stat-card-gradient', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)')
@section('btn-primary-gradient', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)')
@section('mobile-menu-color', '#667eea')

@section('sidebar-nav')
    <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>
        Dashboard
    </a>
    <a class="nav-link @if(request()->routeIs('bakim.*')) active @endif" href="{{ route('bakim.index') }}">
        <i class="fas fa-tools me-2"></i>
        Servis Yönetimi
    </a>
    
    @auth
        @if(auth()->user()->role === 'admin')
            <a class="nav-link @if(request()->routeIs('users.*')) active @endif" href="{{ route('users.index') }}">
                <i class="fas fa-users me-2"></i>
                Kullanıcı Yönetimi
            </a>
            <a class="nav-link @if(request()->routeIs('reports.*')) active @endif" href="{{ route('reports.index') }}">
                <i class="fas fa-chart-line me-2"></i>
                Raporlar
            </a>
            <a class="nav-link @if(request()->routeIs('invoice-settings.*')) active @endif" href="{{ route('invoice-settings.index') }}">
                <i class="fas fa-file-invoice me-2"></i>
                Fatura Ayarları
            </a>
        @endif
    @endauth
@endsection

@section('additional-styles')
    .stats-row .col-md-3 {
        margin-bottom: 1rem;
    }
    .quick-actions .btn {
        margin-bottom: 0.5rem;
        width: 100%;
    }
    @media (min-width: 768px) {
        .quick-actions .btn {
            width: auto;
            margin-bottom: 0;
        }
    }
@endsection
