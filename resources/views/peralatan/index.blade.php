@extends('layouts.app')

@section('title', 'Manajemen Peralatan & Kalibrasi Lab')

@section('content')
<style>
    .equip-box {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .stats-grid-eq {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card-eq {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 18px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .stat-card-eq:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04);
    }
    .btn-action-sm {
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
    }
    /* Modal */
    .custom-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(4px);
    }
    .custom-modal-content {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 700px;
        padding: 30px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-height: 90vh;
        overflow-y: auto;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        .sidebar, .top-bar, .mobile-backdrop, .no-print, button, .stats-grid-eq, .action-bar-eq {
            display: none !important;
        }
        body, .content-body, .data-container {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .equip-box {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .print-header-es {
            display: block !important;
        }
    }
    .print-header-es {
        display: none;
        margin-bottom: 20px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }
</style>

<div class="print-header-es">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo ESLab" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" style="max-height: 45px; width: auto; object-fit: contain;">
            <div>
                <h4 style="margin: 0; font-size: 1rem; font-weight: 800; text-transform: uppercase;">PT ENVIROTAMA SOLUSINDO</h4>
                <div style="font-size: 0.75rem; color: #475569;">DAFTAR INVENTARIS PERALATAN LABORATORIUM & SAMPLING (Form-ES-6.4.1)</div>
            </div>
        </div>
        <div style="text-align: right; font-size: 0.72rem;">
            <div>Akreditasi KAN LP-1813-IDN</div>
            <div>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
        </div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;" class="no-print">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">🔧 Manajemen Peralatan & Kalibrasi Lab</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Monitoring masa berlaku kalibrasi alat ukur sampling & laboratorium (ISO 17025 Form-ES-6.4.1).</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button onclick="window.print()" style="padding: 10px 18px; background: #0f172a; color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px;">
            🖨️ Cetak Form-ES-6.4.1
        </button>
        <button onclick="openModalTambah()" style="background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px;">
            ➕ Tambah Alat Baru
        </button>
    </div>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; font-size: 0.88rem; display: flex; align-items: center; gap: 10px;" class="no-print">
    <span style="font-size: 1.2rem;">✓</span>
    <div>{{ session('success') }}</div>
</div>
@endif

<!-- Stats Grid -->
<div class="stats-grid-eq no-print">
    <div class="stat-card-eq" onclick="window.location.href='{{ route(request()->routeIs('pengujian.*') ? 'pengujian.peralatan.index' : 'peralatan.index') }}'">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Alat Inventaris</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ number_format($totalAlat) }}</div>
        <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Semua unit teregistrasi</div>
    </div>
    <div class="stat-card-eq" onclick="window.location.href='{{ route(request()->routeIs('pengujian.*') ? 'pengujian.peralatan.index' : 'peralatan.index', ['filter' => 'baik']) }}'">
        <div style="font-size: 0.75rem; font-weight: 700; color: #16a34a; text-transform: uppercase;">Kondisi Baik</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #16a34a; margin-top: 4px;">{{ number_format($baik) }}</div>
        <div style="font-size: 0.72rem; color: #16a34a; margin-top: 2px;">Siap digunakan di lapangan</div>
    </div>
    <div class="stat-card-eq" onclick="window.location.href='{{ route(request()->routeIs('pengujian.*') ? 'pengujian.peralatan.index' : 'peralatan.index', ['filter' => 'warning']) }}'">
        <div style="font-size: 0.75rem; font-weight: 700; color: #ca8a04; text-transform: uppercase;">Warning Kalibrasi (&lt; 30 Hari)</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #ca8a04; margin-top: 4px;">{{ number_format($warning) }}</div>
        <div style="font-size: 0.72rem; color: #ca8a04; margin-top: 2px;">Perlu segera dijadwalkan</div>
    </div>
    <div class="stat-card-eq" onclick="window.location.href='{{ route(request()->routeIs('pengujian.*') ? 'pengujian.peralatan.index' : 'peralatan.index', ['filter' => 'expired']) }}'">
        <div style="font-size: 0.75rem; font-weight: 700; color: #dc2626; text-transform: uppercase;">Expired / Perlu Kalibrasi</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #dc2626; margin-top: 4px;">{{ number_format($expired) }}</div>
        <div style="font-size: 0.72rem; color: #dc2626; margin-top: 2px;">Jangan digunakan</div>
    </div>
</div>

<div class="equip-box">
    <div class="action-bar-eq no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.peralatan.index' : 'peralatan.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%; max-width: 600px;">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Inventaris, Nama Alat, Merk, Lokasi..." class="form-control" style="flex: 2; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.88rem;">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Cari
            </button>
            @if($search || $filter !== 'all')
            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.peralatan.index' : 'peralatan.index') }}" style="padding: 10px 16px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none;">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th style="width: 140px;">No. Inventaris</th>
                    <th>Nama Alat</th>
                    <th>Merk / Model / SN</th>
                    <th>Lokasi</th>
                    <th>Jadwal Kalibrasi</th>
                    <th>Lembaga Kalibrasi</th>
                    <th>Kondisi</th>
                    <th style="text-align: center; width: 100px;" class="no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peralatan as $p)
                <tr>
                    <td>
                        <strong style="color: #0284c7; font-family: monospace; font-size: 0.85rem;">{{ $p->no_inventaris }}</strong>
                    </td>
                    <td>
                        <strong style="color: #0f172a;">{{ $p->nama_alat }}</strong>
                        @if($p->rentang_akurasi && $p->rentang_akurasi !== '-')
                        <div style="font-size: 0.72rem; color: #64748b;">Rentang: {{ $p->rentang_akurasi }}</div>
                        @endif
                    </td>
                    <td>
                        <div>{{ $p->merek_brand ?? '-' }} {{ $p->type_model ? '(' . $p->type_model . ')' : '' }}</div>
                        <div style="font-size: 0.72rem; color: #64748b;">SN: {{ $p->no_seri ?? '-' }}</div>
                    </td>
                    <td>{{ $p->lokasi ?? '-' }}</td>
                    <td>
                        @if($p->jadwal_kalibrasi)
                            @if(\Carbon\Carbon::parse($p->jadwal_kalibrasi)->isPast())
                                <span class="tag" style="background: #fee2e2; color: #dc2626; font-weight: 700;">🚨 Expired: {{ \Carbon\Carbon::parse($p->jadwal_kalibrasi)->translatedFormat('d M Y') }}</span>
                            @elseif(\Carbon\Carbon::parse($p->jadwal_kalibrasi)->diffInDays(now()) <= 30)
                                <span class="tag" style="background: #fef9c3; color: #ca8a04; font-weight: 700;">⚠️ Segera: {{ \Carbon\Carbon::parse($p->jadwal_kalibrasi)->translatedFormat('d M Y') }}</span>
                            @else
                                <span class="tag tag-green">✓ {{ \Carbon\Carbon::parse($p->jadwal_kalibrasi)->translatedFormat('d M Y') }}</span>
                            @endif
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td>{{ $p->lembaga_kalibrasi ?? '-' }}</td>
                    <td>
                        @if($p->kondisi === 'Baik')
                            <span class="tag tag-green">Baik</span>
                        @else
                            <span class="tag tag-orange">{{ $p->kondisi ?? 'Standby' }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;" class="no-print">
                        <div style="display: flex; gap: 4px; justify-content: center;">
                            <button type="button" class="btn-action-sm" style="background: #f1f5f9; color: #334155;" onclick="openModalEdit({{ json_encode($p) }})" title="Edit Alat">
                                ✏️
                            </button>
                            <form action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.peralatan.destroy' : 'peralatan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus alat {{ $p->nama_alat }} dari inventaris?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-sm" style="background: #fee2e2; color: #dc2626;" title="Hapus">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                        Belum ada peralatan inventaris yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;" class="no-print">
        {{ $peralatan->links() }}
    </div>
</div>

<!-- MODAL TAMBAH / EDIT ALAT -->
<div id="modalFormAlat" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h3 id="modalAlatTitle" style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">➕ Tambah Peralatan Inventaris Baru</h3>
            <button type="button" onclick="closeModalFormAlat()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b;">✕</button>
        </div>

        <form id="formAlatAction" method="POST">
            @csrf
            <div id="methodSpoof"></div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">No. Inventaris *</label>
                    <input type="text" name="no_inventaris" id="fNoInventaris" required placeholder="EQP/ES/PL/2601/..." class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Nama Alat *</label>
                    <input type="text" name="nama_alat" id="fNamaAlat" required placeholder="Flue Gas Analyzer..." class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Merek / Brand</label>
                    <input type="text" name="merek_brand" id="fMerek" placeholder="Testo / MRU / Apex" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Type / Model</label>
                    <input type="text" name="type_model" id="fType" placeholder="Model Series" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Nomor Seri (SN)</label>
                    <input type="text" name="no_seri" id="fNoSeri" placeholder="SN-123456" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Lokasi Penyimpanan</label>
                    <input type="text" name="lokasi" id="fLokasi" placeholder="R. Sampling / Lab Utama" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Jadwal Kalibrasi</label>
                    <input type="date" name="jadwal_kalibrasi" id="fJadwalKalibrasi" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Lembaga Kalibrasi</label>
                    <input type="text" name="lembaga_kalibrasi" id="fLembaga" placeholder="LK-361-IDN" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kondisi Fisik Alat</label>
                <select name="kondisi" id="fKondisi" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    <option value="Baik">Baik (Siap Pakai)</option>
                    <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                    <option value="Dalam Kalibrasi">Dalam Kalibrasi</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModalFormAlat()" style="padding: 10px 18px; background: #f1f5f9; color: #475569; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 24px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                    Simpan Data Alat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalTambah() {
        const modal = document.getElementById('modalFormAlat');
        const form = document.getElementById('formAlatAction');
        document.getElementById('modalAlatTitle').textContent = '➕ Tambah Peralatan Inventaris Baru';
        document.getElementById('methodSpoof').innerHTML = '';

        const prefix = window.location.pathname.includes('/pengujian') ? '/pengujian' : '';
        form.action = `${prefix}/peralatan`;

        document.getElementById('fNoInventaris').value = '';
        document.getElementById('fNamaAlat').value = '';
        document.getElementById('fMerek').value = '';
        document.getElementById('fType').value = '';
        document.getElementById('fNoSeri').value = '';
        document.getElementById('fLokasi').value = '';
        document.getElementById('fJadwalKalibrasi').value = '';
        document.getElementById('fLembaga').value = '';
        document.getElementById('fKondisi').value = 'Baik';

        modal.style.display = 'flex';
    }

    function openModalEdit(item) {
        const modal = document.getElementById('modalFormAlat');
        const form = document.getElementById('formAlatAction');
        document.getElementById('modalAlatTitle').textContent = `✏️ Edit Data: ${item.nama_alat}`;
        document.getElementById('methodSpoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        const prefix = window.location.pathname.includes('/pengujian') ? '/pengujian' : '';
        form.action = `${prefix}/peralatan/${item.id}`;

        document.getElementById('fNoInventaris').value = item.no_inventaris || '';
        document.getElementById('fNamaAlat').value = item.nama_alat || '';
        document.getElementById('fMerek').value = item.merek_brand || '';
        document.getElementById('fType').value = item.type_model || '';
        document.getElementById('fNoSeri').value = item.no_seri || '';
        document.getElementById('fLokasi').value = item.lokasi || '';
        document.getElementById('fJadwalKalibrasi').value = item.jadwal_kalibrasi ? item.jadwal_kalibrasi.substring(0, 10) : '';
        document.getElementById('fLembaga').value = item.lembaga_kalibrasi || '';
        document.getElementById('fKondisi').value = item.kondisi || 'Baik';

        modal.style.display = 'flex';
    }

    function closeModalFormAlat() {
        document.getElementById('modalFormAlat').style.display = 'none';
    }
</script>
@endsection
