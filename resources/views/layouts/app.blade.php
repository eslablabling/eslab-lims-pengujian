<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LIMS Dashboard') | PT Envirotama Solusindo</title>
    <!-- PWA & Shortcut Support (Android, iOS, Desktop) -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=3">
    <link rel="manifest" href="{{ url('/manifest.json') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=3">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ESLab LIMS">
    <meta name="theme-color" content="#0f172a">
    <link rel="stylesheet" href="{{ asset('mobile-nav.css') }}">
    <link rel="stylesheet" href="{{ asset('pwa-install.css') }}">
    <script src="{{ asset('vendor/chartjs/chart.umd.js') }}"></script>
    <script src="{{ asset('vendor/xlsx/xlsx.full.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <script>
        (function() {
            var mode = localStorage.getItem('eslab_view_mode');
            if (mode === 'desktop') {
                document.documentElement.classList.add('force-desktop-mode');
                document.addEventListener('DOMContentLoaded', function() {
                    var meta = document.querySelector('meta[name="viewport"]');
                    if (meta) meta.setAttribute('content', 'width=1280, initial-scale=0.35, maximum-scale=3.0, user-scalable=yes');
                    updateViewModeButton(true);
                });
            }
        })();

        function toggleDesktopMobileMode() {
            var isCurrentlyDesktop = document.documentElement.classList.contains('force-desktop-mode');
            var meta = document.querySelector('meta[name="viewport"]');
            
            if (isCurrentlyDesktop) {
                document.documentElement.classList.remove('force-desktop-mode');
                if (document.body) document.body.classList.remove('force-desktop-mode');
                localStorage.setItem('eslab_view_mode', 'mobile');
                if (meta) meta.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover');
                updateViewModeButton(false);
            } else {
                document.documentElement.classList.add('force-desktop-mode');
                if (document.body) document.body.classList.add('force-desktop-mode');
                localStorage.setItem('eslab_view_mode', 'desktop');
                if (meta) meta.setAttribute('content', 'width=1280, initial-scale=0.35, maximum-scale=3.0, user-scalable=yes');
                updateViewModeButton(true);
            }
        }

        function updateViewModeButton(isDesktop) {
            var btns = document.querySelectorAll('.view-mode-toggle-btn');
            btns.forEach(function(btn) {
                var icon = btn.querySelector('.view-mode-icon');
                var text = btn.querySelector('.view-mode-text');
                if (isDesktop) {
                    if (icon) icon.className = 'fa-solid fa-mobile-screen-button view-mode-icon';
                    if (text) text.innerHTML = '<strong>📱 Mode Mobile (Kembali)</strong>';
                } else {
                    if (icon) icon.className = 'fa-solid fa-desktop view-mode-icon';
                    if (text) text.innerHTML = '<strong>🖥️ Mode Desktop</strong>';
                }
            });
        }
    </script>
    <style>
        /* Clean Desktop Mode Override for Mobile Viewport Toggle */
        @media (max-width: 991px) {
            html.force-desktop-mode,
            body.force-desktop-mode {
                min-width: 1280px !important;
                width: 1280px !important;
                max-width: none !important;
                overflow-x: auto !important;
            }
            html.force-desktop-mode .sidebar:not(.collapsed),
            body.force-desktop-mode .sidebar:not(.collapsed) {
                left: 0 !important;
                transform: none !important;
                position: fixed !important;
                display: flex !important;
                z-index: 9999 !important;
                width: 260px !important;
            }
            html.force-desktop-mode .sidebar.collapsed,
            body.force-desktop-mode .sidebar.collapsed {
                left: 0 !important;
                transform: none !important;
                position: fixed !important;
                display: flex !important;
                z-index: 9999 !important;
                width: 80px !important;
            }
            html.force-desktop-mode .mobile-backdrop,
            body.force-desktop-mode .mobile-backdrop,
            html.force-desktop-mode .mobile-bottom-dock,
            body.force-desktop-mode .mobile-bottom-dock,
            html.force-desktop-mode .mobile-top-bar,
            body.force-desktop-mode .mobile-top-bar {
                display: none !important;
            }
            html.force-desktop-mode .main-wrapper,
            body.force-desktop-mode .main-wrapper,
            html.force-desktop-mode .content,
            body.force-desktop-mode .content {
                margin-left: 260px !important;
                width: calc(1280px - 260px) !important;
                max-width: none !important;
                padding-top: 20px !important;
            }
            html.force-desktop-mode .main-wrapper.sidebar-collapsed,
            body.force-desktop-mode .main-wrapper.sidebar-collapsed,
            html.force-desktop-mode .content.sidebar-collapsed,
            body.force-desktop-mode .content.sidebar-collapsed {
                margin-left: 80px !important;
                width: calc(1280px - 80px) !important;
            }
        }
        /* --- CORE LIGHT THEME --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; /* Slate 50 */
            color: #1e293b; /* Slate 800 */
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 280px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: 30px 20px;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding: 0 10px;
        }

        .brand img { width: 200px; height: 200%; object-fit: contain; } 

        .nav-menu { list-style: none; flex-grow: 1; overflow-y: auto; }
        .nav-label { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 25px 0 10px 10px; }
        
        .nav-item {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 4px;
            cursor: pointer;
            transition: all 0.2s;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-item:hover, .nav-item.active {
            background: #eff6ff;
            color: #2563eb;
        }

        /* --- MAIN CONTENT --- */
        .main {
            flex-grow: 1;
            overflow-y: auto;
            padding: 40px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .welcome-text h2 { font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .welcome-text p { color: #64748b; font-size: 0.9rem; }

        /* --- STATS GRID --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .stat-card:hover { 
            transform: translateY(-4px); 
            border-color: #cbd5e1; 
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08);
        }
        .stat-card h4 { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; }
        .stat-card .number { font-size: 2rem; font-weight: 800; color: #0f172a; }

        /* --- DATA TABLE --- */
        .data-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        }

        .table-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 16px; color: #64748b; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid #f1f5f9; }
        td { padding: 16px; font-size: 0.85rem; border-bottom: 1px solid #f1f5f9; color: #334155; }
        tbody tr { transition: background-color 0.15s ease-in-out; }
        tbody tr:hover { background-color: #f8fafc; }

        /* Status Tags */
        .tag {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .tag-blue { background: #dbeafe; color: #1e40af; }
        .tag-orange { background: #ffedd5; color: #9a3412; }
        .tag-green { background: #dcfce7; color: #166534; }

        .logout-btn {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.2s;
            width: 100%;
        }
        .logout-btn:hover { background: #fee2e2; }

        /* Weather & Analytics */
        .dashboard-top-row {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 24px;
            margin-bottom: 24px;
        }

        .weather-card {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            padding: 20px;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
        }

        .weather-info h3 { font-size: 1.8rem; font-weight: 800; margin: 5px 0; }
        .weather-info p { font-size: 0.8rem; opacity: 0.9; font-weight: 600; }

        .chart-placeholder {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 20px;
            height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .page-btn {
            padding: 8px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            font-weight: 700;
            cursor: pointer;
            color: #64748b;
            font-size: 0.8rem;
            transition: all 0.2s;
        }
        .page-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        .page-btn.active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* === DARK MODE GLOBAL === */
        body.dark-mode { background-color: #0f172a !important; color: #e2e8f0 !important; }
        body.dark-mode .sidebar { background: #1e293b !important; border-right-color: #334155 !important; box-shadow: 4px 0 24px rgba(0,0,0,0.3) !important; }
        body.dark-mode .nav-item { color: #94a3b8 !important; }
        body.dark-mode .nav-item:hover, body.dark-mode .nav-item.active { background: #1d4ed820 !important; color: #60a5fa !important; }
        body.dark-mode .nav-label { color: #475569 !important; }
        body.dark-mode .top-bar, body.dark-mode .main > .top-bar { background: #0f172a !important; border-bottom-color: #1e293b !important; }
        body.dark-mode .coc-card, body.dark-mode .data-container, body.dark-mode .table-section, body.dark-mode .stat-card, body.dark-mode .menu-card, body.dark-mode .chart-placeholder { background: #1e293b !important; border-color: #334155 !important; color: #e2e8f0 !important; }
        body.dark-mode .weather-card { background: linear-gradient(135deg, #1e3a8a, #1d4ed8) !important; }
        body.dark-mode input, body.dark-mode select, body.dark-mode textarea { background: #0f172a !important; color: #e2e8f0 !important; border-color: #334155 !important; }
        body.dark-mode table th { color: #64748b !important; border-bottom-color: #1e293b !important; }
        body.dark-mode table td { border-bottom-color: #1e293b !important; color: #cbd5e1 !important; }
        body.dark-mode tbody tr:hover td, body.dark-mode tbody tr:hover { background-color: #1e293b !important; }
        body.dark-mode .page-btn { background: #1e293b !important; border-color: #334155 !important; color: #94a3b8 !important; }
        body.dark-mode .page-btn.active { background: #2563eb !important; color: white !important; }
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3, body.dark-mode h4 { color: #f1f5f9 !important; }
        body.dark-mode p { color: #94a3b8 !important; }

        #darkModeToggleBtn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 16px;
            background: none;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.2s;
            margin-top: 8px;
        }
        #darkModeToggleBtn:hover { background: #f1f5f9; color: #1e293b; }
        body.dark-mode #darkModeToggleBtn { border-color: #334155 !important; color: #94a3b8 !important; background: none !important; }
    </style>
    @stack('styles')
</head>
<body>

    @php
        $authUser = auth()->user();
        $roleCode = $authUser->role_code ?? $authUser->role ?? $authUser?->getRole() ?? 'admin_master';
        $routeName = request()->route()->getName();
        $canKalibrasi = $authUser->canAccessKalibrasi();
        $canHris = $authUser->canAccessHris();
    @endphp

    <aside class="sidebar">
        <div class="brand" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; padding: 0 4px;">
            <img src="{{ asset('images/eslab-logo.gif') }}" alt="Logo" style="max-height: 42px; width: auto; object-fit: contain;">
            <button id="sidebarPinBtn" onclick="toggleSidebar(event)" class="sidebar-pin-btn" title="Ciutkan / Buka Sidebar (Ctrl+B)" style="border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; border-radius: 8px; width: 32px; height: 32px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        
        <nav class="nav-menu">
            @if($canKalibrasi || $canHris || $roleCode === 'admin_master')
            <p class="nav-label">Portal Terpadu LIMS</p>

            @if($canKalibrasi)
            <a href="/kalibrasi/dashboard" class="nav-item" style="background: rgba(2, 132, 199, 0.08); border: 1px solid rgba(2, 132, 199, 0.2); color: #0284c7; font-weight: 700; margin-bottom: 6px;">
                <span style="width: 25px; display: inline-block;">⚖️</span> Switch to LIMS Kalibrasi
            </a>
            @endif

            @if($canHris)
            <a href="/pengujian/sso/hris" class="nav-item" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.2); color: #d97706; font-weight: 700; margin-bottom: 6px;">
                <span style="width: 25px; display: inline-block;">👥</span> Portal HRIS Enterprise
            </a>
            @endif

            @if($roleCode === 'admin_master')
            <a href="/pengujian/admin-master/dashboard" class="nav-item" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #dc2626; font-weight: 700; margin-bottom: 6px;">
                <span style="width: 25px; display: inline-block;">👑</span> Master Admin Hub
            </a>
            @endif
            @endif

            <p class="nav-label">Utama</p>
            @if($authUser->canAccessMenu('pengujian_dashboard'))
            <a href="/pengujian/dashboard" class="nav-item {{ Str::startsWith($routeName, 'dashboard') || Str::startsWith($routeName, 'pengujian.dashboard') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">🧪</span> Dashboard Pengujian
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_master_data'))
            <a href="/pengujian/master-data" class="nav-item {{ Str::startsWith($routeName, 'master-data') || Str::startsWith($routeName, 'pengujian.master-data') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">🗂️</span> Master Data
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_kelola_klien'))
            <a href="/pengujian/kelola-klien" class="nav-item {{ Str::startsWith($routeName, 'kelola-klien') || Str::startsWith($routeName, 'pengujian.kelola-klien') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">👥</span> Kelola Klien Customer
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_kelola_users'))
            <a href="/pengujian/kelola-users" class="nav-item {{ Str::startsWith($routeName, 'kelola-users') || Str::startsWith($routeName, 'pengujian.kelola-users') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">🛡️</span> Kelola Users Staff
            </a>
            @endif

            <p class="nav-label">Komersial & Dokumen</p>
            <a href="/pengujian/permintaan" class="nav-item {{ Str::startsWith($routeName, 'permintaan') || Str::startsWith($routeName, 'pengujian.permintaan') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">📑</span> Quotation & Penawaran
            </a>
            <a href="/pengujian/jadwal" class="nav-item {{ Str::startsWith($routeName, 'jadwal') || Str::startsWith($routeName, 'pengujian.jadwal') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">📅</span> Jadwal & Surat Tugas
            </a>
            <a href="/pengujian/invoice" class="nav-item {{ Str::startsWith($routeName, 'invoice') || Str::startsWith($routeName, 'pengujian.invoice') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">💳</span> Invoicing & Kwitansi
            </a>
            <a href="/pengujian/pengiriman" class="nav-item {{ Str::startsWith($routeName, 'pengiriman') || Str::startsWith($routeName, 'pengujian.pengiriman') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">📦</span> Pengiriman & Resi
            </a>
            <a href="/pengujian/finance" class="nav-item {{ Str::startsWith($routeName, 'finance') || Str::startsWith($routeName, 'pengujian.finance') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">💰</span> Penagihan Finance
            </a>

            <p class="nav-label">Menu Kerja Lab</p>

            @if($authUser->canAccessMenu('pengujian_peralatan'))
            <a href="/pengujian/peralatan" class="nav-item {{ Str::startsWith($routeName, 'peralatan') || Str::startsWith($routeName, 'pengujian.peralatan') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">🔧</span> Kelola Peralatan
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_coc'))
            <a href="/pengujian/coc" class="nav-item {{ Str::startsWith($routeName, 'coc') || Str::startsWith($routeName, 'pengujian.coc') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">📑</span> COC Digital
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_komunikasi'))
            <a href="/pengujian/komunikasi" class="nav-item {{ Str::startsWith($routeName, 'komunikasi') || Str::startsWith($routeName, 'pengujian.komunikasi') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">💬</span> Hub Komunikasi
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_sampling'))
            <a href="/pengujian/sampling" class="nav-item {{ Str::startsWith($routeName, 'sampling') || Str::startsWith($routeName, 'pengujian.sampling') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">📍</span> Monitoring Sampling
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_penerimaan'))
            <a href="/pengujian/penerimaan" class="nav-item {{ Str::startsWith($routeName, 'penerimaan') || Str::startsWith($routeName, 'pengujian.penerimaan') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">📥</span> Penerimaan Sampel
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_analisa'))
            <a href="/pengujian/analisa" class="nav-item {{ Str::startsWith($routeName, 'analisa') || Str::startsWith($routeName, 'pengujian.analisa') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">🧪</span> Log Analisa
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_coa'))
            <a href="/pengujian/coa" class="nav-item {{ Str::startsWith($routeName, 'coa') || Str::startsWith($routeName, 'pengujian.coa') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">📜</span> Verifikasi & COA
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_tren'))
            <a href="/pengujian/tren" class="nav-item {{ Str::startsWith($routeName, 'tren') || Str::startsWith($routeName, 'pengujian.tren') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">📈</span> Tren Analisa
            </a>
            @endif

            @if($authUser->canAccessMenu('pengujian_logger'))
            <p class="nav-label">Developer</p>
            <a href="/pengujian/logger" class="nav-item {{ Str::startsWith($routeName, 'logger') || Str::startsWith($routeName, 'pengujian.logger') ? 'active' : '' }}">
                <span style="width: 25px; display: inline-block;">🛡️</span> Activity Logger
            </a>
            @endif

            <button id="darkModeToggleBtn" onclick="toggleDarkMode()">
                🌙 Mode Gelap
            </button>

            <button onclick="toggleDesktopMobileMode(event)" class="view-mode-toggle-btn" style="width: 100%; margin-top: 10px; padding: 10px; background: rgba(168, 85, 247, 0.2); border: 1.5px solid rgba(168, 85, 247, 0.5); color: #7e22ce; border-radius: 8px; font-size: 0.85rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span class="view-mode-text">🖥️ Mode Desktop</span>
            </button>

        </nav>

        <form action="{{ route('pengujian.logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                🚪 Logout
            </button>
        </form>
    </aside>

    <main class="main">
        <div class="top-bar">
            <div class="welcome-text" style="display: flex; align-items: center; gap: 14px;">
                <button id="sidebarToggleBtn" onclick="toggleSidebar(event)" class="sidebar-toggle-btn" title="Toggle Sidebar (Ctrl+B)" style="background: #ffffff; border: 1px solid #e2e8f0; color: #475569; width: 38px; height: 38px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h2>Selamat Datang, {{ auth()->user()->profile?->full_name ?? auth()->user()->name }} 👋</h2>
                    <p>Pantau progres pengujian laboratorium hari ini.</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
                <div id="currentDate" style="font-size: 0.8rem; font-weight: 700; color: #475569; background: #fff; border: 1px solid #e2e8f0; padding: 10px 20px; border-radius: 12px;">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>

                <div style="display: flex; align-items: center; gap: 10px; border-left: 1px solid #e2e8f0; padding-left: 15px;">
                    <div style="text-align: right;">
                        <p style="font-size: 0.8rem; font-weight: 800;">{{ auth()->user()->profile?->full_name ?? auth()->user()->name }}</p>
                        <p style="font-size: 0.65rem; color: #64748b; text-transform: uppercase;">{{ auth()->user()->getRole() }}</p>
                    </div>
                    <button onclick="toggleDesktopMobileMode()" class="view-mode-toggle-btn btn btn-sm me-2" style="background: #a855f7; color: #ffffff; font-size: 0.75rem; font-weight: 800; border-radius: 8px; border: none; padding: 6px 12px; cursor: pointer;" title="Ubah Mode Tampilan Desktop/Mobile">
                        <i class="fa-solid fa-desktop view-mode-icon me-1"></i> <span class="view-mode-text">Mode Desktop</span>
                    </button>
                    <form action="{{ route('pengujian.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1.2rem;" title="Logout">🚪</button>
                    </form>
                </div>
            </div>
        </div>

        @yield('content')
    </main>

    <script>
        function applyDarkMode() {
            const isDark = localStorage.getItem('eslab_dark_mode') === 'true';
            if (isDark) { document.body.classList.add('dark-mode'); }
            else { document.body.classList.remove('dark-mode'); }
            const btn = document.getElementById('darkModeToggleBtn');
            if (btn) btn.innerHTML = isDark ? '☀️ Mode Terang' : '🌙 Mode Gelap';
        }

        window.toggleDarkMode = function() {
            const isDark = document.body.classList.toggle('dark-mode');
            localStorage.setItem('eslab_dark_mode', isDark);
            const btn = document.getElementById('darkModeToggleBtn');
            if (btn) { btn.innerHTML = isDark ? '☀️ Mode Terang' : '🌙 Mode Gelap'; }
        };

        applyDarkMode();

        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
    </script>
    <script src="{{ asset('mobile-nav.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('pwa-install.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('sidebar-collapsible.js') }}"></script>

    @stack('scripts')
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('{{ asset("sw.js") }}')
                .then(function(reg) {
                    reg.update();
                })
                .catch(function() {});
        });
    }
    </script>
</body>
</html>

