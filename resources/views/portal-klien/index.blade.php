<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Portal Customer Kalibrasi - PT Envirotama Solusindo (LK-361-IDN)</title>
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    
    <style>
        :root {
            --primary: #0284c7;
            --primary-hover: #0369a1;
            --primary-light: #e0f2fe;
            --secondary: #475569;
            --success: #16a34a;
            --success-light: #dcfce7;
            --warning: #d97706;
            --warning-light: #fef3c7;
            --danger: #dc2626;
            --danger-light: #fee2e2;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f1f5f9; 
            color: var(--slate-800); 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- SIDEBAR & LAYOUT SYSTEM --- */
        .app-layout {
            display: flex;
            min-height: 100vh;
            background: #f1f5f9;
        }

        .sidebar {
            width: 275px;
            background: #0f172a;
            color: #ffffff;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #1e293b;
            position: sticky;
            top: 0;
            height: 100vh;
            box-sizing: border-box;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #1e293b;
            background: #090d1a;
        }

        .sidebar-menu {
            padding: 16px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
            overflow-y: auto;
        }

        .menu-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 0.5px;
            padding: 12px 10px 4px;
            text-transform: uppercase;
        }

        .sidebar .tab-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 11px 14px;
            border: 1px solid transparent;
            background: transparent;
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            box-sizing: border-box;
        }

        .sidebar .tab-btn:hover {
            background: #1e293b;
            color: #ffffff;
        }

        .sidebar .tab-btn.active {
            background: #0284c7;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid #1e293b;
            background: #0b1329;
        }

        .main-content {
            flex: 1;
            padding: 24px 32px;
            overflow-y: auto;
            min-width: 0;
            box-sizing: border-box;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            background: #ffffff;
            padding: 16px 24px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .brand-badge {
            background: rgba(2, 132, 199, 0.2);
            border: 1px solid rgba(56, 189, 248, 0.4);
            color: #38bdf8;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .company-profile-pill {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 8px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.82rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .btn-logout {
            background: rgba(220, 38, 38, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 9px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 700;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: #dc2626;
            color: white;
        }

        @media (max-width: 992px) {
            .app-layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                padding: 16px;
            }
        }


        .tab-btn.active {
            background: var(--primary);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px -2px rgba(2, 132, 199, 0.3);
        }


        .tab-badge {
            background: #e2e8f0;
            color: #334155;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .tab-btn.active .tab-badge {
            background: var(--primary);
            color: white;
        }

        /* --- STAT CARDS GRID --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 2px 4px -2px rgba(0,0,0,0.03);
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-info h4 {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--slate-600);
            margin-bottom: 4px;
        }

        .stat-info .value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--slate-900);
        }

        /* --- CONTENT CARDS & TABLES --- */
        .card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
            padding: 24px;
            margin-bottom: 28px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--slate-900);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action {
            background: var(--primary);
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-action:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: var(--slate-700);
        }
        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table.custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.85rem;
        }

        table.custom-table th {
            background: #f8fafc;
            color: var(--slate-600);
            padding: 14px 16px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }

        table.custom-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--slate-700);
            vertical-align: middle;
        }

        table.custom-table tbody tr:hover {
            background: #f8fafc;
        }

        /* --- BADGES & STATUS INDICATORS --- */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .badge-success { background: var(--success-light); color: var(--success); }
        .badge-warning { background: var(--warning-light); color: var(--warning); }
        .badge-danger { background: var(--danger-light); color: var(--danger); }
        .badge-info { background: var(--primary-light); color: var(--primary); }
        .badge-neutral { background: #f1f5f9; color: var(--slate-600); }

        /* --- PIPELINE PROGRESS BAR --- */
        .pipeline-bar {
            display: flex;
            gap: 4px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 8px;
            margin-top: 6px;
        }
        .pipeline-step {
            flex: 1;
            height: 6px;
            border-radius: 3px;
            background: #cbd5e1;
        }
        .pipeline-step.active {
            background: var(--primary);
        }
        .pipeline-step.done {
            background: var(--success);
        }

        /* --- MODAL --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: all 0.25s;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 600px;
            padding: 28px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            transform: translateY(20px);
            transition: transform 0.25s;
        }

        .modal-overlay.active .modal-card {
            transform: translateY(0);
        }

        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--slate-700);
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-family: inherit;
            font-size: 0.88rem;
            transition: border 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        /* --- CHAT MESSAGE FEED --- */
        .chat-box {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 8px;
        }
        .chat-msg {
            padding: 14px 18px;
            border-radius: 14px;
            max-width: 80%;
            font-size: 0.88rem;
            line-height: 1.5;
        }
        .chat-msg.klien {
            align-self: flex-end;
            background: var(--primary-light);
            color: var(--primary-hover);
            border-bottom-right-radius: 2px;
        }
        .chat-msg.staff {
            align-self: flex-start;
            background: #f1f5f9;
            color: var(--slate-800);
            border-bottom-left-radius: 2px;
        }
    </style>
    <style>
        .btn-back-home-client {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #0f4c81;
            padding: 7px 16px;
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.25s ease;
        }
        .btn-back-home-client:hover {
            border-color: #00bba7;
            color: #00bba7;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 187, 167, 0.2);
        }
    </style>
</head>
<body>

<body>

    <div class="app-layout">

        <!-- LEFT SIDEBAR NAVIGATION -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-compass-drafting fa-lg" style="color: #38bdf8;"></i>
                <div>
                    <h3 style="font-weight: 800; font-size: 0.95rem; letter-spacing: -0.2px; color: white; margin: 0;">PORTAL CUSTOMER</h3>
                    <p style="font-size: 0.68rem; color: #94a3b8; margin: 2px 0 0 0;">PT Envirotama Solusindo</p>
                </div>
            </div>

            <!-- Selector 2 Kategori Portal Customer -->
            <div style="padding: 12px 14px 0 14px;">
                <div style="font-size: 0.65rem; font-weight: 800; color: #7dd3fc; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Kategori Portal Customer:</div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <button type="button" id="btnModeKalibrasi" onclick="switchCustomerPortalMode('kalibrasi')" style="padding: 8px 10px; font-size: 0.75rem; text-align: left; background: #0284c7; color: white; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                        <span>⚖️ Customer Kalibrasi</span>
                        <span style="font-size: 0.6rem; background: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 4px;">LK-361</span>
                    </button>
                    <button type="button" id="btnModePengujian" onclick="switchCustomerPortalMode('pengujian')" style="padding: 8px 10px; font-size: 0.75rem; text-align: left; background: #1e293b; color: #94a3b8; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                        <span>🧪 Customer Pengujian Lingkungan</span>
                        <span style="font-size: 0.6rem; background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;">Air/Udara</span>
                    </button>
                </div>
            </div>

            <div style="padding: 12px 16px 0 16px;">
                <span id="portalBrandBadge" class="brand-badge" style="display: block; text-align: center;">KAN LK-361-IDN</span>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-label">MODUL KALIBRASI ALAT</div>
                <button class="tab-btn active" onclick="switchTab('tab-requests')">
                    <span><i class="fa-solid fa-file-signature"></i> Permintaan & Quotation</span>
                    <span class="tab-badge" style="background: #e0f2fe; color: #0284c7;">{{ $requestOrders->count() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('tab-orders-po')">
                    <span><i class="fa-solid fa-boxes-stacked"></i> Order Berjalan (Sudah PO)</span>
                    <span class="tab-badge" style="background: #dcfce7; color: #15803d;">{{ $poOrders->count() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('tab-lingkup')">
                    <span><i class="fa-solid fa-award"></i> Lingkup KAN & CMC</span>
                    <span class="tab-badge" style="background: #fef3c7; color: #d97706;">KAN</span>
                </button>
                <button class="tab-btn" onclick="switchTab('tab-recalibration')">
                    <span><i class="fa-solid fa-triangle-exclamation"></i> Alert Kalibrasi Ulang</span>
                    <span class="tab-badge" style="background: #fee2e2; color: #dc2626;">{{ $stats['recalibration_due'] }}</span>
                </button>


                <div class="menu-label" style="margin-top: 16px;">FINANCE & SUPPORT</div>
                <button class="tab-btn" onclick="switchTab('tab-invoices')">
                    <span><i class="fa-solid fa-receipt"></i> Tagihan & Invoice</span>
                    <span class="tab-badge">{{ $invoices->count() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('tab-messages')">
                    <span><i class="fa-solid fa-comments"></i> Pesan & Ticketing</span>
                    <span class="tab-badge">{{ $messages->count() }}</span>
                </button>

                <div class="menu-label" style="margin-top: 16px;">LAB LINGKUNGAN</div>
                <button class="tab-btn" onclick="switchTab('tab-lingkungan-dev')">
                    <span><i class="fa-solid fa-seedling"></i> Pengujian Lingkungan</span>
                    <span class="tab-badge" style="background: #e2e8f0; color: #64748b;">Tahap Dev</span>
                </button>
            </nav>

            <!-- SIDEBAR FOOTER (PROFILE & LOGOUT) -->
            <div class="sidebar-footer">
                <div class="company-profile-pill" style="margin-bottom: 12px;">
                    <div class="user-avatar">
                        {{ strtoupper(substr(session('client_company', 'C'), 0, 1)) }}
                    </div>
                    <div style="overflow: hidden;">
                        <div style="font-weight: 700; font-size: 0.82rem; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ session('client_company') }}</div>
                        <div style="font-size: 0.68rem; color: #94a3b8;">PIC: {{ $customerInfo->kontak_person ?? 'Customer' }}</div>
                    </div>
                </div>

                <form action="{{ route('klien.logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-logout" style="width: 100%; justify-content: center;">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar Portal
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN RIGHT CONTENT AREA -->
        <main class="main-content">
            <!-- HEADER INFO TOP BAR -->
            <div class="content-header">
                <div>
                    <h3 style="font-weight: 800; font-size: 1.15rem; color: var(--slate-900); margin: 0;">Portal Customer Kalibrasi LK-361-IDN</h3>
                    <p style="font-size: 0.78rem; color: var(--slate-600); margin: 4px 0 0 0;">Layanan Kalibrasi Terakreditasi KAN - PT Envirotama Solusindo</p>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn-action" onclick="openModal('modal-request-kalibrasi')">
                        <i class="fa-solid fa-plus"></i> Ajukan Kalibrasi Baru
                    </button>
                </div>
            </div>


        <!-- STATS GRID -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Order Kalibrasi</h4>
                    <div class="value">{{ $stats['total_order'] }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div class="stat-info">
                    <h4>Sertifikat Terbit (COA)</h4>
                    <div class="value">{{ $stats['sertifikat_terbit'] }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="stat-info">
                    <h4>Peringatan Kalibrasi Ulang</h4>
                    <div class="value" style="color: {{ $stats['recalibration_due'] > 0 ? '#dc2626' : 'inherit' }}">
                        {{ $stats['recalibration_due'] }}
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-info">
                    <h4>Invoice Unpaid</h4>
                    <div class="value">{{ $stats['unpaid_invoices'] }}</div>
                </div>
            </div>
        </div>

        <!-- BANNER HIGHLIGHT LINGKUP AKREDITASI KAN LK-361-IDN -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #0284c7 100%); border-radius: 16px; padding: 20px 24px; color: white; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; box-shadow: 0 10px 20px -5px rgba(2, 132, 199, 0.3); flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 52px; height: 52px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: #fbbf24;">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <h4 style="font-size: 1.05rem; font-weight: 800; letter-spacing: -0.2px;">Laboratorium Kalibrasi Terakreditasi KAN (LK-361-IDN)</h4>
                        <span style="background: #f59e0b; color: #0f172a; font-size: 0.68rem; font-weight: 800; padding: 3px 8px; border-radius: 6px;">SNI ISO/IEC 17025:2017</span>
                    </div>
                    <p style="font-size: 0.8rem; color: #e0f2fe; margin-top: 4px;">Lingkup Resmi: Suhu & Kelembapan, Massa, Volume, Tekanan, Aliran, Instrumen Analitik, Fotometri, Akustik, Kelistrikan, Waktu & Frekuensi.</p>
                </div>
            </div>
            <button class="btn-action" style="background: #ffffff; color: #0f172a; font-weight: 800; padding: 10px 18px;" onclick="switchTab('tab-lingkup')">
                <i class="fa-solid fa-list-check" style="color: #0284c7;"></i> Lihat Tabel Kemampuan Ukur (CMC)
            </button>
        </div>


        <!-- TAB 1: PERMINTAAN & QUOTATION PENAWARAN -->
        <div id="tab-requests" class="tab-content card">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <i class="fa-solid fa-file-signature" style="color: var(--primary);"></i> Permintaan & Quotation Penawaran
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Daftar permohonan kalibrasi online & penawaran quotation harga yang diajukan ke laboratorium.</p>
                </div>
                <button class="btn-action" onclick="openModal('modal-request-kalibrasi')">
                    <i class="fa-solid fa-plus"></i> Ajukan Kalibrasi Baru
                </button>
            </div>

            <!-- SEARCH BAR PERMINTAAN -->
            <div style="margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap;">
                <div style="flex: 2; min-width: 260px;">
                    <input type="text" id="reqSearchInput" class="form-control" placeholder="🔍 Cari No. Permintaan, Quotation, atau Tipe Pekerjaan..." onkeyup="applyReqFilterAndSort()" style="font-weight: 600;">
                </div>
                <div style="flex: 1; min-width: 160px;">
                    <select id="reqSortSelect" class="form-control" onchange="applyReqFilterAndSort()" style="font-weight: 600;">
                        <option value="default">Urutan: Default</option>
                        <option value="order_asc">No. Order (A-Z)</option>
                        <option value="order_desc">No. Order (Z-A)</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No. Permintaan / Request</th>
                            <th>No. Quotation Penawaran</th>
                            <th>Tipe Pekerjaan</th>
                            <th>Tanggal Masuk</th>
                            <th>Status Penawaran</th>
                            <th>Rincian Alat</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="reqTableBody">
                        @forelse($requestOrders as $order)
                        <tr data-noorder="{{ $order->no_order }}" data-status="{{ $order->status }}">
                            <td style="font-weight: 700;">
                                <button type="button" class="btn-action btn-secondary" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none;" onclick='showOrderDetail(@json($order))'>
                                    <i class="fa-solid fa-folder-open" style="color: var(--primary);"></i> {{ $order->no_order }}
                                </button>
                            </td>
                            <td>
                                @php
                                    $quoNum = $order->no_quotation ?? $order->no_qt ?? null;
                                @endphp
                                @if(!empty($quoNum))
                                    <span class="badge" style="font-family: monospace; font-size: 0.82rem; background: #dcfce7; color: #15803d; border: 1px solid #86efac; padding: 5px 10px; font-weight: 800;">
                                        <i class="fa-solid fa-file-circle-check"></i> {{ $quoNum }}
                                    </span>
                                @else
                                    <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic; background: #f8fafc; padding: 4px 8px; border-radius: 6px; border: 1px dashed #cbd5e1; display: inline-block;">
                                        <i class="fa-solid fa-clock"></i> Memproses Quotation...
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $order->tipe_pekerjaan === 'on_site' ? 'badge-warning' : 'badge-info' }}">
                                    {{ strtoupper(str_replace('_', ' ', $order->tipe_pekerjaan)) }}
                                </span>
                            </td>
                            <td>{{ $order->tanggal_masuk ? \Carbon\Carbon::parse($order->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                            <td>
                                @php
                                    $stVerif = $order->status_verifikasi ?? ($order->status === 'Revisi Quotation' ? 'Perlu Revisi' : (!empty($quoNum) ? 'Disetujui' : 'Menunggu Verifikasi'));
                                @endphp

                                @if($stVerif === 'Perlu Revisi')
                                    <span class="badge" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; font-weight: 800; font-size: 0.78rem;">
                                        <i class="fa-solid fa-triangle-exclamation" style="color: #ea580c;"></i> Perlu Revisi
                                    </span>
                                    @if(!empty($order->alasan_verifikasi))
                                        <div style="font-size: 0.73rem; color: #c2410c; font-weight: 700; margin-top: 4px; background: #fff7ed; padding: 4px 8px; border-radius: 6px; border-left: 3px solid #f97316;">
                                            💬 Alasan: {{ $order->alasan_verifikasi }}
                                        </div>
                                    @endif
                                @elseif($stVerif === 'Ditolak')
                                    <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-weight: 800; font-size: 0.78rem;">
                                        <i class="fa-solid fa-circle-xmark" style="color: #dc2626;"></i> Ditolak Marketing
                                    </span>
                                    @if(!empty($order->alasan_verifikasi))
                                        <div style="font-size: 0.73rem; color: #dc2626; font-weight: 700; margin-top: 4px; background: #fef2f2; padding: 4px 8px; border-radius: 6px; border-left: 3px solid #ef4444;">
                                            💬 Alasan: {{ $order->alasan_verifikasi }}
                                        </div>
                                    @endif
                                @elseif($stVerif === 'Disetujui' || !empty($quoNum))
                                    <span class="badge" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-weight: 800;">
                                        <i class="fa-solid fa-check-circle" style="color: #0284c7;"></i> Disetujui (Quotation Terbit)
                                    </span>
                                @else
                                    <span class="badge" style="background: #fef3c7; color: #d97706; border: 1px solid #fde68a; font-weight: 800;">
                                        <i class="fa-solid fa-hourglass-half" style="color: #d97706;"></i> Menunggu Verifikasi Marketing
                                    </span>
                                @endif
                            </td>

                            <td style="font-weight: 700;">
                                <button type="button" class="btn-action" style="padding: 6px 12px; font-size: 0.78rem; background: #e0f2fe; color: #0284c7;" onclick='showOrderDetail(@json($order))'>
                                    <i class="fa-solid fa-boxes-stacked"></i> {{ $order->alats->count() }} Alat (Rincian)
                                </button>
                            </td>
                            <td style="text-align: center; white-space: nowrap;">
                                <button type="button" class="btn-action" style="padding: 5px 10px; font-size: 0.75rem; background: #f59e0b; color: white;" onclick='editKalibrasiRequestModal(@json($order))' title="Edit / Revisi Rincian Alat">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit / Revisi
                                </button>
                                <button type="button" class="btn-action" style="padding: 5px 10px; font-size: 0.75rem; background: #ef4444; color: white; margin-left: 4px;" onclick="deleteKalibrasiRequest('{{ $order->id }}', '{{ $order->no_order }}')" title="Batalkan / Hapus Permintaan">
                                    <i class="fa-solid fa-trash"></i> Batalkan / Hapus
                                </button>
                            </td>


                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--slate-600);">
                                <i class="fa-solid fa-inbox fa-2x" style="margin-bottom: 8px; color: #cbd5e1;"></i>
                                <p>Belum ada permintaan kalibrasi baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>


        <!-- TAB 2: ORDER BERJALAN & PENGERJAAN (SUDAH PO) -->
        <div id="tab-orders-po" class="tab-content card" style="display: none;">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <i class="fa-solid fa-boxes-stacked" style="color: #10b981;"></i> Order Berjalan & Pengerjaan (Sudah PO)
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Daftar order kalibrasi terkonfirmasi PO yang sedang diproses di laboratorium hingga penerbitan sertifikat (COA).</p>
                </div>
                <button class="btn-action" onclick="openModal('modal-request-kalibrasi')">
                    <i class="fa-solid fa-plus"></i> Ajukan Kalibrasi Baru
                </button>
            </div>

            <!-- SEARCH, FILTER & SORT BAR ORDER PO -->
            <div style="margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap;">
                <div style="flex: 2; min-width: 260px;">
                    <input type="text" id="poSearchInput" class="form-control" placeholder="🔍 Cari No. Order, Quotation, PO, atau Tipe Pekerjaan..." onkeyup="applyPoFilterAndSort()" style="font-weight: 600;">
                </div>
                <div style="flex: 1; min-width: 180px;">
                    <select id="poStatusFilter" class="form-control" onchange="applyPoFilterAndSort()" style="font-weight: 600;">
                        <option value="all">Semua Status Pengerjaan</option>
                        <option value="Penerimaan Alat">Penerimaan Alat</option>
                        <option value="Terjadwal">Terjadwal</option>
                        <option value="Proses Kalibrasi">Proses Kalibrasi</option>
                        <option value="Evaluasi Data">Evaluasi Data</option>
                        <option value="COA Terbit">COA Terbit</option>
                        <option value="Invoiced">Invoiced</option>
                        <option value="Delivered">Delivered</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 160px;">
                    <select id="poSortSelect" class="form-control" onchange="applyPoFilterAndSort()" style="font-weight: 600;">
                        <option value="default">Urutan: Default</option>
                        <option value="order_asc">No. Order (A-Z)</option>
                        <option value="order_desc">No. Order (Z-A)</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>No. Quotation / PO</th>
                            <th>Tipe Pekerjaan</th>
                            <th>Tanggal Masuk</th>
                            <th>Status Pengerjaan</th>
                            <th>Tahapan Progress Timeline</th>
                            <th>Jml Alat & COA</th>
                        </tr>
                    </thead>
                    <tbody id="poTableBody">
                        @forelse($poOrders as $order)
                        <tr data-noorder="{{ $order->no_order }}" data-status="{{ $order->status }}">
                            <td style="font-weight: 700;">
                                <button type="button" class="btn-action btn-secondary" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none;" onclick='showOrderDetail(@json($order))'>
                                    <i class="fa-solid fa-folder-open" style="color: var(--primary);"></i> {{ $order->no_order }}
                                </button>
                            </td>

                            <td>
                                <div><strong>Quotation:</strong> {{ $order->no_quotation ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #16a34a;"><strong>PO:</strong> {{ $order->no_po ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $order->tipe_pekerjaan === 'on_site' ? 'badge-warning' : 'badge-info' }}">
                                    {{ strtoupper(str_replace('_', ' ', $order->tipe_pekerjaan)) }}
                                </span>
                            </td>
                            <td>{{ $order->tanggal_masuk ? \Carbon\Carbon::parse($order->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $order->status }}</span>
                            </td>
                            <td style="min-width: 320px;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; margin-bottom: 6px; color: var(--slate-600);">
                                    <span style="color: var(--success);"><i class="fa-solid fa-check"></i> 1. Quotation</span>
                                    <span style="color: {{ in_array($order->status, ['Penerimaan Alat', 'Terjadwal', 'Proses Kalibrasi', 'Evaluasi Data', 'COA Terbit', 'Invoiced', 'Delivered']) ? 'var(--success)' : '#94a3b8' }};">2. Terima Alat</span>
                                    <span style="color: {{ in_array($order->status, ['Proses Kalibrasi', 'Evaluasi Data', 'COA Terbit', 'Invoiced', 'Delivered']) ? 'var(--success)' : '#94a3b8' }};">3. Pengukuran</span>
                                    <span style="color: {{ in_array($order->status, ['Evaluasi Data', 'COA Terbit', 'Invoiced', 'Delivered']) ? 'var(--success)' : '#94a3b8' }};">4. Evaluasi</span>
                                    <span style="color: {{ in_array($order->status, ['COA Terbit', 'Invoiced', 'Delivered']) ? 'var(--success)' : '#94a3b8' }};">5. COA Terbit</span>
                                </div>
                                <div class="pipeline-bar">
                                    <div class="pipeline-step done" title="Step 1: Quotation"></div>
                                    <div class="pipeline-step {{ in_array($order->status, ['Penerimaan Alat', 'Terjadwal', 'Proses Kalibrasi', 'Evaluasi Data', 'COA Terbit', 'Invoiced', 'Delivered']) ? 'done' : 'active' }}" title="Step 2: Penerimaan Alat"></div>
                                    <div class="pipeline-step {{ in_array($order->status, ['Proses Kalibrasi', 'Evaluasi Data', 'COA Terbit', 'Invoiced', 'Delivered']) ? 'done' : '' }}" title="Step 3: Pengukuran Lab"></div>
                                    <div class="pipeline-step {{ in_array($order->status, ['Evaluasi Data', 'COA Terbit', 'Invoiced', 'Delivered']) ? 'done' : '' }}" title="Step 4: Evaluasi Data"></div>
                                    <div class="pipeline-step {{ in_array($order->status, ['COA Terbit', 'Invoiced', 'Delivered']) ? 'done' : '' }}" title="Step 5: COA Terbit (Scan TTD Basah)"></div>
                                </div>
                            </td>
                            <td style="font-weight: 700;">
                                <button type="button" class="btn-action" style="padding: 6px 12px; font-size: 0.78rem; background: #e0f2fe; color: #0284c7;" onclick='showOrderDetail(@json($order))'>
                                    <i class="fa-solid fa-boxes-stacked"></i> {{ $order->alats->count() }} Alat (Rincian & COA)
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--slate-600);">
                                <i class="fa-solid fa-inbox fa-2x" style="margin-bottom: 8px; color: #cbd5e1;"></i>
                                <p>Belum ada order kalibrasi yang terkonfirmasi PO.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <!-- TAB 2: INVENTARIS ALAT & COA -->
        <div id="tab-assets" class="tab-content card" style="display: none;">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <i class="fa-solid fa-boxes-stacked" style="color: var(--primary);"></i> Inventaris Alat & Download Sertifikat (COA)
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Daftar seluruh peralatan milik perusahaan dan file Sertifikat Kalibrasi resmi.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Kode Alat</th>
                            <th>Nama Alat</th>
                            <th>Merk / Tipe / No Seri</th>
                            <th>Kategori KAN</th>
                            <th>Status Proses</th>
                            <th>No. Sertifikat</th>
                            <th>Aksi COA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kalibrasiAlats as $alt)
                        <tr>
                            <td style="font-weight: 700; color: var(--slate-900);">{{ $alt->kode_alat_item }}</td>
                            <td style="font-weight: 700; color: var(--primary);">{{ $alt->nama_alat }}</td>
                            <td>
                                <div>{{ $alt->merk ?? '-' }} / {{ $alt->tipe_model ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: var(--slate-600);">SN: {{ $alt->nomor_seri ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-neutral">{{ $alt->kategori_kan }}</span>
                            </td>
                            <td>
                                @if($alt->sertifikat)
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Selesai & Terbit</span>
                                @else
                                    <span class="badge badge-warning"><i class="fa-solid fa-spinner fa-spin"></i> {{ $alt->status_proses }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $alt->sertifikat ? $alt->sertifikat->no_sertifikat : '-' }}</strong>
                            </td>
                            <td>
                                @if($alt->sertifikat)
                                    <a href="{{ route('kalibrasi.coa.print', $alt->sertifikat->id) }}" target="_blank" class="btn-action" style="padding: 6px 12px; font-size: 0.78rem;">
                                        <i class="fa-solid fa-file-pdf"></i> Unduh COA
                                    </a>
                                @else
                                    <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic;">Sedang Diproses</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--slate-600);">
                                <p>Belum ada daftar alat yang dikalibrasi.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: ALERT KALIBRASI ULANG -->
        <div id="tab-recalibration" class="tab-content card" style="display: none;">
            <div class="card-header">
                <div>
                    <div class="card-title" style="color: var(--danger);">
                        <i class="fa-solid fa-triangle-exclamation"></i> Peringatan Jatuh Tempo Kalibrasi Ulang
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Peralatan berikut sudah mendekati/melewati batas 1 tahun sejak tanggal kalibrasi terakhir dan disarankan untuk dikalibrasi ulang.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Nama Alat</th>
                            <th>Merk / Seri</th>
                            <th>No. Sertifikat Terakhir</th>
                            <th>Tgl Kalibrasi</th>
                            <th>Masa Berlaku</th>
                            <th>Status Rekalibrasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recalibrationAlerts as $alt)
                        <tr>
                            <td style="font-weight: 700; color: var(--slate-900);">{{ $alt->nama_alat }}</td>
                            <td>{{ $alt->merk }} (SN: {{ $alt->nomor_seri ?? '-' }})</td>
                            <td>{{ $alt->sertifikat->no_sertifikat }}</td>
                            <td>{{ \Carbon\Carbon::parse($alt->sertifikat->tgl_sertifikat)->format('d/m/Y') }}</td>
                            <td>12 Bulan (Standard KAN)</td>
                            <td>
                                <span class="badge badge-danger">
                                    <i class="fa-solid fa-bell"></i> Wajib Kalibrasi Ulang
                                </span>
                            </td>
                            <td>
                                <button class="btn-action" onclick="prefillKalibrasiRequest('{{ $alt->nama_alat }} (SN: {{ $alt->nomor_seri }})')">
                                    <i class="fa-solid fa-rotate-right"></i> Order Re-Kalibrasi
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--slate-600);">
                                <i class="fa-solid fa-shield-halved fa-2x" style="color: var(--success); margin-bottom: 8px;"></i>
                                <p style="font-weight: 700; color: var(--success);">Semua peralatan Anda berada dalam masa berlaku kalibrasi aktif!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 4: INVOICE & TAGIHAN DIGITAL -->
        <div id="tab-invoices" class="tab-content card" style="display: none;">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <i class="fa-solid fa-file-invoice-dollar" style="color: var(--primary);"></i> Tagihan & Invoice Digital Kalibrasi
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Pantau rincian nilai tagihan, nominal terbayar, sisa pembayaran, dan dokumen keuangan resmi (Informasi & View-Only).</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No. Invoice / Quotation</th>
                            <th>No. Order</th>
                            <th>Tgl Cetak Invoice & TOP (Jatuh Tempo)</th>
                            <th>Nilai Tagihan (Rp)</th>
                            <th>Sisa Tagihan (Rp)</th>
                            <th>Status Pembayaran</th>
                            <th>Dokumen Keuangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                        @php
                            $tglCetak = $inv->tgl_invoice ? \Carbon\Carbon::parse($inv->tgl_invoice) : ($inv->tgl_terbit_sertifikat ? \Carbon\Carbon::parse($inv->tgl_terbit_sertifikat) : \Carbon\Carbon::parse($inv->created_at));
                            $topDueDate = $tglCetak->copy()->addMonth();
                        @endphp
                        <tr>
                            <td style="font-weight: 700; color: var(--primary);">{{ $inv->no_invoice ?? ($inv->no_quotation ?? $inv->no_order) }}</td>
                            <td>{{ $inv->no_order }}</td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.8rem;">
                                    <i class="fa-solid fa-calendar-day" style="color: #0284c7;"></i> {{ $tglCetak->format('d/m/Y') }}
                                </div>
                                <div style="margin-top: 3px;">
                                    <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-size: 0.72rem; font-weight: 800;">
                                        <i class="fa-solid fa-clock"></i> TOP 1 Bulan: {{ $topDueDate->format('d/m/Y') }}
                                    </span>
                                </div>
                            </td>
                            <td style="font-weight: 700;">Rp {{ number_format($inv->grand_total, 0, ',', '.') }}</td>
                            <td style="font-weight: 700; color: {{ $inv->sisa_tagihan > 0 ? '#dc2626' : '#16a34a' }};">
                                Rp {{ number_format($inv->sisa_tagihan, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($inv->computed_status_pembayaran === 'Sudah Lunas')
                                    <span class="badge badge-success"><i class="fa-solid fa-check-double"></i> Lunas</span>
                                @elseif($inv->computed_status_pembayaran === 'Bayar Sebagian')
                                    <span class="badge badge-warning">Bayar Sebagian</span>
                                @else
                                    <span class="badge badge-danger">Belum Lunas</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                    <a href="{{ route('kalibrasi.invoice.print', $inv->id) }}" target="_blank" class="btn-action btn-secondary" style="padding: 4px 8px; font-size: 0.72rem;">
                                        <i class="fa-solid fa-file-pdf"></i> Inv
                                    </a>
                                    <a href="{{ route('kalibrasi.invoice.print_kwitansi', $inv->id) }}" target="_blank" class="btn-action btn-secondary" style="padding: 4px 8px; font-size: 0.72rem;">
                                        <i class="fa-solid fa-receipt"></i> Kwitansi
                                    </a>
                                    <a href="{{ route('kalibrasi.invoice.print_bast', $inv->id) }}" target="_blank" class="btn-action btn-secondary" style="padding: 4px 8px; font-size: 0.72rem;">
                                        <i class="fa-solid fa-file-contract"></i> BAST
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--slate-600);">
                                <i class="fa-solid fa-file-invoice fa-3x" style="color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                                <p style="font-weight: 700; color: #475569; margin: 0;">Belum Ada Tagihan Invoice Diterbitkan</p>
                                <p style="font-size: 0.78rem; color: #94a3b8; margin-top: 4px;">Invoice tagihan akan secara otomatis diterbitkan & tampil di sini setelah Sertifikat (COA) resmi terbit.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- NOTICE HUBUNGI ADMIN FINANCE -->
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px; padding: 18px; margin-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; background: #dbeafe; color: #1d4ed8; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 0.9rem; color: #1e3a8a;">Informasi Konfirmasi & Proses Pembayaran</div>
                        <div style="font-size: 0.8rem; color: #1e40af; margin-top: 2px;">Tabel tagihan di atas bersifat <strong>Informasi & View-Only</strong>. Untuk konfirmasi pembayaran, pengiriman bukti transfer, atau kendala invoice, silakan hubungi Admin Finance LIMS Enviro.</div>
                    </div>
                </div>
                <a href="https://wa.me/628123456789?text=Halo%20Admin%20Finance%20LIMS%20Enviro,%20saya%20ingin%20mengonfirmasi%20pembayaran%20invoice..." target="_blank" class="btn-action" style="background: #25d366; padding: 10px 18px; text-decoration: none;">
                    <i class="fa-brands fa-whatsapp"></i> Hubungi Admin Finance
                </a>
            </div>
        </div>


        <!-- TAB 5: LAYANAN PESAN / HELPDESK -->
        <div id="tab-messages" class="tab-content card" style="display: none;">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <i class="fa-solid fa-comments" style="color: var(--primary);"></i> Layanan Pesan & Ticketing Support
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Komunikasi langsung dengan Tim Technical Support & Admin LIMS Kalibrasi PT Envirotama Solusindo.</p>
                </div>
            </div>

            <div class="chat-box" id="chatFeed">
                @forelse($messages as $msg)
                <div class="chat-msg {{ $msg->sender === 'klien' ? 'klien' : 'staff' }}">
                    <div style="font-weight: 700; font-size: 0.75rem; margin-bottom: 2px;">
                        {{ $msg->sender === 'klien' ? 'Anda (PIC Customer)' : 'Admin Kalibrasi LIMS' }}
                    </div>
                    <div>{{ $msg->message }}</div>
                    <div style="font-size: 0.68rem; opacity: 0.7; margin-top: 4px; text-align: right;">
                        {{ \Carbon\Carbon::parse($msg->created_at)->format('d/m/Y H:i') }}
                    </div>
                </div>
                @empty
                <div style="text-align: center; color: var(--slate-600); padding: 20px;">
                    Belum ada riwayat pesan. Ketik pesan Anda di bawah untuk memulai obrolan dengan tim kami.
                </div>
                @endforelse
            </div>

            <form id="formSendMessage" onsubmit="sendMessage(event)" style="margin-top: 20px; display: flex; gap: 12px;">
                @csrf
                <input type="text" id="chatInput" class="form-control" placeholder="Tuliskan pertanyaan atau kendala Anda di sini..." required>
                <button type="submit" class="btn-action" style="white-space: nowrap;">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pesan
                </button>
            </form>
        </div>

        <!-- TAB LINGKUP AKREDITASI KAN & CMC -->
        <div id="tab-lingkup" class="tab-content card" style="display: none;">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <i class="fa-solid fa-award" style="color: #d97706;"></i> Lingkup Akreditasi KAN LK-361-IDN & Kemampuan Ukur (CMC)
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Daftar kelompok alat terakreditasi KAN, rentang ukur, metode standar, dan ketidakpastian terbaik U95 (CMC).</p>
                </div>
                <a href="https://kan.or.id" target="_blank" class="btn-action btn-secondary" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-file-pdf" style="color: #dc2626;"></i> Download SK Lingkup KAN (PDF)
                </a>
            </div>

            <div style="margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap;">
                <div style="flex: 2; min-width: 260px;">
                    <input type="text" id="cmcSearchInput" class="form-control" placeholder="🔍 Cari nama alat, titik ukur, atau metode (contoh: Inkubator, Timbangan, pH Meter, Pressure Gauge)..." onkeyup="applyCmcFilterAndSort()" style="font-weight: 600;">
                </div>
                <div style="flex: 1; min-width: 180px;">
                    <select id="cmcCategoryFilter" class="form-control" onchange="applyCmcFilterAndSort()" style="font-weight: 600;">
                        <option value="all">Semua Kategori KAN</option>
                        <option value="Suhu & Kelembapan">Suhu & Kelembapan</option>
                        <option value="Massa">Massa</option>
                        <option value="Volume">Volume</option>
                        <option value="Tekanan (Pressure)">Tekanan (Pressure)</option>
                        <option value="Aliran (Flow)">Aliran (Flow)</option>
                        <option value="Instrumen Analitik">Instrumen Analitik</option>
                        <option value="Fotometri & Optik">Fotometri & Optik</option>
                        <option value="Akustik & Vibrasi">Akustik & Vibrasi</option>
                        <option value="Waktu & Frekuensi">Waktu & Frekuensi</option>
                        <option value="Kelistrikan">Kelistrikan</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 160px;">
                    <select id="cmcSortSelect" class="form-control" onchange="applyCmcFilterAndSort()" style="font-weight: 600;">
                        <option value="default">Urutan: Default No</option>
                        <option value="name_asc">Nama Alat (A-Z)</option>
                        <option value="name_desc">Nama Alat (Z-A)</option>
                        <option value="category">Urutkan Kategori</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Kategori KAN</th>
                            <th>Jenis / Kelompok Alat Ukur</th>
                            <th>Rentang Ukur Terakreditasi</th>
                            <th>Kemampuan Ukur (CMC U95)</th>
                            <th>Metode Standar Uji</th>
                        </tr>
                    </thead>
                    <tbody id="cmcTableBody">
                        @foreach($lingkupCmcList as $index => $cmc)
                        <tr data-category="{{ $cmc['kategori'] }}" data-alat="{{ $cmc['alat'] }}">
                            <td style="font-weight: 700; color: var(--slate-600);">{{ $index + 1 }}</td>
                            <td><span class="badge badge-info" style="font-size: 0.72rem;">{{ $cmc['kategori'] }}</span></td>
                            <td style="font-weight: 700; color: var(--slate-900);">{{ $cmc['alat'] }}</td>
                            <td><span style="font-family: monospace; font-weight: 700; color: #0284c7;">{{ $cmc['rentang'] }}</span></td>
                            <td><span style="font-family: monospace; font-weight: 800; color: #16a34a; background: #f0fdf4; border: 1px dashed #86efac; padding: 4px 10px; border-radius: 6px; display: inline-block;">± {{ $cmc['cmc'] }}</span></td>
                            <td style="font-size: 0.78rem; color: var(--slate-600); font-weight: 600;">{{ $cmc['metode'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function applyCmcFilterAndSort() {
                const searchVal = document.getElementById('cmcSearchInput').value.toLowerCase();
                const categoryVal = document.getElementById('cmcCategoryFilter').value;
                const sortVal = document.getElementById('cmcSortSelect').value;

                const tbody = document.getElementById('cmcTableBody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                // Filter
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    const category = row.getAttribute('data-category');
                    
                    const matchesSearch = text.includes(searchVal);
                    const matchesCategory = categoryVal === 'all' || category === categoryVal;

                    if (matchesSearch && matchesCategory) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Sort
                if (sortVal !== 'default') {
                    rows.sort((a, b) => {
                        if (sortVal === 'name_asc') {
                            return (a.getAttribute('data-alat') || '').localeCompare(b.getAttribute('data-alat') || '');
                        } else if (sortVal === 'name_desc') {
                            return (b.getAttribute('data-alat') || '').localeCompare(a.getAttribute('data-alat') || '');
                        } else if (sortVal === 'category') {
                            return (a.getAttribute('data-category') || '').localeCompare(b.getAttribute('data-category') || '');
                        }
                        return 0;
                    });
                    rows.forEach(row => tbody.appendChild(row));
                }
            }
        </script>



        <!-- TAB PENGUIAN LAB LINGKUNGAN (UNDER DEVELOPMENT) -->
        <div id="tab-lingkungan-dev" class="tab-content card" style="display: none; background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border: 2px dashed #86efac;">
            <div style="text-align: center; padding: 48px 24px;">
                <div style="width: 80px; height: 80px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 2.2rem; margin-bottom: 20px; box-shadow: 0 10px 15px -3px rgba(22, 163, 74, 0.2);">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <h3 style="font-weight: 800; font-size: 1.4rem; color: #14532d; margin-bottom: 10px;">
                    MODUL PENGUJIAN LAB LINGKUNGAN DALAM TAHAP PENGEMBANGAN
                </h3>
                <p style="font-size: 0.9rem; color: #166534; max-width: 650px; margin: 0 auto 24px; line-height: 1.6;">
                    Portal Pengujian Lingkungan (Air Limbah, Air Bersih, Udara Ambien, Cerobong Emisi Isokinetik, Kebauan, & Noise) sedang disiapkan oleh tim pengembang LIMS Enviro. Layanan <strong>Portal Kalibrasi Alat (LK-361-IDN)</strong> tetap aktif & berjalan 100% normal.
                </p>
                
                <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                    <button class="btn-action" style="background: #16a34a;" onclick="switchTab('tab-orders')">
                        <i class="fa-solid fa-toolbox"></i> Ke Portal Kalibrasi Alat
                    </button>
                    <a href="https://wa.me/628123456789" target="_blank" class="btn-action btn-secondary">
                        <i class="fa-brands fa-whatsapp" style="color: #25d366;"></i> Hubungi Marketing Lab Lingkungan
                    </a>
                </div>
            </div>
        </div>

        </main>
    </div>



    <!-- MODAL AJUKAN KALIBRASI BARU -->
    <div class="modal-overlay" id="modal-request-kalibrasi">
        <div class="modal-card" style="max-width: 980px; width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h3 id="modalRequestKalibrasiTitle" style="font-weight: 800; font-size: 1.15rem; color: var(--slate-900);">
                        <i class="fa-solid fa-plus-circle" style="color: var(--primary);"></i> Form Permintaan Kalibrasi Alat Baru
                    </h3>
                    <p style="font-size: 0.78rem; color: var(--slate-600); margin-top: 2px;">Isikan rincian peralatan yang ingin dikalibrasi (Nama Alat, Merk, Tipe, No. Seri, Rentang Ukur, Qty) untuk penerbitan Quotation Penawaran Harga.</p>
                </div>
                <button onclick="closeModal('modal-request-kalibrasi')" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--slate-600);">&times;</button>
            </div>


            <form id="formKalibrasiRequest" onsubmit="submitKalibrasiForm(event)">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 20px; background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    @php
                        $latestOrderWithPic = $kalibrasiOrders->first(fn($o) => !empty($o->kontak_person));
                        $defaultContactPerson = (!empty($customerInfo->kontak_person) ? $customerInfo->kontak_person : ($latestOrderWithPic->kontak_person ?? ''));

                        $latestOrderWithHp = $kalibrasiOrders->first(fn($o) => !empty($o->no_hp_email));
                        $defaultNoHpEmail = (!empty($customerInfo->no_hp_email) ? $customerInfo->no_hp_email : (!empty($customerInfo->no_hp) ? $customerInfo->no_hp : (!empty($customerInfo->email) ? $customerInfo->email : ($latestOrderWithHp->no_hp_email ?? ''))));
                    @endphp
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 700; font-size: 0.82rem;">Kontak Person / PIC Perusahaan *</label>
                        <input type="text" name="contact_person" id="inputContactPersonModal" class="form-control" value="{{ $defaultContactPerson }}" placeholder="Nama Kontak Person / PIC" required style="font-weight: 600;">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 700; font-size: 0.82rem;">No. HP / WhatsApp / Email *</label>
                        <input type="text" name="no_hp_email" id="inputNoHpEmailModal" class="form-control" value="{{ $defaultNoHpEmail }}" placeholder="No. HP / WhatsApp / Email" required style="font-weight: 600;">
                    </div>


                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 700; font-size: 0.82rem;">Tipe Pekerjaan *</label>
                        <select name="tipe_pekerjaan" class="form-control" required style="font-weight: 600;">
                            <option value="in_lab">In Lab (Alat Dikirim ke Lab LIMS Enviro)</option>
                            <option value="on_site">On Site (Teknisi Kalibrasi Datang ke Lokasi Pabrik/Lab Anda)</option>
                        </select>
                    </div>
                </div>

                <!-- TABEL MULTI-ITEM RINCIAN ALAT (SEPERTI QUOTATION KALIBRASI) -->
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <div style="font-weight: 800; font-size: 0.9rem; color: var(--slate-900);">
                            <i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Daftar Rincian Peralatan Kalibrasi
                        </div>
                        <button type="button" class="btn-action" style="padding: 6px 14px; font-size: 0.78rem; background: #0284c7;" onclick="addRequestItemRow()">
                            <i class="fa-solid fa-plus"></i> Tambah Baris Alat
                        </button>
                    </div>

                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <table class="custom-table" style="font-size: 0.8rem; margin-bottom: 0;">
                            <thead>
                                <tr style="background: #f1f5f9;">
                                    <th style="width: 35px; text-align: center;">NO</th>
                                    <th style="min-width: 170px;">NAMA ALAT KALIBRASI *</th>
                                    <th style="min-width: 130px;">MERK / TYPE</th>
                                    <th style="min-width: 110px;">NO. SERI</th>
                                    <th style="min-width: 170px;">CAL POINT *</th>
                                    <th style="min-width: 130px;">METHOD</th>
                                    <th style="min-width: 100px;">CAL LOC</th>
                                    <th style="width: 75px; text-align: center;">COPY</th>
                                    <th style="width: 40px; text-align: center;">HAPUS</th>
                                </tr>
                            </thead>
                            <tbody id="requestItemsTbody">
                                <!-- Populated dynamically by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <datalist id="masterAlatDatalist">
                    @php
                        $uniqueAlatNames = array_unique(array_merge(
                            array_column($masterParametersData, 'nama_parameter'),
                            array_column($masterHargasData, 'nama_alat')
                        ));
                        natcasesort($uniqueAlatNames);
                    @endphp
                    @foreach($uniqueAlatNames as $alatName)
                        @if(!empty(trim($alatName)))
                            <option value="{{ trim($alatName) }}"></option>
                        @endif
                    @endforeach
                </datalist>



                <div class="form-group">
                    <label style="font-weight: 700; font-size: 0.82rem;">Catatan / Instruksi Khusus (Optional)</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan alamat pengujian onsite atau permintaan perkiraan tanggal pengerjaan..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn-action btn-secondary" onclick="closeModal('modal-request-kalibrasi')">Batal</button>
                    <button type="submit" id="btnSubmitRequestModal" class="btn-action" style="padding: 10px 20px; font-weight: 800;">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Permintaan Quotation
                    </button>
                </div>

            </form>
        </div>
    </div>


    <!-- MODAL PILIH CAL POINT DARI MASTER DATA -->
    <div class="modal-overlay" id="modal-pick-calpoint">
        <div class="modal-card" style="max-width: 650px; width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h3 style="font-weight: 800; font-size: 1.05rem; color: var(--slate-900);">
                        <i class="fa-solid fa-thumbtack" style="color: #0284c7;"></i> Pilih Cal Point (Titik Ukur) dari Master Data
                    </h3>
                    <p style="font-size: 0.75rem; color: var(--slate-600); margin-top: 2px;">Pilih titik ukur standar dari Master Parameter atau ketik sendiri pada form.</p>
                </div>
                <button onclick="closeModal('modal-pick-calpoint')" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--slate-600);">&times;</button>
            </div>

            <div style="margin-bottom: 14px;">
                <input type="text" id="filterMasterCalPointInput" class="form-control" placeholder="🔍 Filter nama parameter (contoh: Thermometer, Suhu, Timbangan, pH)..." onkeyup="filterMasterCalPointList()" style="font-size: 0.82rem; font-weight: 600;">
            </div>

            <div id="calPointMasterList" style="max-height: 380px; overflow-y: auto; padding-right: 4px;">
                <!-- Populated dynamically by JS -->
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn-action btn-secondary" onclick="closeModal('modal-pick-calpoint')">Batal</button>
                <button type="button" class="btn-action" style="padding: 8px 18px; font-weight: 800; background: linear-gradient(135deg, #10b981, #047857);" onclick="applySelectedCheckboxesToRow()">
                    <i class="fa-solid fa-check"></i> Gunakan Point Terpilih
                </button>
            </div>

        </div>
    </div>

    <!-- MODAL KONFIRMASI PEMBAYARAN -->
    <div class="modal-overlay" id="modal-payment">

        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-weight: 800; font-size: 1.1rem; color: var(--slate-900);">
                    <i class="fa-solid fa-file-invoice" style="color: var(--primary);"></i> Konfirmasi Pembayaran Invoice
                </h3>
                <button onclick="closeModal('modal-payment')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--slate-600);">&times;</button>
            </div>

            <form id="formPaymentModal" onsubmit="submitPaymentForm(event)">
                @csrf
                <input type="hidden" name="order_id" id="paymentOrderId">

                <div class="form-group">
                    <label>Order / Permintaan Kalibrasi</label>
                    <input type="text" id="paymentOrderNo" class="form-control" readonly style="background: #f1f5f9; font-weight: 700;">
                </div>

                <div class="form-group">
                    <label>Nomor Referensi Bank / Struk Transfer *</label>
                    <input type="text" name="nomor_referensi" class="form-control" placeholder="Contoh: TRF-BCA-987654321" required>
                </div>

                <div class="form-group">
                    <label>Catatan Pembayaran (Nominal, Bank Pengirim, Nama Rekening)</label>
                    <textarea name="catatan_bayar" class="form-control" rows="3" placeholder="Transfer Bank BCA a/n PT ABC sebesar Rp 5.000.000..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                    <button type="button" class="btn-action btn-secondary" onclick="closeModal('modal-payment')">Batal</button>
                    <button type="submit" class="btn-action">Kirim Konfirmasi Pembayaran</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            
            document.getElementById(tabId).style.display = 'block';
            event.currentTarget.classList.add('active');
        }

        const masterParams = @json($masterParametersData);
        let reqItemIndex = 0;
        let activeCalPointRowIdx = null;

        function addRequestItemRow(nama = '', merk = '', tipe = '', noseri = '', calPoint = '', metode = '', calLoc = 'in_lab', qty = 1) {
            const tbody = document.getElementById('requestItemsTbody');
            if (!tbody) return;
            const rowIdx = reqItemIndex;
            const rowId = `reqRow_${rowIdx}`;
            
            const tr = document.createElement('tr');
            tr.id = rowId;
            tr.innerHTML = `
                <td style="font-weight:700; text-align:center;" class="row-no">1</td>
                <td>
                    <input type="text" name="items[${rowIdx}][nama_alat]" list="masterAlatDatalist" class="form-control" placeholder="Nama Alat Kalibrasi" value="${nama}" required style="padding: 6px 8px; font-size: 0.8rem; font-weight:600;" onchange="autofillItemDetails(this, ${rowIdx})">
                </td>
                <td>
                    <input type="text" name="items[${rowIdx}][merk]" class="form-control" placeholder="Merk / Type" value="${merk}" style="padding: 6px 8px; font-size: 0.8rem;">
                </td>
                <td>
                    <input type="text" name="items[${rowIdx}][no_seri]" class="form-control" placeholder="No. Seri" value="${noseri}" style="padding: 6px 8px; font-size: 0.8rem; font-family:monospace;">
                </td>
                <td>
                    <textarea name="items[${rowIdx}][cal_point]" id="calPoint_${rowIdx}" class="form-control" rows="2" placeholder="Cal Point (Wajib Diisi *)" required style="padding: 6px 8px; font-size: 0.78rem;">${calPoint}</textarea>
                    <button type="button" class="btn-action" style="padding: 3px 6px; font-size: 0.7rem; margin-top: 4px; background: #e0f2fe; color: #0284c7; width: 100%; border: 1px solid #bae6fd; font-weight:700;" onclick="openCalPointModal(${rowIdx})">
                        📌 Pilih Point (Multi)
                    </button>
                </td>
                <td>
                    <textarea name="items[${rowIdx}][metode]" id="method_${rowIdx}" class="form-control" rows="2" placeholder="Method (IKM / Standar)" style="padding: 6px 8px; font-size: 0.78rem;">${metode}</textarea>
                </td>
                <td>
                    <select name="items[${rowIdx}][cal_loc]" class="form-control" style="padding: 6px 4px; font-size: 0.78rem; font-weight: 600;">
                        <option value="in_lab" ${calLoc === 'in_lab' ? 'selected' : ''}>In Lab</option>
                        <option value="on_site" ${calLoc === 'on_site' ? 'selected' : ''}>On Site</option>
                    </select>
                    <input type="hidden" name="items[${rowIdx}][qty]" value="1">
                </td>
                <td style="text-align: center; width: 75px;">
                    <button type="button" onclick="copyRequestItemRow(${rowIdx})" title="Copy / Duplikat Baris Alat Ini" class="btn-action" style="padding: 4px 8px; font-size: 0.72rem; background: #f59e0b; color: white; border: none; border-radius: 6px; font-weight: 700; cursor: pointer;">
                        📋 Copy
                    </button>
                </td>
                <td style="text-align: center; width: 40px;">
                    <button type="button" onclick="removeRequestItemRow('${rowId}')" style="background: #fee2e2; border: 1px solid #fca5a5; color: #dc2626; border-radius: 6px; padding: 4px 8px; cursor: pointer; font-weight: 700;">&times;</button>
                </td>
            `;
            tbody.appendChild(tr);
            reqItemIndex++;
            updateRequestRowNumbers();
        }

        function copyRequestItemRow(rowIdx) {
            const countStr = prompt("Berapa kali baris alat ini ingin di-copy / duplikasi?", "1");
            if (countStr === null) return;
            const count = parseInt(countStr) || 1;
            if (count < 1) return;

            const tr = document.getElementById(`reqRow_${rowIdx}`);
            if (!tr) return;

            const namaInput = tr.querySelector(`input[name="items[${rowIdx}][nama_alat]"]`);
            const merkInput = tr.querySelector(`input[name="items[${rowIdx}][merk]"]`);
            const noSeriInput = tr.querySelector(`input[name="items[${rowIdx}][no_seri]"]`);
            const calPointInput = tr.querySelector(`textarea[name="items[${rowIdx}][cal_point]"]`);
            const methodInput = tr.querySelector(`textarea[name="items[${rowIdx}][metode]"]`);
            const calLocSelect = tr.querySelector(`select[name="items[${rowIdx}][cal_loc]"]`);

            const nama = namaInput ? namaInput.value : '';
            const merk = merkInput ? merkInput.value : '';
            const noseri = noSeriInput ? noSeriInput.value : '';
            const calPoint = calPointInput ? calPointInput.value : '';
            const metode = methodInput ? methodInput.value : '';
            const calLoc = calLocSelect ? calLocSelect.value : 'in_lab';

            for (let i = 0; i < count; i++) {
                addRequestItemRow(nama, merk, '', noseri, calPoint, metode, calLoc, 1);
            }
        }

        function autofillItemDetails(inputEl, rowIdx) {
            const val = inputEl.value.trim().toLowerCase();
            if (!val || !masterParams || masterParams.length === 0) return;
            
            const foundParam = masterParams.find(p => p.nama_parameter && p.nama_parameter.toLowerCase().includes(val));
            if (foundParam) {
                const methodEl = document.getElementById(`method_${rowIdx}`);
                const calPointEl = document.getElementById(`calPoint_${rowIdx}`);
                if (methodEl && !methodEl.value) {
                    methodEl.value = foundParam.metode || '';
                }
                if (calPointEl && !calPointEl.value) {
                    calPointEl.value = foundParam.titik_kalibrasi || foundParam.rentang_ukur || '';
                }
            }
        }

        function pickMasterCalPoint(rowIdx) {
            openCalPointModal(rowIdx);
        }

        function openCalPointModal(rowIdx) {

            activeCalPointRowIdx = rowIdx;
            
            const tr = document.getElementById(`reqRow_${rowIdx}`);
            let toolName = '';
            let existingCalPointVal = '';
            if (tr) {
                const nameInput = tr.querySelector(`input[name="items[${rowIdx}][nama_alat]"]`);
                if (nameInput) {
                    toolName = nameInput.value.trim();
                }
                const calPointInput = document.getElementById(`calPoint_${rowIdx}`);
                if (calPointInput) {
                    existingCalPointVal = calPointInput.value.trim();
                }
            }
            
            const filterInput = document.getElementById('filterMasterCalPointInput');
            if (filterInput) {
                filterInput.value = toolName;
            }
            
            renderCalPointMasterList(toolName, existingCalPointVal);
            openModal('modal-pick-calpoint');
        }

        function renderCalPointMasterList(filterKeyword = '', existingCalPointVal = '') {
            const container = document.getElementById('calPointMasterList');
            if (!container) return;
            container.innerHTML = '';

            if (activeCalPointRowIdx !== null && !existingCalPointVal) {
                const calPointInput = document.getElementById(`calPoint_${activeCalPointRowIdx}`);
                if (calPointInput) {
                    existingCalPointVal = calPointInput.value.trim();
                }
            }

            const existingPointsSet = existingCalPointVal
                ? existingCalPointVal.split(/;|,|\n/).map(s => s.trim().toLowerCase()).filter(Boolean)
                : [];

            const kw = filterKeyword.toLowerCase().trim();
            let filtered = masterParams ? masterParams.filter(p => {
                if (!kw) return true;
                return (p.nama_parameter && p.nama_parameter.toLowerCase().includes(kw)) ||
                       (p.rentang_ukur && p.rentang_ukur.toLowerCase().includes(kw)) ||
                       (p.metode && p.metode.toLowerCase().includes(kw));
            }) : [];

            let isHint = false;
            if (kw && filtered.length > 0) {
                isHint = true;
            }

            if (isHint) {
                container.innerHTML += `
                    <div style="background: #e0f2fe; border: 1px solid #7dd3fc; color: #0369a1; padding: 8px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa-solid fa-lightbulb" style="color: #f59e0b;"></i> Hint Cal Point Sesuai Alat ("${filterKeyword}"):</span>
                        <button type="button" onclick="clearCalPointFilter()" style="background: none; border: none; color: #0284c7; font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 0.75rem;">Lihat Semua Master</button>
                    </div>
                `;
            } else if (kw && filtered.length === 0) {
                container.innerHTML += `
                    <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 8px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                        <span>Tidak ada master parameter khusus "${filterKeyword}". Menampilkan seluruh master:</span>
                        <button type="button" onclick="clearCalPointFilter()" style="background: none; border: none; color: #b91c1c; font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 0.75rem;">Reset Filter</button>
                    </div>
                `;
                filtered = masterParams || [];
            }

            if (filtered.length > 0) {
                filtered.forEach((param, paramIdx) => {
                    const rawPoints = param.rentang_ukur || '';
                    let pointsArray = rawPoints.split(';').map(pt => pt.trim()).filter(pt => pt.length > 0);
                    if (pointsArray.length <= 1 && rawPoints.includes(',')) {
                        pointsArray = rawPoints.split(',').map(pt => pt.trim()).filter(pt => pt.length > 0);
                    }
                    if (pointsArray.length === 0 && rawPoints.trim().length > 0) {
                        pointsArray = [rawPoints.trim()];
                    }

                    const safeMethod = (param.metode || '').replace(/'/g, "\\'").replace(/"/g, "&quot;");

                    let checkboxesHtml = '';
                    pointsArray.forEach(pt => {
                        const safePt = pt.replace(/'/g, "\\'").replace(/"/g, "&quot;");
                        const isChecked = existingPointsSet.includes(pt.trim().toLowerCase());
                        checkboxesHtml += `
                            <label style="display: inline-flex; align-items: center; gap: 6px; background: ${isChecked ? '#e0f2fe' : '#ffffff'}; border: 1px solid ${isChecked ? '#7dd3fc' : '#cbd5e1'}; padding: 6px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer; user-select: none; transition: all 0.2s;">
                                <input type="checkbox" class="cal-point-cb" data-method="${safeMethod}" value="${safePt}" ${isChecked ? 'checked' : ''} style="cursor: pointer; width: 15px; height: 15px; accent-color: #0284c7;">
                                <span style="color: #0f172a; ${isChecked ? 'font-weight: 800;' : ''}">${pt}</span>
                            </label>
                        `;
                    });

                    container.innerHTML += `
                        <div class="param-card-item" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px dashed #cbd5e1; flex-wrap: wrap; gap: 8px;">
                                <div>
                                    <span style="font-weight: 800; font-size: 0.88rem; color: #0f172a;">${param.nama_parameter}</span>
                                    <span style="font-size: 0.72rem; color: #0284c7; font-weight: 700; margin-left: 8px;">(Method: ${param.metode || '-'})</span>
                                </div>
                                <div style="display: flex; gap: 6px;">
                                    <button type="button" onclick="toggleAllParamCheckboxes(${paramIdx}, true)" style="font-size: 0.7rem; background: #e0f2fe; border: 1px solid #bae6fd; color: #0284c7; border-radius: 5px; padding: 3px 8px; cursor: pointer; font-weight:700;">Check Semua</button>
                                    <button type="button" onclick="toggleAllParamCheckboxes(${paramIdx}, false)" style="font-size: 0.7rem; background: #f1f5f9; border: 1px solid #cbd5e1; color: #64748b; border-radius: 5px; padding: 3px 8px; cursor: pointer; font-weight:700;">Uncheck Semua</button>
                                </div>
                            </div>
                            <div class="param-cb-container-${paramIdx}" style="display: flex; flex-wrap: wrap; gap: 8px; max-height: 160px; overflow-y: auto; padding: 4px 0;">
                                ${checkboxesHtml}
                            </div>
                        </div>
                    `;
                });
            }
        }

        function toggleAllParamCheckboxes(paramIdx, checkState) {
            const container = document.querySelector(`.param-cb-container-${paramIdx}`);
            if (container) {
                const checkboxes = container.querySelectorAll('input.cal-point-cb');
                checkboxes.forEach(cb => cb.checked = checkState);
            }
        }

        function applySelectedCheckboxesToRow() {
            if (activeCalPointRowIdx === null) {
                closeModal('modal-pick-calpoint');
                return;
            }

            const checkedNodes = document.querySelectorAll('#calPointMasterList input.cal-point-cb:checked');
            const selectedPoints = [];
            let chosenMethod = '';

            checkedNodes.forEach(cb => {
                selectedPoints.push(cb.value);
                if (!chosenMethod && cb.dataset.method) {
                    chosenMethod = cb.dataset.method;
                }
            });

            if (selectedPoints.length === 0) {
                alert('Silakan centang (check) minimal 1 titik ukur Cal Point.');
                return;
            }

            const pointsString = selectedPoints.join('; ');
            const calPointEl = document.getElementById(`calPoint_${activeCalPointRowIdx}`);
            const methodEl = document.getElementById(`method_${activeCalPointRowIdx}`);

            if (calPointEl) {
                calPointEl.value = pointsString;
            }
            if (methodEl && chosenMethod && !methodEl.value) {
                methodEl.value = chosenMethod;
            }

            closeModal('modal-pick-calpoint');
        }

        function clearCalPointFilter() {
            const input = document.getElementById('filterMasterCalPointInput');
            if (input) input.value = '';
            renderCalPointMasterList('');
        }



        function filterMasterCalPointList() {
            const input = document.getElementById('filterMasterCalPointInput');
            renderCalPointMasterList(input ? input.value : '');
        }

        function applyCalPointToRow(pointsText, metodeText) {
            if (activeCalPointRowIdx !== null) {
                const calPointEl = document.getElementById(`calPoint_${activeCalPointRowIdx}`);
                const methodEl = document.getElementById(`method_${activeCalPointRowIdx}`);
                if (calPointEl) calPointEl.value = pointsText;
                if (methodEl && metodeText && !methodEl.value) methodEl.value = metodeText;
            }
            closeModal('modal-pick-calpoint');
        }

        function removeRequestItemRow(rowId) {
            const tbody = document.getElementById('requestItemsTbody');
            const row = document.getElementById(rowId);
            if (row) {
                if (tbody.querySelectorAll('tr').length <= 1) {
                    alert('Minimal harus ada 1 item alat dalam permintaan kalibrasi.');
                    return;
                }
                row.remove();
                updateRequestRowNumbers();
            }
        }


        function updateRequestRowNumbers() {
            const tbody = document.getElementById('requestItemsTbody');
            if (!tbody) return;
            const rows = tbody.querySelectorAll('tr');
            rows.forEach((r, i) => {
                const noCell = r.querySelector('.row-no');
                if (noCell) noCell.innerText = i + 1;
            });
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            if (modalId === 'modal-request-kalibrasi') {
                const form = document.getElementById('formKalibrasiRequest');
                if (form && form.getAttribute('data-mode') !== 'edit') {
                    form.setAttribute('data-mode', 'create');
                    form.removeAttribute('data-id');
                    const titleEl = document.getElementById('modalRequestKalibrasiTitle');
                    if (titleEl) {
                        titleEl.innerHTML = `<i class="fa-solid fa-plus-circle" style="color: var(--primary);"></i> Form Permintaan Kalibrasi Alat Baru`;
                    }
                    const submitBtn = document.getElementById('btnSubmitRequestModal');
                    if (submitBtn) {
                        submitBtn.innerHTML = `<i class="fa-solid fa-paper-plane"></i> Kirim Permintaan Quotation`;
                    }

                    const cpInput = document.getElementById('inputContactPersonModal');
                    if (cpInput && !cpInput.value.trim()) {
                        cpInput.value = "{{ $defaultContactPerson }}";
                    }
                    const hpInput = document.getElementById('inputNoHpEmailModal');
                    if (hpInput && !hpInput.value.trim()) {
                        hpInput.value = "{{ $defaultNoHpEmail }}";
                    }

                    const tbody = document.getElementById('requestItemsTbody');
                    if (tbody && tbody.children.length === 0) {
                        addRequestItemRow();
                    }
                }
            }
        }


        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            if (modalId === 'modal-request-kalibrasi') {
                const form = document.getElementById('formKalibrasiRequest');
                if (form) {
                    form.removeAttribute('data-mode');
                    form.removeAttribute('data-id');
                }
            }
        }

        function editKalibrasiRequestModal(orderData) {
            const order = typeof orderData === 'string' ? JSON.parse(orderData) : orderData;
            
            const form = document.getElementById('formKalibrasiRequest');
            if (!form) return;

            form.setAttribute('data-mode', 'edit');
            form.setAttribute('data-id', order.id);
            
            const titleEl = document.getElementById('modalRequestKalibrasiTitle');
            if (titleEl) {
                titleEl.innerHTML = `<i class="fa-solid fa-pen-to-square" style="color: #f59e0b;"></i> Edit Permintaan Kalibrasi (#${order.no_order})`;
            }

            const submitBtn = document.getElementById('btnSubmitRequestModal');
            if (submitBtn) {
                submitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Permintaan`;
            }

            const cp = form.querySelector('input[name="contact_person"]');
            if (cp) cp.value = order.kontak_person || '';

            const hp = form.querySelector('input[name="no_hp_email"]');
            if (hp) hp.value = order.no_hp_email || '';

            const tp = form.querySelector('select[name="tipe_pekerjaan"]');
            if (tp) tp.value = order.tipe_pekerjaan || 'in_lab';

            const cat = form.querySelector('textarea[name="catatan"]');
            if (cat) {
                let cleanCat = (order.catatan || '').replace(/Permintaan Kalibrasi (.*) dari Portal Customer\.\nDaftar Alat:\n[\s\S]*?(Catatan Tambahan: |$)/g, '');
                cat.value = cleanCat.trim();
            }

            const tbody = document.getElementById('requestItemsTbody');
            if (tbody) tbody.innerHTML = '';

            if (order.alats && order.alats.length > 0) {
                order.alats.forEach(alt => {
                    addRequestItemRow(
                        alt.nama_alat || '',
                        alt.merk || '',
                        alt.tipe_model || alt.tipe_tanpa_merk || '',
                        alt.nomor_seri || alt.no_seri || '',
                        alt.cal_poin_parameter || alt.rentang_ukur || '',
                        alt.metode || '',
                        alt.lokasi_pekerjaan || order.tipe_pekerjaan || 'in_lab',
                        alt.qty || 1
                    );
                });
            } else {
                addRequestItemRow();
            }

            openModal('modal-request-kalibrasi');
        }

        function getClientPortalBase() {
            return window.location.pathname.replace(/\/portal.*$/, '');
        }

        async function deleteKalibrasiRequest(orderId, orderNo) {
            if (!confirm(`Apakah Anda yakin ingin menghapus permohonan kalibrasi #${orderNo}?`)) {
                return;
            }

            try {
                const res = await fetch(`${getClientPortalBase()}/request-kalibrasi/${orderId}/delete`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert("Gagal menghapus: " + (data.message || 'Error'));
                }
            } catch (err) {
                console.error(err);
                alert("Terjadi kesalahan jaringan.");
            }
        }


        function prefillKalibrasiRequest(alatName) {
            const tbody = document.getElementById('requestItemsTbody');
            if (tbody) tbody.innerHTML = '';
            addRequestItemRow(alatName, '', '', '', '', 1);
            openModal('modal-request-kalibrasi');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('requestItemsTbody');
            if (tbody && tbody.children.length === 0) {
                addRequestItemRow();
            }
        });


        function openPaymentModal(orderId, orderNo) {
            document.getElementById('paymentOrderId').value = orderId;
            document.getElementById('paymentOrderNo').value = orderNo;
            openModal('modal-payment');
        }

        async function submitKalibrasiForm(e) {
            e.preventDefault();
            const form = e.target;

            // Validate CAL POINT is not empty for any row
            const calPoints = form.querySelectorAll('textarea[name*="[cal_point]"]');
            let missingCalPoint = false;
            calPoints.forEach(el => {
                if (!el.value.trim()) {
                    missingCalPoint = true;
                    el.style.border = '2px solid #ef4444';
                } else {
                    el.style.border = '1px solid #cbd5e1';
                }
            });

            if (missingCalPoint) {
                alert("CAL POINT (Titik Ukur Kalibrasi) wajib diisi untuk semua baris alat! Silakan ketik atau pilih dari master point.");
                return;
            }

            const formData = new FormData(form);
            const mode = form.getAttribute('data-mode') || 'create';
            const orderId = form.getAttribute('data-id') || '';

            let targetUrl = `${getClientPortalBase()}/request-kalibrasi`;
            if (mode === 'edit' && orderId) {
                targetUrl = `${getClientPortalBase()}/request-kalibrasi/${orderId}/update`;
            }


            try {
                const res = await fetch(targetUrl, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    closeModal('modal-request-kalibrasi');
                    location.reload();
                } else {
                    alert("Gagal menyimpan: " + (data.message || 'Error'));
                }
            } catch (err) {
                console.error(err);
                alert("Terjadi kesalahan jaringan saat mengirim data.");
            }
        }


        async function submitPaymentProof(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const res = await fetch(`${getClientPortalBase()}/upload-bukti-bayar`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    closeModal('modal-payment');
                    location.reload();
                } else {
                    alert("Gagal mengirim konfirmasi: " + (data.message || 'Error'));
                }
            } catch (err) {
                alert("Terjadi kesalahan jaringan.");
            }
        }

        async function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (!message) return;

            const formData = new FormData();
            formData.append('message', message);

            try {
                const res = await fetch("{{ route('klien.pesan.send') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    input.value = '';
                    location.reload();
                }
            } catch (err) {
                alert("Gagal mengirim pesan.");
            }
        }

        function applyReqFilterAndSort() {
            const searchInput = document.getElementById('reqSearchInput');
            const sortSelect = document.getElementById('reqSortSelect');
            if (!searchInput || !sortSelect) return;
            const searchVal = searchInput.value.toLowerCase();
            const sortVal = sortSelect.value;

            const tbody = document.getElementById('reqTableBody');
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr[data-noorder]'));

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(searchVal)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            if (sortVal !== 'default') {
                rows.sort((a, b) => {
                    if (sortVal === 'order_asc') {
                        return (a.getAttribute('data-noorder') || '').localeCompare(b.getAttribute('data-noorder') || '');
                    } else if (sortVal === 'order_desc') {
                        return (b.getAttribute('data-noorder') || '').localeCompare(a.getAttribute('data-noorder') || '');
                    }
                    return 0;
                });
                rows.forEach(row => tbody.appendChild(row));
            }
        }

        function applyPoFilterAndSort() {
            const searchInput = document.getElementById('poSearchInput');
            const statusFilter = document.getElementById('poStatusFilter');
            const sortSelect = document.getElementById('poSortSelect');
            if (!searchInput || !statusFilter || !sortSelect) return;
            const searchVal = searchInput.value.toLowerCase();
            const statusVal = statusFilter.value;
            const sortVal = sortSelect.value;

            const tbody = document.getElementById('poTableBody');
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr[data-noorder]'));

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const status = row.getAttribute('data-status');
                
                const matchesSearch = text.includes(searchVal);
                const matchesStatus = statusVal === 'all' || status === statusVal;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            if (sortVal !== 'default') {
                rows.sort((a, b) => {
                    if (sortVal === 'order_asc') {
                        return (a.getAttribute('data-noorder') || '').localeCompare(b.getAttribute('data-noorder') || '');
                    } else if (sortVal === 'order_desc') {
                        return (b.getAttribute('data-noorder') || '').localeCompare(a.getAttribute('data-noorder') || '');
                    }
                    return 0;
                });
                rows.forEach(row => tbody.appendChild(row));
            }
        }



        function showOrderDetail(orderJsonData) {
            const order = typeof orderJsonData === 'string' ? JSON.parse(orderJsonData) : orderJsonData;
            document.getElementById('modalOrderNoTitle').innerText = order.no_order;
            document.getElementById('modalQuotationNo').innerText = order.no_quotation || '-';
            document.getElementById('modalPoNo').innerText = order.no_po || '-';
            document.getElementById('modalOrderType').innerText = (order.tipe_pekerjaan || '').toUpperCase().replace('_', ' ');
            document.getElementById('modalOrderStatus').innerText = order.status || '-';

            const tbody = document.getElementById('modalOrderAlatsTableBody');
            tbody.innerHTML = '';

            if (order.alats && order.alats.length > 0) {
                order.alats.forEach((alt, idx) => {
                    const hasCert = alt.sertifikat && (alt.sertifikat.file_path || alt.sertifikat.drive_url);
                    let downloadBtn = `<span style="font-size: 0.75rem; color: #f59e0b; font-weight: 700;"><i class="fa-solid fa-clock"></i> Menunggu Scan COA</span>`;
                    if (hasCert) {
                        const url = alt.sertifikat.drive_url ? alt.sertifikat.drive_url : `/kalibrasi/sertifikat/${alt.sertifikat.id}/pdf`;
                        downloadBtn = `<a href="${url}" target="_blank" class="btn-action" style="padding: 6px 12px; font-size: 0.75rem; background: #16a34a; text-decoration: none;"><i class="fa-solid fa-file-pdf"></i> Unduh COA (Scan TTD Basah)</a>`;
                    }

                    tbody.innerHTML += `
                        <tr>
                            <td style="font-weight:700;">${idx + 1}</td>
                            <td style="font-weight:800; color: var(--slate-900);">${alt.nama_alat || '-'}</td>
                            <td>${alt.merk || '-'} ${alt.tipe_tanpa_merk ? '/ ' + alt.tipe_tanpa_merk : ''}</td>
                            <td style="font-family:monospace; font-weight:700; color: var(--primary);">${alt.no_seri || '-'}</td>
                            <td><span class="badge badge-info">${alt.status_proses || 'Proses'}</span></td>
                            <td>${downloadBtn}</td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; padding:20px; color:#64748b;">Belum ada item alat terdaftar pada order ini.</td></tr>`;
            }

            openModal('modal-order-detail');
        }

        function switchCustomerPortalMode(mode) {
            const btnKal = document.getElementById('btnModeKalibrasi');
            const btnPeng = document.getElementById('btnModePengujian');
            const brandBadge = document.getElementById('portalBrandBadge');

            if (mode === 'kalibrasi') {
                btnKal.style.background = '#0284c7';
                btnKal.style.color = 'white';
                btnPeng.style.background = '#1e293b';
                btnPeng.style.color = '#94a3b8';
                if (brandBadge) brandBadge.innerText = 'KAN LK-361-IDN';
                switchTab('tab-requests');
            } else {
                btnPeng.style.background = '#16a34a';
                btnPeng.style.color = 'white';
                btnKal.style.background = '#1e293b';
                btnKal.style.color = '#94a3b8';
                if (brandBadge) brandBadge.innerText = 'LAB PENGUJI LINGKUNGAN';
                switchTab('tab-lingkungan-dev');
            }
        }
    </script>

    <!-- MODAL POP-UP DETAIL ORDER & INVENTARIS ALAT -->
    <div class="modal-overlay" id="modal-order-detail">
        <div class="modal-card" style="max-width: 900px; width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h3 style="font-weight: 800; font-size: 1.15rem; color: var(--slate-900);">
                        <i class="fa-solid fa-toolbox" style="color: var(--primary);"></i> Rincian Order & Inventaris Alat: <span id="modalOrderNoTitle" style="color: var(--primary);"></span>
                    </h3>
                    <p style="font-size: 0.78rem; color: var(--slate-600); margin-top: 2px;">Daftar peralatan terdaftar dan pengunduhan file Sertifikat Kalibrasi (COA Scanned TTD Basah).</p>
                </div>
                <button onclick="closeModal('modal-order-detail')" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--slate-600);">&times;</button>
            </div>

            <!-- DETAIL ORDER METADATA -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; border: 1px solid #e2e8f0;">
                <div>
                    <div style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">No. Quotation</div>
                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-900);" id="modalQuotationNo">-</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">No. PO Klien</div>
                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-900);" id="modalPoNo">-</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Tipe Pekerjaan</div>
                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--primary);" id="modalOrderType">-</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Status Order</div>
                    <div style="font-weight: 800; font-size: 0.85rem; color: #16a34a;" id="modalOrderStatus">-</div>
                </div>
            </div>

            <!-- TABLE ITEM ALAT DALAM ORDER -->
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Nama Alat Ukur</th>
                            <th>Merk / Tipe</th>
                            <th>No. Seri Alat</th>
                            <th>Status Proses</th>
                            <th>Berkas Sertifikat (COA)</th>
                        </tr>
                    </thead>
                    <tbody id="modalOrderAlatsTableBody">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="btn-action btn-secondary" onclick="closeModal('modal-order-detail')">Tutup</button>
            </div>
        </div>
    </div>
</body>
</html>

