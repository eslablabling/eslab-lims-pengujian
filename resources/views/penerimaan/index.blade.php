@extends('layouts.app')

@section('title', 'Penerimaan Sampel Laboratorium')

@section('content')
<style>
    .penerimaan-tab-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .penerimaan-tab-btn {
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        color: #64748b;
        transition: all 0.2s;
    }
    .penerimaan-tab-btn.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .penerimaan-box {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .btn-terima-single {
        padding: 6px 14px;
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }
    .btn-terima-single:hover {
        background: #059669;
    }
    .btn-batch-receive {
        padding: 10px 18px;
        background: #0284c7;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
        max-width: 600px;
        padding: 30px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-height: 90vh;
        overflow-y: auto;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📥 Penerimaan & Registrasi Sampel Masuk Lab</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Pemeriksaan kondisi fisik sampel, integritas wadah/segel, dan serah terima ke tim analis laboratorium.</p>
    </div>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; font-size: 0.88rem; display: flex; align-items: center; gap: 10px;">
    <span style="font-size: 1.2rem;">✓</span>
    <div>{{ session('success') }}</div>
</div>
@endif

@if($errors->any())
<div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 0.88rem;">
    <div style="font-weight: 800; margin-bottom: 6px;">⚠️ Terjadi kesalahan:</div>
    <ul style="margin-left: 20px;">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Tab Navigation -->
<div class="penerimaan-tab-nav">
    <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.penerimaan.index' : 'penerimaan.index', ['tab' => 'belum_diterima', 'search' => $search]) }}" 
       class="penerimaan-tab-btn {{ $tab === 'belum_diterima' ? 'active' : '' }}">
        📦 Belum Diterima di Lab ({{ $countBelumDiterima }})
    </a>
    <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.penerimaan.index' : 'penerimaan.index', ['tab' => 'sudah_diterima', 'search' => $search]) }}" 
       class="penerimaan-tab-btn {{ $tab === 'sudah_diterima' ? 'active' : '' }}">
        ✅ Sudah Diterima di Lab ({{ $countSudahDiterima }})
    </a>
</div>

<div class="penerimaan-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.penerimaan.index' : 'penerimaan.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%; max-width: 600px;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID Sampel, No. COC, Perusahaan, Cerobong..." class="form-control" style="flex: 2; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.88rem;">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Cari
            </button>
            @if($search)
            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.penerimaan.index' : 'penerimaan.index', ['tab' => $tab]) }}" style="padding: 10px 16px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none;">
                Reset
            </a>
            @endif
        </form>

        @if($tab === 'belum_diterima')
        <button type="button" class="btn-batch-receive" onclick="openBatchReceiveModal()">
            📦 Terima Semua yang Dipilih
        </button>
        @endif
    </div>

    <form id="formBatchReceive" method="POST" action="{{ url('/pengujian/penerimaan/batch-receive') }}">
        @csrf
        <div style="overflow-x: auto; width: 100%;">
            <table>
                <thead>
                    <tr>
                        @if($tab === 'belum_diterima')
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="checkAllSamples" onclick="toggleSelectAll(this)">
                        </th>
                        @endif
                        <th style="width: 140px;">Sample ID</th>
                        <th style="width: 150px;">No. COC</th>
                        <th>Nama Perusahaan</th>
                        <th>Cerobong / Titik</th>
                        <th>Tgl. Sampling</th>
                        <th>Tgl. Terima Lab</th>
                        <th>Status Lab</th>
                        <th style="text-align: center; width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($samples as $s)
                    <tr>
                        @if($tab === 'belum_diterima')
                        <td style="text-align: center;">
                            <input type="checkbox" name="sample_ids[]" value="{{ $s->id }}" class="sample-checkbox">
                        </td>
                        @endif
                        <td>
                            <strong style="color: #0284c7; font-family: monospace; font-size: 0.95rem;">{{ $s->sample_id }}</strong>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #334155;">{{ $s->coc?->nomor_coc ?? '-' }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: #0f172a;">{{ $s->coc?->company_name ?? '-' }}</div>
                            <div style="font-size: 0.72rem; color: #64748b;">Petugas: {{ $s->coc?->sampling_officer ?? '-' }}</div>
                        </td>
                        <td>
                            <strong style="color: #0f172a;">{{ $s->nama_cerobong ?? $s->description ?? '-' }}</strong>
                        </td>
                        <td>
                            {{ $s->coc?->sampling_date ? \Carbon\Carbon::parse($s->coc->sampling_date)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td>
                            @if($s->tgl_terima_lab)
                                <div style="font-weight: 700; color: #166534;">
                                    ✓ {{ \Carbon\Carbon::parse($s->tgl_terima_lab)->translatedFormat('d M Y H:i') }}
                                </div>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Belum Diterima</span>
                            @endif
                        </td>
                        <td>
                            @if($s->status_lab === 'In Analysis')
                                <span class="tag tag-blue">Sedang Analisa</span>
                            @elseif($s->status_lab === 'Pending Verification')
                                <span class="tag tag-orange">Menunggu Verifikasi</span>
                            @elseif($s->is_verified)
                                <span class="tag tag-green">✓ Verified</span>
                            @else
                                <span class="tag" style="background: #f1f5f9; color: #64748b;">{{ $s->status_lab ?? 'Pending' }}</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if(!$s->tgl_terima_lab)
                            <button type="button" class="btn-terima-single" onclick="openReceiveModal({{ $s->id }}, '{{ $s->sample_id }}', '{{ $s->coc?->company_name }}', '{{ $s->nama_cerobong }}')">
                                📥 Terima
                            </button>
                            @else
                            <span style="font-size: 0.75rem; color: #15803d; font-weight: 700;">
                                ✅ Diterima Lab
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $tab === 'belum_diterima' ? 9 : 8 }}" style="text-align: center; padding: 40px; color: #94a3b8;">
                            Tidak ada sampel dalam kategori ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div style="margin-top: 20px;">
        {{ $samples->links() }}
    </div>
</div>

<!-- MODAL TERIMA SINGLE SAMPLE -->
<div id="modalReceive" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">📥 Konfirmasi Penerimaan Sampel</h3>
                <p id="receiveModalSubtitle" style="font-size: 0.8rem; color: #64748b; margin-top: 2px;"></p>
            </div>
            <button type="button" onclick="closeReceiveModal()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b;">✕</button>
        </div>

        <form id="formSingleReceive" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Tanggal & Waktu Terima Lab *</label>
                <input type="datetime-local" name="tgl_terima_lab" id="inputTglTerima" value="{{ date('Y-m-d\TH:i') }}" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 10px;" required>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Kondisi Kemasan / Segel Wadah</label>
                <select name="kondisi_wadah" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 10px;">
                    <option value="Baik & Tersegel Utuh">Baik & Tersegel Utuh</option>
                    <option value="Baik (Tanpa Segel)">Baik (Tanpa Segel)</option>
                    <option value="Kemasan Rusak / Bocor">Kemasan Rusak / Bocor</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Suhu Sampel Saat Diterima (°C)</label>
                <input type="number" step="0.1" name="suhu_terima" value="25.0" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 10px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeReceiveModal()" style="padding: 10px 18px; background: #f1f5f9; color: #475569; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 24px; background: #10b981; color: white; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                    ✓ Konfirmasi Terima Sampel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.sample-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
    }

    function openReceiveModal(sampleId, sampleCode, companyName, cerobongName) {
        const modal = document.getElementById('modalReceive');
        const subtitle = document.getElementById('receiveModalSubtitle');
        const form = document.getElementById('formSingleReceive');

        subtitle.textContent = `${sampleCode} - ${companyName} (${cerobongName})`;
        const prefix = window.location.pathname.includes('/pengujian') ? '/pengujian' : '';
        form.action = `${prefix}/penerimaan/${sampleId}/receive`;
        modal.style.display = 'flex';
    }

    function closeReceiveModal() {
        document.getElementById('modalReceive').style.display = 'none';
    }

    function openBatchReceiveModal() {
        const checked = document.querySelectorAll('.sample-checkbox:checked');
        if (checked.length === 0) {
            alert('Silakan centang minimal 1 sampel untuk diterima bersamaan.');
            return;
        }
        if (confirm(`Terima ${checked.length} sampel yang dicentang sekaligus?`)) {
            const form = document.getElementById('formBatchReceive');
            const prefix = window.location.pathname.includes('/pengujian') ? '/pengujian' : '';
            form.action = `${prefix}/penerimaan/batch-receive`;

            // Append date input
            const inputDate = document.createElement('input');
            inputDate.type = 'hidden';
            inputDate.name = 'tgl_terima_lab';
            inputDate.value = new Date().toISOString();
            form.appendChild(inputDate);

            form.submit();
        }
    }
</script>
@endsection
