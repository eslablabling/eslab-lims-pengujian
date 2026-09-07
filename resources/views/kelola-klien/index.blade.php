@php
    $isKalibrasi = request()->is('*kalibrasi*') || (session()->has('kalibrasi_user') && !request()->is('*pengujian*'));
    $layoutToUse = $isKalibrasi ? 'kalibrasi.layouts.kalibrasi' : 'layouts.app';
    $basePath = $isKalibrasi ? '/kalibrasi/kelola-klien' : '/pengujian/kelola-klien';
@endphp
@extends($layoutToUse)

@section('title', 'Master Akun Customer Terpadu')

@section('content')
<style>
    .klien-master-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
    }
    .klien-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .klien-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .klien-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07);
    }
    .klien-stat-card h4 {
        margin: 0 0 6px 0;
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .klien-stat-card .number {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .klien-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }
    .klien-table {
        width: 100%;
        border-collapse: collapse;
    }
    .klien-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }
    .klien-table td {
        padding: 14px 16px;
        font-size: 0.84rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .klien-table tr:hover {
        background: #f8fafc;
    }
    .klien-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .badge-both { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .badge-pengujian { background: #eff6ff; color: #0284c7; border: 1px solid #bae6fd; }
    .badge-kalibrasi { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
</style>

<div>
    <!-- Cross-App Quick Navigator Header -->
    <div class="klien-master-header">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <span style="font-size: 1.5rem;">🌐</span>
                <h2 style="font-size: 1.35rem; font-weight: 800; color: white; margin: 0;">Master Akun Customer Terpadu</h2>
                <span style="background: #38bdf8; color: #0f172a; font-size: 0.65rem; font-weight: 800; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Central Hub</span>
            </div>
            <p style="font-size: 0.82rem; color: #94a3b8; margin: 0;">Manajemen terpusat akun portal pelanggan untuk Pengujian Lingkungan & Kalibrasi Alat.</p>
        </div>

        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <a href="/pengujian/dashboard" style="background: rgba(2, 132, 199, 0.25); border: 1px solid #38bdf8; color: #7dd3fc; padding: 8px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <span>🧪</span> LIMS Pengujian
            </a>
            <a href="/kalibrasi/dashboard" style="background: rgba(16, 185, 129, 0.25); border: 1px solid #34d399; color: #a7f3d0; padding: 8px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <span>⚖️</span> LIMS Kalibrasi
            </a>
            <button onclick="document.getElementById('modalTambahKlien').style.display='flex'" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none; color: white; padding: 8px 16px; border-radius: 10px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);">
                <span>➕</span> Buat Akun Customer
            </button>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 1.1rem;">✓</span> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 1.1rem;">⚠️</span> {{ session('error') }}
    </div>
    @endif

    <!-- Stats Summary Cards -->
    <div class="klien-stats-grid">
        <div class="klien-stat-card" style="cursor: pointer;" onclick="window.location.href='{{ $basePath }}'">
            <h4>Total Akun Customer</h4>
            <div class="number">{{ number_format($totalClients) }}</div>
            <span style="font-size: 0.72rem; color: #64748b;">Semua perusahaan terdaftar</span>
        </div>
        <div class="klien-stat-card" style="cursor: pointer;" onclick="window.location.href='{{ $basePath }}?filter=pengujian'">
            <h4>🧪 Pengujian Saja</h4>
            <div class="number" style="color: #0284c7;">{{ number_format($totalPengujian) }}</div>
            <span style="font-size: 0.72rem; color: #0284c7;">Akses portal pengujian</span>
        </div>
        <div class="klien-stat-card" style="cursor: pointer;" onclick="window.location.href='{{ $basePath }}?filter=kalibrasi'">
            <h4>⚖️ Kalibrasi Saja</h4>
            <div class="number" style="color: #d97706;">{{ number_format($totalKalibrasi) }}</div>
            <span style="font-size: 0.72rem; color: #d97706;">Akses portal kalibrasi</span>
        </div>
        <div class="klien-stat-card" style="cursor: pointer;" onclick="window.location.href='{{ $basePath }}?filter=both'">
            <h4>🌐 Terintegrasi Keduanya</h4>
            <div class="number" style="color: #16a34a;">{{ number_format($totalBoth) }}</div>
            <span style="font-size: 0.72rem; color: #16a34a;">Akses kedua layanan</span>
        </div>
    </div>

    <div class="klien-container">
        <!-- Filter Tabs & Search Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="{{ $basePath }}?{{ http_build_query(array_merge(request()->except('filter', 'page'), [])) }}" style="padding: 8px 14px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $filter === 'all' ? 'background: #0f172a; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    Semua ({{ $totalClients }})
                </a>
                <a href="{{ $basePath }}?{{ http_build_query(array_merge(request()->except('page'), ['filter' => 'pengujian'])) }}" style="padding: 8px 14px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $filter === 'pengujian' ? 'background: #0284c7; color: white;' : 'background: #eff6ff; color: #0284c7;' }}">
                    🧪 Pengujian ({{ $totalPengujian }})
                </a>
                <a href="{{ $basePath }}?{{ http_build_query(array_merge(request()->except('page'), ['filter' => 'kalibrasi'])) }}" style="padding: 8px 14px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $filter === 'kalibrasi' ? 'background: #d97706; color: white;' : 'background: #fffbeb; color: #d97706;' }}">
                    ⚖️ Kalibrasi ({{ $totalKalibrasi }})
                </a>
                <a href="{{ $basePath }}?{{ http_build_query(array_merge(request()->except('page'), ['filter' => 'both'])) }}" style="padding: 8px 14px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $filter === 'both' ? 'background: #16a34a; color: white;' : 'background: #ecfdf5; color: #16a34a;' }}">
                    🌐 Keduanya / Terpadu ({{ $totalBoth }})
                </a>
            </div>

            <form method="GET" action="{{ $basePath }}" style="display: flex; gap: 8px;">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama perusahaan, username..." style="padding: 9px 14px; border: 1px solid #cbd5e1; border-radius: 10px; width: 260px; font-size: 0.82rem; outline: none; background: #ffffff;">
                <button type="submit" style="padding: 9px 16px; background: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                    🔍 Cari
                </button>
                @if($search)
                <a href="{{ $basePath }}?filter={{ $filter }}" style="padding: 9px 12px; background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.82rem; text-decoration: none;">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- Data Table -->
        <div style="overflow-x: auto; width: 100%;">
            <table class="klien-table">
                <thead>
                    <tr>
                        <th>Nama Perusahaan</th>
                        <th>Username Portal</th>
                        <th>Password Klien</th>
                        <th>Akses Layanan</th>
                        <th>Riwayat Order Terpadu</th>
                        <th>Status Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($klien as $k)
                    <tr>
                        <td>
                            <strong style="color: #0f172a; font-size: 0.9rem;">{{ $k->company_name }}</strong>
                            <div style="font-size: 0.7rem; color: #94a3b8;">ID: #{{ $k->id }} &bull; Dibuat: {{ $k->created_at ? $k->created_at->translatedFormat('d M Y') : '-' }}</div>
                        </td>
                        <td>
                            <span style="font-family: monospace; font-weight: 700; color: #0284c7; background: #f0f9ff; padding: 3px 8px; border-radius: 6px; border: 1px solid #bae6fd;">
                                {{ $k->username }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span id="pwd_{{ $k->id }}" style="font-family: monospace; font-size: 0.8rem; color: #475569; background: #f8fafc; padding: 2px 6px; border-radius: 4px; border: 1px dashed #cbd5e1;">
                                    ••••••••
                                </span>
                                <button type="button" onclick="togglePasswordVisibility({{ $k->id }}, '{{ $k->password }}')" style="background: none; border: none; cursor: pointer; font-size: 0.8rem; color: #64748b;" title="Lihat/Sembunyikan Password">
                                    👁️
                                </button>
                            </div>
                        </td>
                        <td>
                            @if($k->default_module === 'both')
                                <span class="klien-badge badge-both">🌐 Pengujian & Kalibrasi</span>
                            @elseif($k->default_module === 'pengujian')
                                <span class="klien-badge badge-pengujian">🧪 Pengujian Lingkungan</span>
                            @else
                                <span class="klien-badge badge-kalibrasi">⚖️ Kalibrasi Alat</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                <span style="font-size: 0.72rem; background: #eff6ff; color: #0284c7; padding: 2px 6px; border-radius: 4px; font-weight: 700; border: 1px solid #bae6fd;">
                                    🧪 {{ $k->total_order_pengujian }} Pengujian
                                </span>
                                <span style="font-size: 0.72rem; background: #fffbeb; color: #d97706; padding: 2px 6px; border-radius: 4px; font-weight: 700; border: 1px solid #fde68a;">
                                    ⚖️ {{ $k->total_order_kalibrasi }} Kalibrasi
                                </span>
                            </div>
                        </td>
                        <td>
                            <form action="{{ $basePath }}/{{ $k->id }}/toggle-status" method="POST" style="display: inline;">
                                @csrf
                                @if($k->is_active)
                                    <button type="submit" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                        ✓ Aktif
                                    </button>
                                @else
                                    <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                        ✕ Nonaktif
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <button type="button" onclick="openEditModal({{ $k->id }}, '{{ addslashes($k->company_name) }}', '{{ $k->username }}', '{{ $k->default_module }}')" style="padding: 6px 10px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;" title="Edit Akun">
                                    ✏️ Edit
                                </button>

                                <form action="{{ $basePath }}/{{ $k->id }}/reset-password" method="POST" style="display: inline;" onsubmit="return confirm('Reset password akun {{ $k->company_name }}?')">
                                    @csrf
                                    <button type="submit" style="padding: 6px 10px; background: #fffbeb; border: 1px solid #fde68a; color: #d97706; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;" title="Generate Password Baru">
                                        🔑 Reset
                                    </button>
                                </form>

                                <form action="{{ $basePath }}/{{ $k->id }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus akun customer {{ $k->company_name }} secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 8px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;" title="Hapus Akun">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <span style="font-size: 2rem; display: block; margin-bottom: 8px;">🏢</span>
                            Tidak ada data akun customer yang sesuai dengan filter atau pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $klien->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Klien Baru -->
<div id="modalTambahKlien" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 520px; padding: 30px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">➕ Buat Akun Customer Baru</h3>
            <button type="button" onclick="document.getElementById('modalTambahKlien').style.display='none'" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #64748b;">✕</button>
        </div>
        <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 20px;">Kredensial ini akan digunakan pelanggan untuk login ke Portal Klien ESLAB.</p>

        <form action="{{ $basePath }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Nama Perusahaan / Klien *</label>
                <input type="text" name="company_name" id="inputNewCompany" required placeholder="PT Sumber Berkah Mandiri" oninput="autoGenerateUsername(this.value)" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; box-sizing: border-box;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Username Portal *</label>
                    <input type="text" name="username" id="inputNewUsername" required placeholder="klien_sumberberkah" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; font-family: monospace; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Password *</label>
                    <input type="text" name="password" id="inputNewPassword" value="{{ \App\Models\ClientAccount::generatePassword() }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; font-family: monospace; box-sizing: border-box;">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Akses Layanan Terintegrasi *</label>
                <select name="default_module" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; font-weight: 600; box-sizing: border-box;">
                    <option value="both" selected>🌐 Kedua Layanan (Pengujian Lingkungan & Kalibrasi Alat)</option>
                    <option value="pengujian">🧪 Pengujian Lingkungan Saja</option>
                    <option value="kalibrasi">⚖️ Kalibrasi Alat Saja</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('modalTambahKlien').style.display='none'" style="padding: 10px 18px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 20px; background: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Simpan Akun Customer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Klien -->
<div id="modalEditKlien" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 520px; padding: 30px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">✏️ Edit Akun Customer</h3>
            <button type="button" onclick="document.getElementById('modalEditKlien').style.display='none'" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #64748b;">✕</button>
        </div>

        <form id="formEditKlien" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Nama Perusahaan *</label>
                <input type="text" name="company_name" id="editCompany" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; box-sizing: border-box;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Username Portal *</label>
                    <input type="text" name="username" id="editUsername" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; font-family: monospace; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Password Baru (Opsional)</label>
                    <input type="text" name="password" id="editPassword" placeholder="Kosongkan jika tidak ubah" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; box-sizing: border-box;">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Akses Layanan Terintegrasi *</label>
                <select name="default_module" id="editModule" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; font-weight: 600; box-sizing: border-box;">
                    <option value="both">🌐 Kedua Layanan (Pengujian & Kalibrasi)</option>
                    <option value="pengujian">🧪 Pengujian Lingkungan Saja</option>
                    <option value="kalibrasi">⚖️ Kalibrasi Alat Saja</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('modalEditKlien').style.display='none'" style="padding: 10px 18px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 20px; background: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(id, rawPassword) {
        const span = document.getElementById('pwd_' + id);
        if (span.innerText === '••••••••') {
            span.innerText = rawPassword;
            span.style.color = '#0284c7';
            span.style.fontWeight = 'bold';
        } else {
            span.innerText = '••••••••';
            span.style.color = '#475569';
            span.style.fontWeight = 'normal';
        }
    }

    function autoGenerateUsername(companyName) {
        if (!companyName) return;
        const clean = companyName.toLowerCase()
            .replace(/pt\.?|cv\.?|tbk\.?|inc\.?|ltd\.?/gi, '')
            .replace(/[^a-z0-9]/gi, '')
            .trim();
        document.getElementById('inputNewUsername').value = 'klien_' + clean.substring(0, 18);
    }

    function openEditModal(id, company, username, module) {
        const basePath = '{{ $basePath }}';
        document.getElementById('editCompany').value = company;
        document.getElementById('editUsername').value = username;
        document.getElementById('editModule').value = module;
        document.getElementById('editPassword').value = '';
        document.getElementById('formEditKlien').action = basePath + '/' + id;
        document.getElementById('modalEditKlien').style.display = 'flex';
    }
</script>
@endpush
