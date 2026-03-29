@extends('layouts.app')

@section('title', 'Personel Paneli - MotoJet Servis')
@section('body-class', 'theme-staff')
@section('user-role', 'Personel')
@section('user-badge-class', 'bg-success')
@section('sidebar-gradient', 'linear-gradient(135deg, #28a745 0%, #20c997 100%)')
@section('stat-card-gradient', 'linear-gradient(135deg, #28a745 0%, #20c997 100%)')
@section('btn-primary-gradient', 'linear-gradient(135deg, #28a745 0%, #20c997 100%)')
@section('mobile-menu-color', '#28a745')

@section('sidebar-nav')
    <a class="nav-link @if(request()->routeIs('staff.dashboard')) active @endif" href="{{ route('staff.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>
        Ana Sayfa
    </a>
    <a class="nav-link @if(request()->routeIs('staff.bakim.*')) active @endif" href="{{ route('staff.bakim.index') }}">
        <i class="fas fa-cogs me-2"></i>
        Tüm Bakımlar
    </a>
@endsection

@section('additional-styles')
    .task-item {
        border-left: 4px solid #28a745;
        padding-left: 1rem;
        margin-bottom: 1rem;
    }
    .priority-high {
        border-left-color: #dc3545;
    }
    .priority-medium {
        border-left-color: #ffc107;
    }
    .priority-low {
        border-left-color: #28a745;
    }
    
    /* Mobile-specific improvements */
    @media (max-width: 767px) {
        .task-item {
            padding-left: 0.5rem;
            margin-bottom: 0.75rem;
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
