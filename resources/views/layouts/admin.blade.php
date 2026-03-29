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
            <div class="nav-item">
                <a class="nav-link @if(request()->routeIs('logs.*')) active @endif" 
                   data-bs-toggle="collapse" 
                   href="#logsMenu" 
                   role="button" 
                   aria-expanded="@if(request()->routeIs('logs.*')) true @else false @endif">
                    <i class="fas fa-history me-2"></i>
                    Sistem Logları
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse @if(request()->routeIs('logs.*')) show @endif" id="logsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('logs.activity')) active @endif" 
                               href="{{ route('logs.activity') }}">
                                <i class="fas fa-list me-2"></i>
                                Aktivite Logları
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('logs.login')) active @endif" 
                               href="{{ route('logs.login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Giriş Logları
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('logs.statistics')) active @endif" 
                               href="{{ route('logs.statistics') }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                İstatistikler
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <a class="nav-link @if(request()->routeIs('invoice-settings.*')) active @endif" href="{{ route('invoice-settings.index') }}">
                <i class="fas fa-file-invoice me-2"></i>
                Fatura Ayarları
            </a>
        @endif
    @endauth
@endsection

@section('additional-styles')
    /* Log Menu Dropdown Styling */
    #logsMenu .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
    }
    
    #logsMenu .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
    }
    
    #logsMenu .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        font-weight: 500;
    }
    
    .nav-item > .nav-link[data-bs-toggle="collapse"] {
        cursor: pointer;
    }
    
    .nav-item > .nav-link[data-bs-toggle="collapse"] .fa-chevron-down {
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }
    
    .nav-item > .nav-link[data-bs-toggle="collapse"][aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }
    
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
    
    /* Mobile-specific improvements */
    @media (max-width: 767px) {
        .stats-row .col-6 {
            margin-bottom: 1rem;
        }
        .stat-number {
            font-size: 1.5rem;
        }
        .card-header h5 {
            font-size: 1rem;
        }
        .table-responsive {
            border: none;
        }
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
        }
        .form-control, .form-select {
            font-size: 16px; /* Prevents zoom on iOS */
        }
        .mobile-header h5 {
            font-size: 1.1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
    }
    
    /* Touch-friendly improvements */
    .btn, .nav-link {
        min-height: 44px;
        display: flex;
        align-items: center;
    }
    
    .btn-sm {
        min-height: 36px;
    }
    
    /* Improved spacing for mobile cards */
    @media (max-width: 767px) {
        .card {
            margin-bottom: 1rem;
        }
        .card-body {
            padding: 1rem;
        }
        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        .row > * {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
@endsection
