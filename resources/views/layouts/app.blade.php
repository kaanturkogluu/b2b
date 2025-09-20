<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MotoJet Servis')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: @yield('sidebar-gradient', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)');
            min-height: 100vh;
            color: white;
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            z-index: 1000;
            transition: left 0.3s ease;
        }
        .sidebar.show {
            left: 0;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .main-content {
            padding: 1rem;
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }
        @media (min-width: 768px) {
            .sidebar {
                position: relative;
                left: 0;
                width: auto;
            }
            .main-content {
                margin-left: 0;
                padding: 2rem;
            }
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .stat-card {
            background: @yield('stat-card-gradient', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)');
            color: white;
        }
        .stat-card .card-body {
            padding: 1.5rem;
        }
        @media (min-width: 768px) {
            .stat-card .card-body {
                padding: 2rem;
            }
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        @media (min-width: 768px) {
            .stat-number {
                font-size: 2.5rem;
            }
        }
        .header {
            background: white;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        @media (min-width: 768px) {
            .header {
                padding: 1rem 2rem;
                margin-bottom: 2rem;
            }
        }
        .mobile-menu-btn {
            display: block;
            background: none;
            border: none;
            color: @yield('mobile-menu-color', '#667eea');
            font-size: 1.5rem;
            padding: 0.5rem;
        }
        @media (min-width: 768px) {
            .mobile-menu-btn {
                display: none;
            }
        }
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        .sidebar-overlay.show {
            display: block;
        }
        .mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: white;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 1rem;
        }
        @media (min-width: 768px) {
            .mobile-header {
                display: none;
            }
        }
        .btn-primary {
            background: @yield('btn-primary-gradient', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)');
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }
        .badge-devam {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }
        .badge-tamamlandi {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .badge-odeme-bekliyor {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        .badge-odeme-alindi {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        /* Touch-friendly improvements */
        .btn, .nav-link, .mobile-menu-btn {
            min-height: 44px;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-sm {
            min-height: 36px;
            min-width: 36px;
        }
        
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }
        
        .card {
            touch-action: manipulation;
        }
        
        .form-control, .form-select {
            min-height: 44px;
            font-size: 16px; /* Prevents zoom on iOS */
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.5em 0.75em;
        }
        
        /* Improved spacing for mobile */
        @media (max-width: 767px) {
            .main-content {
                padding: 0.5rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .btn-group .btn {
                padding: 0.375rem 0.5rem;
            }
        }
        
        @yield('additional-styles')
    </style>
</head>
<body class="@yield('body-class', '')">
    <!-- Mobile Header -->
    <div class="mobile-header">
        <button class="mobile-menu-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h4 class="mb-0">MotoJet Servis</h4>
        <div class="d-flex align-items-center">
            <span class="badge @yield('user-badge-class', 'bg-primary') me-2">@yield('user-role', 'Kullanıcı')</span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="fas fa-motorcycle me-2"></i>
                    MotoJet Servis
                </h4>
                <button class="btn btn-sm btn-outline-light d-md-none" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="nav flex-column">
                @yield('sidebar-nav')
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Desktop Header -->
        <div class="header d-none d-md-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">@yield('page-title', 'MotoJet Servis')</h2>
                <p class="text-muted mb-0">@yield('page-subtitle', 'Hoş geldiniz, ' . auth()->user()->name)</p>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge @yield('user-badge-class', 'bg-primary') me-3">@yield('user-role', 'Kullanıcı')</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Çıkış Yap
                    </button>
                </form>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Close sidebar when clicking on nav links on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });

            // Close sidebar when clicking outside on mobile
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                }
            });
        });
    </script>
    
    @yield('additional-scripts')
</body>
</html>
