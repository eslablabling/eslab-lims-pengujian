<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin Central Hub | PT Envirotama Solusindo</title>
    
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    
    <style>
        :root {
            --bg-dark: #090d16;
            --card-bg: #111827;
            --accent-gold: #f59e0b;
            --accent-blue: #0284c7;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #1f2937;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-dark); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }

        .top-nav {
            background: #0f172a;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-title h2 { font-size: 18px; font-weight: 800; color: #fff; }
        .badge-hub {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #000;
            font-weight: 800;
            font-size: 10px;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-btn {
            background: #1e293b;
            color: #cbd5e1;
            border: 1px solid var(--border-color);
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .nav-btn:hover { background: #334155; color: #fff; }
        .nav-btn-gold { background: linear-gradient(135deg, #f59e0b, #d97706); color: #000; border: none; }
        .nav-btn-gold:hover { opacity: 0.9; }

        .main-container { padding: 32px; flex: 1; }

        /* Alert Notification */
        .alert {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .alert-success { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
        .alert-error { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }

        /* Metrics Top Bar */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        .metric-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .metric-info h4 { font-size: 12px; color: var(--text-muted); font-weight: 600; }
        .metric-info .number { font-size: 24px; font-weight: 800; color: #fff; margin-top: 4px; }

        /* Navigation Tabs */
        .tab-menu {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }
        .tab-btn {
            background: transparent;
            color: var(--text-muted);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tab-btn.active { background: rgba(245, 158, 11, 0.15); color: var(--accent-gold); border: 1px solid rgba(245, 158, 11, 0.3); }
        .tab-btn:hover:not(.active) { background: #1e293b; color: #fff; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Card Container */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .card-title { font-size: 16px; font-weight: 800; display: flex; align-items: center; gap: 10px; }

        /* Table Design */
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 12px 16px; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border-color); }
        td { padding: 14px 16px; font-size: 13px; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            display: inline-block;
            margin: 2px;
        }
        .badge-gold { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.4); }
        .badge-blue { background: rgba(2, 132, 199, 0.2); color: #38bdf8; border: 1px solid rgba(2, 132, 199, 0.4); }
        .badge-green { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
        .badge-red { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }

        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            margin-right: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }
        .btn-edit { background: rgba(2, 132, 199, 0.2); color: #38bdf8; border: 1px solid rgba(2, 132, 199, 0.4); }
        .btn-edit:hover { background: #0284c7; color: #fff; }
        .btn-key { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.4); }
        .btn-key:hover { background: #f59e0b; color: #000; }
        .btn-toggle { background: rgba(139, 92, 246, 0.2); color: #c084fc; border: 1px solid rgba(139, 92, 246, 0.4); }
        .btn-toggle:hover { background: #8b5cf6; color: #fff; }
        .btn-delete { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }
        .btn-delete:hover { background: #ef4444; color: #fff; }

        /* Modal styling */
        .modal-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .modal-card {
            background: #111827;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 6px; }
        .form-control {
            width: 100%;
            background: #1f2937;
            border: 1px solid #374151;
            color: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
        }
        .form-control:focus { outline: none; border-color: var(--accent-gold); }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 8px;
            background: #1f2937;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #374151;
        }
        .checkbox-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #cbd5e1; cursor: pointer; }
        .checkbox-item input { accent-color: var(--accent-gold); width: 15px; height: 15px; cursor: pointer; }

        .section-header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 18px 0 10px 0;
            padding-bottom: 6px;
            border-bottom: 1px dashed #374151;
        }
        .section-header-box h4 { font-size: 13px; font-weight: 800; color: var(--accent-gold); }
        .btn-mini-link { font-size: 11px; color: #38bdf8; background: none; border: none; cursor: pointer; text-decoration: underline; margin-left: 8px; }
        .btn-mini-link:hover { color: #fff; }

        @media (max-width: 991px) {
            .top-nav {
                padding: 12px 14px;
                flex-direction: column;
                gap: 10px;
                align-items: stretch;
            }
            .brand-title {
                justify-content: space-between;
                width: 100%;
            }
            .brand-title h2 { font-size: 13px; }
            .nav-links {
                width: 100%;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
            }
            .nav-btn {
                padding: 6px 8px;
                font-size: 11px;
                justify-content: center;
                width: 100%;
            }
            .main-container {
                padding: 12px;
            }
            .metrics-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
                margin-bottom: 16px !important;
            }
            .metric-card {
                padding: 10px 12px !important;
                border-radius: 12px !important;
                gap: 10px !important;
            }
            .metric-icon {
                width: 36px !important;
                height: 36px !important;
                font-size: 14px !important;
                border-radius: 8px !important;
                flex-shrink: 0;
            }
            .metric-info h4 {
                font-size: 10px !important;
                line-height: 1.1 !important;
            }
            .metric-info .number {
                font-size: 18px !important;
                margin-top: 2px !important;
            }
            .tab-menu {
                display: flex !important;
                overflow-x: auto !important;
                white-space: nowrap !important;
                gap: 6px !important;
                padding-bottom: 8px !important;
                margin-bottom: 16px !important;
                -webkit-overflow-scrolling: touch;
            }
            .tab-btn {
                padding: 8px 12px !important;
                font-size: 11px !important;
                flex-shrink: 0 !important;
                border-radius: 8px !important;
            }
            .card {
                padding: 14px !important;
                border-radius: 12px !important;
            }
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .card-title { font-size: 13px !important; }
            .modal-card {
                width: 95% !important;
                padding: 14px !important;
                border-radius: 14px !important;
                max-height: 92vh !important;
            }
            .checkbox-group {
                grid-template-columns: 1fr !important;
                gap: 6px !important;
                padding: 10px !important;
            }
        }
    </style>
</head>
<body>

    <div class="top-nav">
        <div class="brand-title">
            <span class="badge-hub">Admin Master Hub</span>
            <h2>PT ENVIROTAMA SOLUSINDO</h2>
        </div>
        <div class="nav-links">
            <a href="/pengujian/dashboard" class="nav-btn"><i class="fa-solid fa-flask"></i> LIMS Pengujian</a>
            <a href="/kalibrasi/dashboard" class="nav-btn"><i class="fa-solid fa-scale-balanced"></i> LIMS Kalibrasi</a>
            <a href="/pengujian/sso/hris" class="nav-btn nav-btn-gold"><i class="fa-solid fa-users"></i> HRIS Enterprise</a>
            <form action="{{ route('pengujian.logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="nav-btn" style="background: rgba(239, 68, 68, 0.2); color: #f87171; border-color: rgba(239, 68, 68, 0.4);"><i class="fa-solid fa-power-off"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="main-container">

        @if(session('success'))
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
        @endif

        <!-- Metrics Top Bar -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b;"><i class="fa-solid fa-users-gear"></i></div>
                <div class="metric-info">
                    <h4>Total User Staff</h4>
                    <div class="number">{{ $totalUsers }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(59, 130, 246, 0.2); color: #3b82f6;"><i class="fa-solid fa-building-user"></i></div>
                <div class="metric-info">
                    <h4>Total Akun Klien</h4>
                    <div class="number">{{ $totalClients }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(16, 185, 129, 0.2); color: #10b981;"><i class="fa-solid fa-signal"></i></div>
                <div class="metric-info">
                    <h4>Sesi Aktif</h4>
                    <div class="number">{{ $activeSessions }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(239, 68, 68, 0.2); color: #ef4444;"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="metric-info">
                    <h4>IP Diblokir</h4>
                    <div class="number">{{ $blockedIps }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="tab-menu">
            <button class="tab-btn active" onclick="showTab('users')"><i class="fa-solid fa-users-gear"></i> 1. Kelola User Staff & Hak Akses</button>
            <button class="tab-btn" onclick="showTab('clients')"><i class="fa-solid fa-building-user"></i> 2. Kelola Klien Customer</button>
            <button class="tab-btn" onclick="showTab('other')"><i class="fa-solid fa-boxes-stacked"></i> 3. Manajemen Lainnya</button>
            <button class="tab-btn" onclick="showTab('security')"><i class="fa-solid fa-shield-cat"></i> 4. Security Monitor</button>
        </div>

        <!-- TAB 1: KELOLA USER -->
        <div id="tab-users" class="tab-content active">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-user-shield text-warning"></i> Kelola Hak Akses & Akun Staff Terintegrasi (Check & Uncheck Per Menu)</div>
                    <button class="nav-btn nav-btn-gold" onclick="openAddModal()"><i class="fa-solid fa-user-plus"></i> Tambah User Baru</button>
                </div>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama & Username</th>
                                <th>Email</th>
                                <th>Role Code / Jabatan</th>
                                <th>Modul Akses</th>
                                <th>Akses Harga</th>
                                <th>Status Akun</th>
                                <th>Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                            @php
                                $isActive = (bool)($u->is_active ?? $u->profile?->is_active ?? true);
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $u->name }}</strong><br>
                                    <small style="color: var(--text-muted);">{{ $u->username ?? str_replace('@eslab.com', '', $u->email) }}</small>
                                </td>
                                <td>{{ $u->email }}</td>
                                <td><span class="badge badge-gold">{{ strtoupper($u->role_code ?? $u->role) }}</span></td>
                                <td>
                                    @if($u->can_access_pengujian) <span class="badge badge-blue">Pengujian</span> @endif
                                    @if($u->can_access_kalibrasi) <span class="badge badge-green">Kalibrasi</span> @endif
                                    @if($u->can_access_hris) <span class="badge badge-gold">HRIS</span> @endif
                                </td>
                                <td>
                                    @if($u->can_view_harga)
                                        <span class="badge badge-green">Bisa Lihat Harga</span>
                                    @else
                                        <span class="badge badge-red">Harga Di-hide</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isActive)
                                        <span class="badge badge-green"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-red"><i class="fa-solid fa-circle-xmark"></i> Non-Aktif</span>
                                    @endif
                                </td>
                                <td style="white-space: nowrap;">
                                    <button class="btn-action btn-edit" onclick="openEditModal({{ json_encode($u) }})" title="Edit Hak Akses & Menu"><i class="fa-solid fa-sliders"></i> Edit Akses</button>
                                    
                                    <button class="btn-action btn-key" onclick="openResetModal({{ json_encode($u) }})" title="Reset Kata Sandi"><i class="fa-solid fa-key"></i> Reset Pass</button>

                                    @if($u->username !== 'admin_.master')
                                    @php
                                        $currentReqPath = request()->path();
                                        $baseAdminPrefix = preg_replace('#/dashboard$#i', '', $currentReqPath);
                                        $baseAdminUrl = url($baseAdminPrefix);
                                    @endphp
                                    <form action="{{ $baseAdminUrl }}/users/{{ $u->id }}/toggle-status" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-toggle" title="{{ $isActive ? 'Non-aktifkan Akun' : 'Aktifkan Akun' }}">
                                            <i class="fa-solid fa-power-off"></i> {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <form action="{{ $baseAdminUrl }}/users/{{ $u->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user {{ $u->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus User"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: KELOLA KLIEN -->
        <div id="tab-clients" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-building-user text-primary"></i> Kelola Akun Portal Klien Customer</div>
                    <a href="{{ route('kelola-klien.index') }}" class="nav-btn"><i class="fa-solid fa-external-link"></i> Buka Portal Kelola Klien Full</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID / Nama Perusahaan</th>
                            <th>Username / Email</th>
                            <th>No. Telepon</th>
                            <th>Status Akun</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $c)
                        <tr>
                            <td><strong>{{ $c->nama_perusahaan ?? $c->username }}</strong></td>
                            <td>{{ $c->email ?? $c->username }}</td>
                            <td>{{ $c->no_hp ?? '-' }}</td>
                            <td><span class="badge badge-green">Aktif</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: MANAJEMEN LAINNYA -->
        <div id="tab-other" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-boxes-stacked text-success"></i> Manajemen Terpusat (Master Data, Harga & Parameter)</div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    <div style="background: #0f172a; padding: 20px; border-radius: 14px; border: 1px solid var(--border-color);">
                        <h4>⚖️ Master Data Kalibrasi</h4>
                        <p style="font-size: 12px; color: var(--text-muted); margin: 8px 0;">Pengaturan Customer, Parameter KAN, Formulir Lembar Kerja, & Master Harga.</p>
                        <a href="{{ route('kalibrasi.master-data.index') }}" class="nav-btn nav-btn-gold" style="width: 100%; justify-content: center; margin-top: 10px;">Buka Master Data Kalibrasi</a>
                    </div>
                    <div style="background: #0f172a; padding: 20px; border-radius: 14px; border: 1px solid var(--border-color);">
                        <h4>🧪 Master Data Pengujian</h4>
                        <p style="font-size: 12px; color: var(--text-muted); margin: 8px 0;">Pengaturan Baku Mutu Parameter, Metode Pengujian, & Tarif Emisi/Air.</p>
                        <a href="{{ route('master-data.index') }}" class="nav-btn" style="width: 100%; justify-content: center; margin-top: 10px;">Buka Master Data Pengujian</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: SECURITY MONITOR -->
        <div id="tab-security" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-shield-cat text-danger"></i> Security & Audit Network Monitor</div>
                    <a href="{{ route('kalibrasi.network-monitor.index') }}" class="nav-btn" style="background:#ef4444; color:#fff;"><i class="fa-solid fa-shield-halved"></i> Buka Security Monitor Full</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Waktu Log</th>
                            <th>IP Address</th>
                            <th>Aktivitas</th>
                            <th>Status Security</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($securityLogs as $sLog)
                        <tr>
                            <td>{{ $sLog->created_at ?? now() }}</td>
                            <td><code>{{ $sLog->ip_address ?? '127.0.0.1' }}</code></td>
                            <td>{{ $sLog->action ?? $sLog->event ?? 'System Login' }}</td>
                            <td><span class="badge badge-green">NORMAL</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- RESET PASSWORD MODAL -->
    <div id="resetModal" class="modal-bg">
        <div class="modal-card" style="max-width: 450px;">
            <h3 style="margin-bottom: 12px; color: var(--accent-gold);"><i class="fa-solid fa-key"></i> Reset Kata Sandi User</h3>
            <p id="resetModalUserText" style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;"></p>
            <form id="resetForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>Kata Sandi Baru</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="reset_new_password" name="new_password" class="form-control" placeholder="Input atau klik acak" required>
                        <button type="button" class="nav-btn" onclick="generateRandomPass('reset_new_password')"><i class="fa-solid fa-wand-magic-sparkles"></i> Acak</button>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="nav-btn" onclick="closeResetModal()">Batal</button>
                    <button type="submit" class="nav-btn nav-btn-gold">Simpan Kata Sandi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ADD USER MODAL -->
    <div id="addModal" class="modal-bg">
        <div class="modal-card">
            <h3 style="margin-bottom: 16px; color: var(--accent-gold);"><i class="fa-solid fa-user-plus"></i> Tambah User Staff & Atur Hak Akses Menu</h3>
            <form action="{{ url(preg_replace('#/dashboard$#i', '', request()->path()) . '/users') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Rahmad Gunawan">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="rahmad.gunawan">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="rahmad.gunawan@eslab.com">
                    </div>
                    <div class="form-group">
                        <label>Kata Sandi Awal</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" id="add_password" name="password" class="form-control" required placeholder="Kata sandi">
                            <button type="button" class="nav-btn" onclick="generateRandomPass('add_password')">Acak</button>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Role Code / Preset Jabatan</label>
                        <select name="role_code" class="form-control" onchange="applyRolePreset('add', this.value)">
                            <optgroup label="👑 Manajemen & Penjaminan Mutu">
                                <option value="admin_master">Superadmin System Developer</option>
                                <option value="gm">General Manager & Product Specialist</option>
                                <option value="qms_staff">QMS Staff (Pengujian & Kalibrasi)</option>
                            </optgroup>
                            <optgroup label="💼 Shared Corporate Services (Pengujian & Kalibrasi)">
                                <option value="sales_marketing">Sales & Marketing Staff</option>
                                <option value="finance_staff">Finance Staff</option>
                                <option value="hrgs_specialist">HR & GS Specialist / Purchasing Staff</option>
                                <option value="lab_support">Lab Support Staff</option>
                                <option value="gs_staff">GS Staff (General Services)</option>
                            </optgroup>
                            <optgroup label="⚖️ Laboratorium Kalibrasi & Pengujian Mekanik">
                                <option value="manager_kalibrasi">Manager Lab Kalibrasi & Pengujian Mekanik</option>
                                <option value="admin_kalibrasi">Admin Lab Kalibrasi & Pengujian Mekanik</option>
                                <option value="teknisi_kalibrasi">Teknisi Kalibrasi / Pengujian Mekanik</option>
                            </optgroup>
                            <optgroup label="🧪 Laboratorium Pengujian Parameter Kualitas Lingkungan">
                                <option value="manager_lingkungan">Manager Lab Lingkungan</option>
                                <option value="penyelia_lingkungan">Penyelia Lab Lingkungan</option>
                                <option value="admin_lingkungan">Admin Lab Lingkungan</option>
                                <option value="analis_lingkungan">Analis Lab Lingkungan</option>
                                <option value="petugas_sampling">Petugas Sampling</option>
                            </optgroup>
                            <optgroup label="🎓 Program Magang & Praktik Kerja Lapangan (PKL)">
                                <option value="intern_pengujian">Siswa PKL / Mahasiswa Magang Lab Pengujian</option>
                                <option value="intern_kalibrasi">Siswa PKL / Mahasiswa Magang Lab Kalibrasi</option>
                                <option value="intern_hr">Siswa PKL / Mahasiswa Magang HR & Administrasi</option>
                                <option value="intern">Anak Magang / Siswa PKL (Umum)</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Akses Tingkat Tinggi</label>
                        <div style="display: flex; gap: 16px; padding-top: 8px;">
                            <label class="checkbox-item"><input type="checkbox" name="can_view_harga" checked> Akses Master Harga</label>
                            <label class="checkbox-item"><input type="checkbox" name="is_active" checked> Akun Langsung Aktif</label>
                        </div>
                    </div>
                </div>

                <!-- Modul Root Checkbox -->
                <div class="form-group">
                    <label>Akses Modul Utama</label>
                    <div class="checkbox-group">
                        <label class="checkbox-item"><input type="checkbox" name="can_access_pengujian" checked> LIMS Pengujian</label>
                        <label class="checkbox-item"><input type="checkbox" name="can_access_kalibrasi" checked> LIMS Kalibrasi</label>
                        <label class="checkbox-item"><input type="checkbox" name="can_access_hris" checked> HRIS Enterprise</label>
                    </div>
                </div>

                <!-- Detail Sub-Menu Checkboxes (Check & Uncheck Per Menu) -->
                <div class="section-header-box">
                    <h4>🧪 Menu LIMS Pengujian</h4>
                    <div>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('add_pengujian', true)">Centang Semua</button>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('add_pengujian', false)">Hapus Semua</button>
                    </div>
                </div>
                <div class="checkbox-group" id="add_pengujian_group">
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_dashboard" checked> Dashboard Pengujian</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_master_data" checked> Master Data Pengujian</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_kelola_klien" checked> Kelola Klien Customer</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_kelola_users" checked> Kelola Users Staff</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_peralatan" checked> Kelola Peralatan</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_coc" checked> COC Digital</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_komunikasi" checked> Hub Komunikasi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_sampling" checked> Monitoring Sampling</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_penerimaan" checked> Penerimaan Sampel</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_analisa" checked> Log Analisa</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_coa" checked> Verifikasi & COA</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_tren" checked> Tren Analisa</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_logger" checked> Activity Logger</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_network" checked> Network & Security Monitor</label>
                </div>

                <div class="section-header-box">
                    <h4>⚖️ Menu LIMS Kalibrasi</h4>
                    <div>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('add_kalibrasi', true)">Centang Semua</button>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('add_kalibrasi', false)">Hapus Semua</button>
                    </div>
                </div>
                <div class="checkbox-group" id="add_kalibrasi_group">
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_dashboard" checked> Dashboard Kalibrasi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_klien" checked> Manajemen Akun Customer</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_master_data" checked> Master Data Kalibrasi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_permintaan" checked> Permintaan Portal Klien</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_quotation" checked> Quotation (32 Kolom)</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_penerimaan" checked> Penerimaan & Pengembalian</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_jadwal" checked> Penerbitan Jadwal</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_coc" checked> Lembar Proses / COC</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_input_data" checked> Input Data & U95</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_evaluasi" checked> Evaluasi Manager</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_sertifikat" checked> Sertifikat Kalibrasi (COA)</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_monitoring" checked> Monitoring Order</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_invoice" checked> Pembuatan Invoice</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_pengiriman" checked> Pengiriman Dokumen & Resi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_finance_penagihan" checked> Finance Penagihan & Lunas</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_qms_finance" checked> QMS & Finance Approval</label>
                </div>

                <div class="section-header-box">
                    <h4>👥 Menu HRIS Enterprise</h4>
                    <div>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('add_hris', true)">Centang Semua</button>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('add_hris', false)">Hapus Semua</button>
                        <button type="button" class="btn-mini-link" style="color: #38bdf8; font-weight: 700;" onclick="applyHrisMinimalPreset('add')">⭐ Template Default</button>
                    </div>
                </div>
                                                                <div class="checkbox-group" id="add_hris_group">
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_presensi" checked> Presensi & Biometric Clock-In</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_kalender" checked> Kalender Kerja</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_linimasa" checked> Linimasa & Backup HR</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_cuti" checked> Cuti & Izin Sakit</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_overtime" checked> SPL & Overtime Lembur</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_quick_approval"> 📱 Quick Swipe Approval</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_kudos" checked> ⭐ Kudos & Rekognisi Tim</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_innovations" checked> 💡 Inovasi & Kaizen Lab</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_reports"> 📊 Laporan & Rekap Export HR</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_device_monitor"> 🖥️ Device Monitor & Sesi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_profil" checked> Profil & Keamanan Saya</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_users"> Data Seluruh Personil (Admin)</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_discipline"> 🏆 Kelola Poin Kedisiplinan</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_reimburse"> Klaim & Reimburse</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_payroll"> Vault E-Slip Gaji</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_evaluasi" checked> Evaluasi Kinerja (KPI)</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_medical" checked> Medical Checkup</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_announcements" checked> Pengumuman Internal</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_tickets" checked> Helpdesk & HR Ticket</label>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px;">
                    <button type="button" class="nav-btn" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="nav-btn nav-btn-gold"><i class="fa-solid fa-floppy-disk"></i> Simpan User & Hak Akses</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT USER MODAL -->
    <div id="editModal" class="modal-bg">
        <div class="modal-card">
            <h3 style="margin-bottom: 16px; color: var(--accent-gold);"><i class="fa-solid fa-sliders"></i> Edit Detail Hak Akses Menu Staff</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kata Sandi Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label>Role Code / Preset Jabatan</label>
                        <select id="edit_role_code" name="role_code" class="form-control" onchange="applyRolePreset('edit', this.value)">
                            <optgroup label="👑 Manajemen & Penjaminan Mutu">
                                <option value="admin_master">Superadmin System Developer</option>
                                <option value="gm">General Manager & Product Specialist</option>
                                <option value="qms_staff">QMS Staff (Pengujian & Kalibrasi)</option>
                            </optgroup>
                            <optgroup label="💼 Shared Corporate Services (Pengujian & Kalibrasi)">
                                <option value="sales_marketing">Sales & Marketing Staff</option>
                                <option value="finance_staff">Finance Staff</option>
                                <option value="hrgs_specialist">HR & GS Specialist / Purchasing Staff</option>
                                <option value="lab_support">Lab Support Staff</option>
                                <option value="gs_staff">GS Staff (General Services)</option>
                            </optgroup>
                            <optgroup label="⚖️ Laboratorium Kalibrasi & Pengujian Mekanik">
                                <option value="manager_kalibrasi">Manager Lab Kalibrasi & Pengujian Mekanik</option>
                                <option value="admin_kalibrasi">Admin Lab Kalibrasi & Pengujian Mekanik</option>
                                <option value="teknisi_kalibrasi">Teknisi Kalibrasi / Pengujian Mekanik</option>
                            </optgroup>
                            <optgroup label="🧪 Laboratorium Pengujian Parameter Kualitas Lingkungan">
                                <option value="manager_lingkungan">Manager Lab Lingkungan</option>
                                <option value="penyelia_lingkungan">Penyelia Lab Lingkungan</option>
                                <option value="admin_lingkungan">Admin Lab Lingkungan</option>
                                <option value="analis_lingkungan">Analis Lab Lingkungan</option>
                                <option value="petugas_sampling">Petugas Sampling</option>
                            </optgroup>
                            <optgroup label="🎓 Program Magang & Praktik Kerja Lapangan (PKL)">
                                <option value="intern_pengujian">Siswa PKL / Mahasiswa Magang Lab Pengujian</option>
                                <option value="intern_kalibrasi">Siswa PKL / Mahasiswa Magang Lab Kalibrasi</option>
                                <option value="intern_hr">Siswa PKL / Mahasiswa Magang HR & Administrasi</option>
                                <option value="intern">Anak Magang / Siswa PKL (Umum)</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Akses Tingkat Tinggi</label>
                        <div style="display: flex; gap: 16px; padding-top: 8px;">
                            <label class="checkbox-item"><input type="checkbox" id="edit_can_harga" name="can_view_harga"> Akses Master Harga</label>
                            <label class="checkbox-item"><input type="checkbox" id="edit_is_active" name="is_active"> Status Akun Aktif</label>
                        </div>
                    </div>
                </div>

                <!-- Modul Root Checkbox -->
                <div class="form-group">
                    <label>Akses Modul Utama</label>
                    <div class="checkbox-group">
                        <label class="checkbox-item"><input type="checkbox" id="edit_can_pengujian" name="can_access_pengujian"> LIMS Pengujian</label>
                        <label class="checkbox-item"><input type="checkbox" id="edit_can_kalibrasi" name="can_access_kalibrasi"> LIMS Kalibrasi</label>
                        <label class="checkbox-item"><input type="checkbox" id="edit_can_hris" name="can_access_hris"> HRIS Enterprise</label>
                    </div>
                </div>

                <!-- Detail Sub-Menu Checkboxes -->
                <div class="section-header-box">
                    <h4>🧪 Menu LIMS Pengujian</h4>
                    <div>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('edit_pengujian', true)">Centang Semua</button>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('edit_pengujian', false)">Hapus Semua</button>
                    </div>
                </div>
                <div class="checkbox-group" id="edit_pengujian_group">
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_dashboard" checked> Dashboard Pengujian</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_master_data" checked> Master Data Pengujian</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_kelola_klien" checked> Kelola Klien Customer</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_kelola_users" checked> Kelola Users Staff</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_peralatan" checked> Kelola Peralatan</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_coc" checked> COC Digital</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_komunikasi" checked> Hub Komunikasi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_sampling" checked> Monitoring Sampling</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_penerimaan" checked> Penerimaan Sampel</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_analisa" checked> Log Analisa</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_coa" checked> Verifikasi & COA</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_tren" checked> Tren Analisa</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_logger" checked> Activity Logger</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="pengujian_network" checked> Network & Security Monitor</label>
                </div>

                <div class="section-header-box">
                    <h4>⚖️ Menu LIMS Kalibrasi</h4>
                    <div>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('edit_kalibrasi', true)">Centang Semua</button>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('edit_kalibrasi', false)">Hapus Semua</button>
                    </div>
                </div>
                <div class="checkbox-group" id="edit_kalibrasi_group">
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_dashboard" checked> Dashboard Kalibrasi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_klien" checked> Manajemen Akun Customer</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_master_data" checked> Master Data Kalibrasi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_permintaan" checked> Permintaan Portal Klien</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_quotation" checked> Quotation (32 Kolom)</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_penerimaan" checked> Penerimaan & Pengembalian</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_jadwal" checked> Penerbitan Jadwal</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_coc" checked> Lembar Proses / COC</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_input_data" checked> Input Data & U95</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_evaluasi" checked> Evaluasi Manager</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_sertifikat" checked> Sertifikat Kalibrasi (COA)</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_monitoring" checked> Monitoring Order</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_invoice" checked> Pembuatan Invoice</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_pengiriman" checked> Pengiriman Dokumen & Resi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_finance_penagihan" checked> Finance Penagihan & Lunas</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="kalibrasi_qms_finance" checked> QMS & Finance Approval</label>
                </div>

                <div class="section-header-box">
                    <h4>👥 Menu HRIS Enterprise</h4>
                    <div>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('edit_hris', true)">Centang Semua</button>
                        <button type="button" class="btn-mini-link" onclick="toggleSection('edit_hris', false)">Hapus Semua</button>
                        <button type="button" class="btn-mini-link" style="color: #38bdf8; font-weight: 700;" onclick="applyHrisMinimalPreset('edit')">⭐ Template Default</button>
                    </div>
                </div>
                                                                <div class="checkbox-group" id="edit_hris_group">
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_presensi" checked> Presensi & Biometric Clock-In</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_kalender" checked> Kalender Kerja</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_linimasa" checked> Linimasa & Backup HR</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_cuti" checked> Cuti & Izin Sakit</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_overtime" checked> SPL & Overtime Lembur</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_quick_approval"> 📱 Quick Swipe Approval</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_kudos" checked> ⭐ Kudos & Rekognisi Tim</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_innovations" checked> 💡 Inovasi & Kaizen Lab</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_reports"> 📊 Laporan & Rekap Export HR</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_device_monitor"> 🖥️ Device Monitor & Sesi</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_profil" checked> Profil & Keamanan Saya</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_users"> Data Seluruh Personil (Admin)</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_discipline"> 🏆 Kelola Poin Kedisiplinan</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_reimburse"> Klaim & Reimburse</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_payroll"> Vault E-Slip Gaji</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_evaluasi" checked> Evaluasi Kinerja (KPI)</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_medical" checked> Medical Checkup</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_announcements" checked> Pengumuman Internal</label>
                    <label class="checkbox-item"><input type="checkbox" name="allowed_menus[]" value="hris_tickets" checked> Helpdesk & HR Ticket</label>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px;">
                    <button type="button" class="nav-btn" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="nav-btn nav-btn-gold"><i class="fa-solid fa-rotate"></i> Update Hak Akses User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            event.currentTarget.classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        function openAddModal() { document.getElementById('addModal').style.display = 'flex'; }
        function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

        function openResetModal(user) {
            const baseAdminUrl = window.location.pathname.replace(/\/dashboard$/i, '');
            document.getElementById('resetForm').action = baseAdminUrl + '/users/' + user.id + '/reset-password';
            document.getElementById('resetModalUserText').innerHTML = 'Reset kata sandi untuk staf: <b>' + user.name + '</b> (' + user.email + ')';
            document.getElementById('reset_new_password').value = '';
            document.getElementById('resetModal').style.display = 'flex';
        }
        function closeResetModal() { document.getElementById('resetModal').style.display = 'none'; }

        function generateRandomPass(inputId) {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#';
            let pass = '';
            for (let i = 0; i < 8; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById(inputId).value = pass;
        }

        function toggleSection(prefix, checkAll) {
            const container = document.getElementById(prefix + '_group');
            if (container) {
                container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = checkAll);
            }
        }

        const defaultMinimalHris = [
            'hris_presensi', 'hris_kalender', 'hris_linimasa', 'hris_cuti', 'hris_overtime',
            'hris_kudos', 'hris_innovations', 'hris_profil', 'hris_evaluasi', 'hris_medical',
            'hris_announcements', 'hris_tickets'
        ];

        function applyHrisMinimalPreset(mode) {
            const container = document.getElementById(mode + '_hris_group');
            if (!container) return;
            container.querySelectorAll('input[name="allowed_menus[]"]').forEach(cb => {
                cb.checked = defaultMinimalHris.includes(cb.value);
            });
        }

        function openEditModal(user) {
            const baseAdminUrl = window.location.pathname.replace(/\/dashboard$/i, '');
            document.getElementById('editForm').action = baseAdminUrl + '/users/' + user.id;
            document.getElementById('edit_name').value = user.name;
            const roleSelect = document.getElementById('edit_role_code');
            const targetRole = user.role_code || user.role || 'staff';
            const exists = Array.from(roleSelect.options).some(opt => opt.value === targetRole);
            if (!exists && targetRole) {
                const opt = new Option(targetRole.toUpperCase(), targetRole, true, true);
                roleSelect.add(opt);
            }
            roleSelect.value = targetRole;
            document.getElementById('edit_can_pengujian').checked = !!user.can_access_pengujian;
            document.getElementById('edit_can_kalibrasi').checked = !!user.can_access_kalibrasi;
            document.getElementById('edit_can_hris').checked = !!user.can_access_hris;
            document.getElementById('edit_can_harga').checked = !!user.can_view_harga;
            document.getElementById('edit_is_active').checked = (user.is_active !== undefined) ? !!user.is_active : (user.profile ? !!user.profile.is_active : true);

            // Populate checked menus
            let allowedMenus = [];
            const rawMenus = user.allowed_menus_json || (user.profile ? user.profile.allowed_menus_json : null);
            if (rawMenus) {
                try { allowedMenus = JSON.parse(rawMenus); } catch(e) {}
            }

            const editModalEl = document.getElementById('editModal');
            editModalEl.querySelectorAll('input[name="allowed_menus[]"]').forEach(cb => {
                const val = cb.value;
                if (user.role_code === 'admin_master' || user.role === 'admin_master') {
                    cb.checked = true;
                } else if (allowedMenus.length > 0) {
                    // If it's a standard default HRIS menu that might be newly introduced, include it by default
                    if (defaultMinimalHris.includes(val)) {
                        cb.checked = true;
                    } else {
                        cb.checked = allowedMenus.includes(val);
                    }
                } else {
                    cb.checked = cb.defaultChecked;
                }
            });

            document.getElementById('editModal').style.display = 'flex';
        }
        function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

                function applyRolePreset(mode, roleCode) {
            const container = document.getElementById(mode + 'Modal');
            if (!container || !roleCode) return;

            const defaultHrisMenus = [
                'hris_presensi', 'hris_kalender', 'hris_cuti', 'hris_overtime',
                'hris_kudos', 'hris_innovations', 'hris_profil', 'hris_medical',
                'hris_announcements', 'hris_tickets'
            ];

            const supervisorHrisMenus = [
                ...defaultHrisMenus,
                'hris_linimasa', 'hris_quick_approval', 'hris_discipline', 'hris_evaluasi', 'hris_reports'
            ];

            const adminHrisMenus = [
                ...supervisorHrisMenus,
                'hris_users', 'hris_reimburse', 'hris_payroll', 'hris_device_monitor'
            ];

            const presets = {
                admin_master: ['all'],
                admin: ['all'],
                manager_kalibrasi: [
                    'kalibrasi_dashboard', 'kalibrasi_master_data', 'kalibrasi_penerimaan', 'kalibrasi_jadwal',
                    'kalibrasi_coc', 'kalibrasi_input_data', 'kalibrasi_evaluasi', 'kalibrasi_sertifikat',
                    'kalibrasi_monitoring', 'kalibrasi_qms_finance',
                    'pengujian_dashboard',
                    ...supervisorHrisMenus
                ],
                admin_kalibrasi: [
                    'kalibrasi_dashboard', 'kalibrasi_klien', 'kalibrasi_penerimaan', 'kalibrasi_jadwal',
                    'kalibrasi_coc', 'kalibrasi_sertifikat', 'kalibrasi_monitoring', 'kalibrasi_pengiriman',
                    'pengujian_dashboard',
                    ...defaultHrisMenus
                ],
                lab_support: [
                    'kalibrasi_dashboard', 'kalibrasi_penerimaan', 'kalibrasi_jadwal', 'kalibrasi_coc',
                    'kalibrasi_monitoring',
                    ...defaultHrisMenus
                ],
                teknisi_kalibrasi: [
                    'kalibrasi_dashboard', 'kalibrasi_coc', 'kalibrasi_input_data', 'kalibrasi_sertifikat',
                    'kalibrasi_monitoring',
                    ...defaultHrisMenus
                ],
                sales_marketing: [
                    'kalibrasi_dashboard', 'kalibrasi_klien', 'kalibrasi_permintaan', 'kalibrasi_quotation',
                    'pengujian_dashboard', 'pengujian_kelola_klien', 'pengujian_coc', 'pengujian_komunikasi',
                    ...defaultHrisMenus
                ],
                finance_staff: [
                    'kalibrasi_dashboard', 'kalibrasi_quotation', 'kalibrasi_invoice', 'kalibrasi_finance_penagihan',
                    'pengujian_dashboard', 'pengujian_kelola_klien',
                    'hris_payroll', 'hris_reimburse',
                    ...defaultHrisMenus
                ],
                qms_staff: [
                    'kalibrasi_dashboard', 'kalibrasi_master_data', 'kalibrasi_sertifikat', 'kalibrasi_jadwal', 'kalibrasi_qms_finance',
                    'pengujian_dashboard', 'pengujian_master_data', 'pengujian_coa',
                    ...defaultHrisMenus
                ],
                manager_lingkungan: [
                    'pengujian_dashboard', 'pengujian_master_data', 'pengujian_kelola_klien', 'pengujian_peralatan',
                    'pengujian_coc', 'pengujian_komunikasi', 'pengujian_sampling', 'pengujian_penerimaan',
                    'pengujian_analisa', 'pengujian_coa', 'pengujian_tren', 'pengujian_logger', 'pengujian_network',
                    ...supervisorHrisMenus
                ],
                penyelia_lingkungan: [
                    'pengujian_dashboard', 'pengujian_master_data', 'pengujian_kelola_klien', 'pengujian_peralatan',
                    'pengujian_coc', 'pengujian_komunikasi', 'pengujian_sampling', 'pengujian_penerimaan',
                    'pengujian_analisa', 'pengujian_coa', 'pengujian_tren',
                    ...supervisorHrisMenus
                ],
                analis_lingkungan: [
                    'pengujian_dashboard', 'pengujian_peralatan', 'pengujian_coc', 'pengujian_komunikasi',
                    'pengujian_analisa', 'pengujian_coa',
                    ...defaultHrisMenus
                ],
                petugas_sampling: [
                    'pengujian_dashboard', 'pengujian_peralatan', 'pengujian_coc', 'pengujian_komunikasi',
                    'pengujian_sampling',
                    ...defaultHrisMenus
                ],
                hrgs_specialist: [
                    'all_hris', 'pengujian_dashboard', 'kalibrasi_dashboard',
                    ...adminHrisMenus
                ],
                intern: [
                    ...defaultHrisMenus
                ],
                intern_pengujian: [
                    ...defaultHrisMenus
                ],
                intern_kalibrasi: [
                    ...defaultHrisMenus
                ],
                intern_hr: [
                    ...defaultHrisMenus
                ],
                staff: [
                    'pengujian_dashboard', 'pengujian_komunikasi',
                    ...defaultHrisMenus
                ]
            };

            const cbPengujian = document.getElementById(mode + '_can_access_pengujian');
            const cbKalibrasi = document.getElementById(mode + '_can_access_kalibrasi');
            const cbHris = document.getElementById(mode + '_can_access_hris');
            const cbHarga = document.getElementById(mode + '_can_view_harga');

            if (roleCode === 'intern' || roleCode === 'intern_pengujian' || roleCode === 'intern_kalibrasi' || roleCode === 'intern_hr') {
                if (cbPengujian) cbPengujian.checked = false;
                if (cbKalibrasi) cbKalibrasi.checked = false;
                if (cbHris) cbHris.checked = true;
                if (cbHarga) cbHarga.checked = false;
            } else if (roleCode.includes('kalibrasi') || roleCode === 'lab_support') {
                if (cbPengujian) cbPengujian.checked = false;
                if (cbKalibrasi) cbKalibrasi.checked = true;
                if (cbHris) cbHris.checked = true;
                if (cbHarga) cbHarga.checked = (roleCode === 'manager_kalibrasi');
            } else if (roleCode.includes('lingkungan') || roleCode === 'petugas_sampling') {
                if (cbPengujian) cbPengujian.checked = true;
                if (cbKalibrasi) cbKalibrasi.checked = false;
                if (cbHris) cbHris.checked = true;
                if (cbHarga) cbHarga.checked = (roleCode === 'manager_lingkungan');
            } else if (roleCode === 'sales_marketing' || roleCode === 'finance_staff') {
                if (cbPengujian) cbPengujian.checked = true;
                if (cbKalibrasi) cbKalibrasi.checked = true;
                if (cbHris) cbHris.checked = true;
                if (cbHarga) cbHarga.checked = true;
            } else if (roleCode === 'qms_staff' || roleCode === 'hrgs_specialist') {
                if (cbPengujian) cbPengujian.checked = true;
                if (cbKalibrasi) cbKalibrasi.checked = true;
                if (cbHris) cbHris.checked = true;
                if (cbHarga) cbHarga.checked = false;
            } else if (roleCode === 'admin_master' || roleCode === 'admin') {
                if (cbPengujian) cbPengujian.checked = true;
                if (cbKalibrasi) cbKalibrasi.checked = true;
                if (cbHris) cbHris.checked = true;
                if (cbHarga) cbHarga.checked = true;
            }

            const selected = presets[roleCode];
            if (selected) {
                container.querySelectorAll('input[name="allowed_menus[]"]').forEach(cb => {
                    if (selected.includes('all') || selected.includes(cb.value)) {
                        cb.checked = true;
                    } else if (selected.includes('all_hris') && cb.value.startsWith('hris_')) {
                        cb.checked = true;
                    } else {
                        cb.checked = false;
                    }
                });
            }
        }
    </script>
</body>
</html>
