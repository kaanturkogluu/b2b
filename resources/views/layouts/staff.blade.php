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
@endsection
