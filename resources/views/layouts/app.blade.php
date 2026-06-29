<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ $appName }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --tz-green: #1E9048;
            --tz-green-dark: #166B36;
            --tz-yellow: #FFC400;
            --tz-gold: #FFD700;
            --tz-black: #1C1C1C;
            --tz-blue: #1DA1D4;
            --tz-blue-dark: #167BA3;
            --sidebar-width: 260px;
            --sidebar-collapsed: 70px;
        }

        * { font-family: 'Inter', sans-serif; }

        html, body {
            background-color: #f5f7fa;
            color: #333;
            overflow-x: hidden;
        }

        /* Prevent body scroll when mobile sidebar is open */
        body.sidebar-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
            height: 100%;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            height: 100dvh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--tz-black);
            color: #fff;
            z-index: 1040;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
            scrollbar-width: thin;
            scrollbar-color: var(--tz-green) transparent;
            -webkit-overflow-scrolling: touch;
        }

        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: var(--tz-green); border-radius: 5px; }

        .sidebar-brand {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 60px;
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            background: var(--tz-green);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .sidebar-brand-text small {
            font-size: 0.7rem;
            opacity: 0.7;
            font-weight: 400;
        }

        .nav-menu { padding: 0.75rem 0; }

        .nav-section {
            padding: 0.5rem 1.25rem 0.25rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }

        .nav-link {
            padding: 0.65rem 1.25rem;
            color: rgba(255,255,255,0.75);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-size: 0.88rem;
            cursor: pointer;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(30, 144, 72, 0.15);
            border-left-color: var(--tz-green);
        }

        .nav-link i { font-size: 1rem; min-width: 20px; text-align: center; }

        /* ===== SIDEBAR COLLAPSE BUTTON ===== */
        .sidebar-collapse-btn {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-left: auto;
            flex-shrink: 0;
            padding: 0;
        }

        .sidebar-collapse-btn:hover {
            background: var(--tz-green);
            color: #fff;
            border-color: var(--tz-green);
        }

        .sidebar-collapse-btn i {
            min-width: auto;
            transition: transform 0.3s;
        }

        /* ===== COLLAPSED SIDEBAR STATE ===== */
        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed);
        }

        body.sidebar-collapsed .sidebar-brand-text,
        body.sidebar-collapsed .nav-link span,
        body.sidebar-collapsed .nav-section,
        body.sidebar-collapsed .submenu-toggle {
            opacity: 0;
            visibility: hidden;
            width: 0;
            display: none;
        }

        body.sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding: 1.25rem 0.5rem;
        }

        body.sidebar-collapsed .sidebar-brand-icon {
            margin: 0 auto;
        }

        body.sidebar-collapsed .sidebar-collapse-btn {
            position: absolute;
            right: -13px;
            top: 18px;
            background: var(--tz-green);
            color: #fff;
            border-color: var(--tz-green);
            z-index: 1050;
        }

        body.sidebar-collapsed .sidebar-collapse-btn i {
            transform: rotate(180deg);
        }

        body.sidebar-collapsed .nav-link {
            justify-content: center;
            padding: 0.65rem 0.5rem;
            border-left: none;
        }

        body.sidebar-collapsed .nav-link i {
            font-size: 1.2rem;
            min-width: auto;
        }

        body.sidebar-collapsed .nav-menu {
            padding: 0.75rem 0.5rem;
        }

        body.sidebar-collapsed .has-submenu .submenu {
            display: none !important;
        }

        body.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Tooltip for collapsed sidebar */
        body.sidebar-collapsed .nav-link {
            position: relative;
        }

        body.sidebar-collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--tz-black);
            color: #fff;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            white-space: nowrap;
            z-index: 1060;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        body.sidebar-collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: calc(100% + 4px);
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: var(--tz-black);
            z-index: 1060;
        }

        /* Submenu styles */
        .submenu {
            background: rgba(0,0,0,0.2);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .submenu.show {
            max-height: 500px;
            transition: max-height 0.4s ease-in;
        }

        .submenu .nav-link {
            padding-left: 3rem;
            font-size: 0.82rem;
            border-left: none;
        }

        .submenu .nav-link:hover { background: rgba(30, 144, 72, 0.1); }

        .has-submenu .submenu-toggle {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.3s;
        }

        .has-submenu.open .submenu-toggle { transform: rotate(180deg); }

        /* ===== SIDEBAR OVERLAY ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1035;
            opacity: 0;
            transition: opacity 0.3s;
            cursor: pointer;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            min-height: 100dvh;
            transition: margin-left 0.3s ease-in-out;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .topbar-left { display: flex; align-items: center; gap: 1rem; }
        .topbar-title { font-size: 1.1rem; font-weight: 600; color: var(--tz-black); }

        .topbar-right { display: flex; align-items: center; gap: 0.75rem; }

        .btn-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #666;
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
        }

        .btn-icon:hover { background: #f5f5f5; color: var(--tz-green); }

        /* Sidebar expand button in topbar (visible when sidebar is collapsed) */
        .sidebar-expand-btn {
            display: none !important;
        }

        body.sidebar-collapsed .sidebar-expand-btn {
            display: inline-flex !important;
            margin-right: 0.5rem;
        }

        body.sidebar-collapsed .topbar-left {
            padding-left: 0.5rem;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: #dc3545;
            color: #fff;
            font-size: 0.6rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-dropdown:hover { background: #f5f5f5; }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--tz-green);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .user-info { line-height: 1.2; }
        .user-name { font-size: 0.8rem; font-weight: 600; color: #333; }
        .user-role { font-size: 0.7rem; color: #888; }

        /* ===== PAGE CONTENT ===== */
        .page-content { padding: 1.5rem; }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .page-header h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--tz-black);
            margin: 0;
        }

        /* ===== CARDS ===== */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: box-shadow 0.2s;
        }

        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: #333;
            border-radius: 12px 12px 0 0 !important;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-icon.green { background: rgba(30, 144, 72, 0.1); color: var(--tz-green); }
        .stat-icon.blue { background: rgba(29, 161, 212, 0.1); color: var(--tz-blue); }
        .stat-icon.yellow { background: rgba(255, 196, 0, 0.1); color: #c79100; }
        .stat-icon.red { background: rgba(198, 40, 40, 0.1); color: #c62828; }
        .stat-icon.purple { background: rgba(106, 27, 154, 0.1); color: #6a1b9a; }
        .stat-icon.orange { background: rgba(245, 124, 0, 0.1); color: #f57c00; }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--tz-black);
            line-height: 1;
            margin-bottom: 0.25rem;
            word-break: break-word;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #888;
        }

        .stat-change {
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: var(--tz-green);
            border-color: var(--tz-green);
        }

        .btn-primary:hover, .btn-primary:focus {
            background: var(--tz-green-dark);
            border-color: var(--tz-green-dark);
        }

        .btn-outline-primary {
            color: var(--tz-green);
            border-color: var(--tz-green);
        }

        .btn-outline-primary:hover {
            background: var(--tz-green);
            border-color: var(--tz-green);
        }

        .btn-info {
            background: var(--tz-blue);
            border-color: var(--tz-blue);
            color: #fff;
        }

        .btn-info:hover { background: var(--tz-blue-dark); border-color: var(--tz-blue-dark); color: #fff; }

        /* ===== TABLES ===== */
        .table { font-size: 0.85rem; }

        .table thead th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #666;
            border-bottom: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        /* ===== FORMS ===== */
        .form-label { font-size: 0.85rem; font-weight: 500; color: #555; }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 0.85rem;
            padding: 0.55rem 0.85rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--tz-green);
            box-shadow: 0 0 0 0.2rem rgba(30, 144, 72, 0.15);
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.8rem;
            color: #999;
            border-top: 1px solid #eee;
            margin-top: 2rem;
        }

        /* ===== ALERTS ===== */
        .alert { border-radius: 10px; border: none; font-size: 0.85rem; }

        /* ===== BADGE ===== */
        .badge { font-size: 0.75rem; font-weight: 500; padding: 0.4em 0.6em; border-radius: 6px; }

        /* ===== BREADCRUMB ===== */
        .breadcrumb { font-size: 0.8rem; }
        .breadcrumb-item a { color: var(--tz-green); text-decoration: none; }

        /* ===== TOAST ===== */
        .toast-container { z-index: 9999; }

        /* ===== MODAL ===== */
        .modal-content { border-radius: 12px; border: none; }
        .modal-header { border-bottom: 1px solid #f0f0f0; }

        /* ===== SPINNER ===== */
        .spinner-border-sm { width: 1rem; height: 1rem; }

        /* ============================================ */
        /* ===== RESPONSIVE: Tablet & Mobile ========= */
        /* ============================================ */
        @media (max-width: 991.98px) {
            /* Disable collapsed state on mobile */
            body.sidebar-collapsed .sidebar {
                width: var(--sidebar-width);
            }

            body.sidebar-collapsed .main-content {
                margin-left: 0;
            }

            body.sidebar-collapsed .sidebar-brand-text,
            body.sidebar-collapsed .nav-link span,
            body.sidebar-collapsed .nav-section,
            body.sidebar-collapsed .submenu-toggle {
                display: block;
                opacity: 1;
                visibility: visible;
                width: auto;
            }

            body.sidebar-collapsed .nav-link {
                justify-content: flex-start;
                padding: 0.65rem 1.25rem;
            }

            body.sidebar-collapsed .sidebar-collapse-btn {
                display: none;
            }

            /* Sidebar hidden by default on mobile */
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 2px 0 20px rgba(0,0,0,0.3);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            /* Main content takes full width */
            .main-content {
                margin-left: 0;
            }

            /* Show overlay when sidebar is open */
            .sidebar-overlay {
                display: block;
                opacity: 0;
                pointer-events: none;
            }

            .sidebar-overlay.show {
                opacity: 1;
                pointer-events: auto;
            }

            /* Reduce page padding on mobile */
            .page-content {
                padding: 1rem 0.75rem;
            }

            .page-header {
                margin-bottom: 1rem;
            }

            .page-header h1 {
                font-size: 1.15rem;
            }

            /* Topbar adjustments */
            .topbar {
                padding: 0 0.75rem;
            }

            .topbar-title {
                font-size: 1rem;
            }

            /* Stat card adjustments */
            .stat-card {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            /* Ensure tables scroll horizontally */
            .table-responsive {
                border-radius: 12px;
                -webkit-overflow-scrolling: touch;
            }

            /* Card body padding reduction */
            .card-body {
                padding: 1rem;
            }

            .card-header {
                padding: 0.875rem 1rem;
            }
        }

        /* ============================================ */
        /* ===== RESPONSIVE: Small Mobile ============== */
        /* ============================================ */
        @media (max-width: 575.98px) {
            .page-content {
                padding: 0.75rem 0.5rem;
            }

            .page-header h1 {
                font-size: 1rem;
            }

            .stat-card {
                padding: 0.875rem;
                gap: 0.75rem;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .stat-value {
                font-size: 1.1rem;
            }

            .stat-label {
                font-size: 0.75rem;
            }

            .topbar {
                padding: 0 0.5rem;
                height: 56px;
            }

            .btn-icon {
                width: 36px;
                height: 36px;
            }

            /* Reduce font sizes in tables */
            .table { font-size: 0.8rem; }
            .table thead th { font-size: 0.75rem; padding: 0.625rem 0.75rem; }
            .table tbody td { padding: 0.625rem 0.75rem; }

            /* Form adjustments */
            .form-control, .form-select {
                font-size: 16px; /* Prevents iOS zoom on focus */
                padding: 0.5rem 0.75rem;
            }

            /* Footer adjustments */
            .footer {
                font-size: 0.75rem;
                padding: 0.75rem 0.5rem;
            }
        }

        /* ============================================ */
        /* ===== RESPONSIVE: Large screens ============= */
        /* ============================================ */
        @media (min-width: 992px) {
            /* Always hide overlay on large screens */
            .sidebar-overlay {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay (mobile only) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        @include('partials.header')

        <!-- Page Content -->
        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Setup AJAX defaults
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        /**
         * Toggle sidebar visibility (mobile only)
         * Also handles body scroll lock to prevent background scrolling
         */
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const body = document.body;

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            body.classList.toggle('sidebar-open');
        }

        /**
         * Toggle sidebar collapse/expand (desktop only)
         * Saves preference to localStorage
         */
        function toggleSidebarCollapse(event) {
            event.preventDefault();
            event.stopPropagation();

            // Only allow on desktop
            if (window.innerWidth < 992) return;

            const body = document.body;
            const isCollapsed = body.classList.toggle('sidebar-collapsed');

            // Save preference
            localStorage.setItem('sidebar-collapsed', isCollapsed ? '1' : '0');
        }

        // Restore sidebar collapse state on page load
        (function() {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === '1';
            if (isCollapsed && window.innerWidth >= 992) {
                document.body.classList.add('sidebar-collapsed');
            }

            // Set data-title attributes for tooltips in collapsed mode
            document.querySelectorAll('.nav-link:not(.has-submenu > .nav-link)').forEach(function(link) {
                const span = link.querySelector('span');
                if (span) {
                    link.setAttribute('data-title', span.textContent.trim());
                }
            });
        })();

        // Handle window resize - reset collapse on mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth < 992) {
                document.body.classList.remove('sidebar-collapsed');
            }
        });

        /**
         * Close sidebar on mobile - used when clicking a nav link
         */
        function closeSidebarMobile() {
            if (window.innerWidth < 992) {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                const body = document.body;

                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        }

        /**
         * Toggle submenu open/closed state
         * Called via inline onclick on submenu toggle links
         */
        function toggleSubmenu(element, event) {
            event.preventDefault();
            event.stopPropagation();

            const hasSubmenu = element.closest('.has-submenu');
            const submenu = hasSubmenu.querySelector('.submenu');

            // Close sibling submenus at the same level (accordion behavior)
            const parent = hasSubmenu.parentElement;
            if (parent) {
                parent.querySelectorAll(':scope > .has-submenu.open').forEach(function(sibling) {
                    if (sibling !== hasSubmenu) {
                        sibling.classList.remove('open');
                        const siblingSubmenu = sibling.querySelector('.submenu');
                        if (siblingSubmenu) siblingSubmenu.classList.remove('show');
                    }
                });
            }

            // Toggle current submenu
            hasSubmenu.classList.toggle('open');
            submenu.classList.toggle('show');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Location cascading
        function loadDistricts(regionId, districtSelectId, callback) {
            if (!regionId) return;
            $.get('/ajax/districts/' + regionId, function(data) {
                let options = '<option value="">Select District</option>';
                data.forEach(function(d) { options += '<option value="' + d.id + '">' + d.name + '</option>'; });
                $('#' + districtSelectId).html(options);
                if (callback) callback();
            });
        }

        function loadWards(districtId, wardSelectId, callback) {
            if (!districtId) return;
            $.get('/ajax/wards/' + districtId, function(data) {
                let options = '<option value="">Select Ward</option>';
                data.forEach(function(w) { options += '<option value="' + w.id + '">' + w.name + '</option>'; });
                $('#' + wardSelectId).html(options);
                if (callback) callback();
            });
        }

        function loadVillages(wardId, villageSelectId, callback) {
            if (!wardId) return;
            $.get('/ajax/villages/' + wardId, function(data) {
                let options = '<option value="">Select Village/Street</option>';
                data.forEach(function(v) { options += '<option value="' + v.id + '">' + v.name + '</option>'; });
                $('#' + villageSelectId).html(options);
                if (callback) callback();
            });
        }

        // ===== SELECT2 SEARCHABLE LOCATION DROPDOWNS =====
        function initSelect2Location(regionId, districtId, wardId, villageId) {
            const r = $('#' + (regionId || 'regionSelect'));
            const d = $('#' + (districtId || 'districtSelect'));
            const w = $('#' + (wardId || 'wardSelect'));
            const v = $('#' + (villageId || 'villageSelect'));

            // Only init Select2 if element exists and is a select
            [r, d, w, v].forEach(function($el) {
                if ($el.length && $el.is('select')) {
                    $el.select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $el.closest('.modal').length ? $el.closest('.modal') : undefined });
                }
            });

            // Rebuild cascading handlers that work with Select2
            r.off('change.select2').on('change.select2', function() {
                const regionVal = $(this).val();
                if (regionVal) {
                    $.get('/ajax/districts/' + regionVal, function(data) {
                        let options = '<option value="">Select District</option>';
                        data.forEach(function(item) { options += '<option value="' + item.id + '">' + item.name + '</option>'; });
                        d.html(options).trigger('change.select2');
                        w.html('<option value="">Select Ward</option>').trigger('change.select2');
                        v.html('<option value="">Select Village/Street</option>').trigger('change.select2');
                    });
                }
            });

            d.off('change.select2').on('change.select2', function() {
                const districtVal = $(this).val();
                if (districtVal) {
                    $.get('/ajax/wards/' + districtVal, function(data) {
                        let options = '<option value="">Select Ward</option>';
                        data.forEach(function(item) { options += '<option value="' + item.id + '">' + item.name + '</option>'; });
                        w.html(options).trigger('change.select2');
                        v.html('<option value="">Select Village/Street</option>').trigger('change.select2');
                    });
                }
            });

            w.off('change.select2').on('change.select2', function() {
                const wardVal = $(this).val();
                if (wardVal) {
                    $.get('/ajax/villages/' + wardVal, function(data) {
                        let options = '<option value="">Select Village/Street</option>';
                        data.forEach(function(item) { options += '<option value="' + item.id + '">' + item.name + '</option>'; });
                        v.html(options).trigger('change.select2');
                    });
                }
            });
        }

        // ===== GEOLOCATION HELPER =====
        function captureGeoLocation(latInputId, lngInputId, btnId) {
            const $btn = btnId ? $('#' + btnId) : null;
            const $lat = $('#' + latInputId);
            const $lng = $('#' + lngInputId);

            function doCapture() {
                if ($btn) {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Capturing...');
                }
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by this browser.');
                    if ($btn) $btn.prop('disabled', false).html('<i class="bi bi-geo-alt me-1"></i>Capture Location');
                    return;
                }
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        $lat.val(position.coords.latitude.toFixed(8));
                        $lng.val(position.coords.longitude.toFixed(8));
                        if ($btn) $btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i>Captured');
                        setTimeout(function() {
                            if ($btn) $btn.html('<i class="bi bi-geo-alt me-1"></i>Capture Location');
                        }, 3000);
                    },
                    function(error) {
                        let msg = 'Unable to retrieve location.';
                        switch(error.code) {
                            case error.PERMISSION_DENIED: msg = 'Location access denied. Please allow location permission.'; break;
                            case error.POSITION_UNAVAILABLE: msg = 'Location information unavailable.'; break;
                            case error.TIMEOUT: msg = 'Location request timed out.'; break;
                        }
                        alert(msg);
                        if ($btn) $btn.prop('disabled', false).html('<i class="bi bi-geo-alt me-1"></i>Capture Location');
                    },
                    { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
                );
            }

            if ($btn) {
                $btn.on('click', function(e) { e.preventDefault(); doCapture(); });
            }

            return { capture: doCapture };
        }
    </script>

    @stack('scripts')
</body>
</html>
