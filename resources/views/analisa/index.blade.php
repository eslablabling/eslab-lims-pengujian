@extends('layouts.app')

@section('title', 'Log & Input Analisa Laboratorium')

@section('content')
<style>
    .analisa-tab-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .analisa-tab-btn {
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
    .analisa-tab-btn.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .analisa-box {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .btn-input-analisa {
        padding: 6px 14px;
        background: #0284c7;
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
    .btn-input-analisa:hover {
        background: #0369a1;
    }
    .badge-rework {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 800;
        display: inline-block;
        margin-top: 4px;
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
        max-width: 950px;
        padding: 30px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-height: 90vh;
        overflow-y: auto;
    }
    .gas-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .gas-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 10px;
        border: 1px solid #e2e8f0;
        text-align: center;
    }
    .gas-table td {
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.85rem;
        font-family: inherit;
        outline: none;
    }
    .form-control:focus {
        border-color: #2563eb;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">🧪 Log & Input Analisa Laboratorium</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Pengujian parameter fisika, kimia, emisi gas, gravimetri partikulat, dan pengendalian mutu QC duplikat lab.</p>
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
<div class="analisa-tab-nav">
    <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.analisa.index' : 'analisa.index', ['tab' => 'antrean', 'search' => $search]) }}" 
       class="analisa-tab-btn {{ $tab === 'antrean' ? 'active' : '' }}">
        🧪 Antrean Analisa Lab ({{ $countAntrean }})
    </a>
    <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.analisa.index' : 'analisa.index', ['tab' => 'selesai', 'search' => $search]) }}" 
       class="analisa-tab-btn {{ $tab === 'selesai' ? 'active' : '' }}">
        ✅ Riwayat Selesai / Terverifikasi ({{ $countSelesai }})
    </a>
</div>

<div class="analisa-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.analisa.index' : 'analisa.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%; max-width: 600px;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID Sampel, No. COC, Perusahaan, Cerobong..." class="form-control" style="flex: 2; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.88rem;">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Cari
            </button>
            @if($search)
            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.analisa.index' : 'analisa.index', ['tab' => $tab]) }}" style="padding: 10px 16px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none;">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th style="width: 140px;">Sample ID</th>
                    <th style="width: 150px;">No. COC</th>
                    <th>Nama Pelanggan</th>
                    <th>Cerobong / Titik</th>
                    <th>Tgl. Terima Lab</th>
                    <th>Status Analisa</th>
                    <th style="text-align: center; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($samples as $s)
                <tr>
                    <td>
                        <strong style="color: #0284c7; font-family: monospace; font-size: 0.95rem;">{{ $s->sample_id }}</strong>
                        @if($s->rework_reason)
                        <div class="badge-rework" title="{{ $s->rework_reason }}">
                            ⚠️ Revisi: {{ Str::limit($s->rework_reason, 20) }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight: 700; color: #334155;">{{ $s->coc?->nomor_coc ?? '-' }}</span>
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #0f172a;">{{ $s->coc?->company_name ?? '-' }}</div>
                        <div style="font-size: 0.72rem; color: #64748b;">{{ $s->coc?->sampling_location ?? '-' }}</div>
                    </td>
                    <td>
                        <strong style="color: #0f172a;">{{ $s->nama_cerobong ?? $s->description ?? '-' }}</strong>
                    </td>
                    <td>
                        {{ $s->tgl_terima_lab ? \Carbon\Carbon::parse($s->tgl_terima_lab)->translatedFormat('d M Y H:i') : '-' }}
                    </td>
                    <td>
                        @if($s->status_lab === 'Pending Verification')
                            <span class="tag tag-orange">Menunggu Verifikasi</span>
                        @elseif($s->status_lab === 'In Analysis')
                            <span class="tag tag-blue">Sedang Analisa</span>
                        @elseif($s->is_verified)
                            <span class="tag tag-green">✓ Verified (COA Terbit)</span>
                        @else
                            <span class="tag" style="background: #f1f5f9; color: #64748b;">{{ $s->status_lab ?? 'Pending' }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn-input-analisa" onclick="openModalAnalisa({{ json_encode($s) }})">
                            ✏️ Input Parameter
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                        Tidak ada sampel yang memerlukan analisa pada tab ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $samples->links() }}
    </div>
</div>

<!-- MODAL INPUT PARAMETER ANALISA -->
<div id="modalAnalisa" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">🧪 Input Parameter Hasil Pengujian Lab</h3>
                <p id="modalAnalisaSubtitle" style="font-size: 0.8rem; color: #64748b; margin-top: 2px;"></p>
            </div>
            <button type="button" onclick="closeModalAnalisa()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b;">✕</button>
        </div>

        <div id="modalReworkAlert" style="display: none; background: #fef2f2; border: 1px solid #fca5a5; padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; color: #991b1b; font-size: 0.85rem;">
            <strong>⚠️ Catatan Revisi / Rework dari Manajer:</strong>
            <p id="modalReworkText" style="margin-top: 4px; font-style: italic;"></p>
        </div>

        <form id="formAnalisa" method="POST">
            @csrf
            @method('PUT')

            <!-- SECTION PARAMETER LAB -->
            <div style="margin-bottom: 24px;">
                <h4 style="font-size: 0.9rem; font-weight: 800; color: #0f172a; margin-bottom: 10px;">
                    1. Parameter Uji Emisi & Konsentrasi Gas
                </h4>
                <div style="overflow-x: auto; width: 100%;">
                    <table class="gas-table">
                        <thead>
                            <tr>
                                <th style="width: 200px; text-align: left;">Parameter</th>
                                <th style="width: 80px;">Satuan</th>
                                <th style="width: 100px;">R1</th>
                                <th style="width: 100px;">R2</th>
                                <th style="width: 100px;">R3</th>
                                <th style="width: 110px;">Rata-rata</th>
                                <th style="width: 110px;">Terkoreksi $O_2$</th>
                                <th style="width: 100px;">Baku Mutu</th>
                            </tr>
                        </thead>
                        <tbody id="modalParamTableBody">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION QC PARTIKULAT / GRAVIMETRI -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 24px;">
                <h4 style="font-size: 0.9rem; font-weight: 800; color: #0f172a; margin-bottom: 12px;">
                    2. Quality Control (QC) Pengujian Partikulat Gravimetri
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Berat Blanko Filter (g)</label>
                        <input type="number" step="0.0001" name="qc_blank_weight" id="qcBlank" class="form-control" placeholder="Contoh: 0.0012">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Duplikat 1 ($D_1$) [g]</label>
                        <input type="number" step="0.0001" name="qc_dup_weight_1" id="qcDup1" class="form-control" oninput="calculateQcRpd()" placeholder="Contoh: 0.0245">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Duplikat 2 ($D_2$) [g]</label>
                        <input type="number" step="0.0001" name="qc_dup_weight_2" id="qcDup2" class="form-control" oninput="calculateQcRpd()" placeholder="Contoh: 0.0248">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">% RPD (Presisi $\le 10\%$)</label>
                        <input type="number" step="0.01" name="qc_rpd" id="qcRpd" class="form-control" readonly style="font-weight: 800; background: #ffffff;">
                    </div>
                </div>
                <div id="qcStatusDisplay" style="margin-top: 10px; font-size: 0.8rem; font-weight: 700;"></div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <button type="button" onclick="closeModalAnalisa()" style="padding: 10px 18px; background: #f1f5f9; color: #475569; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="status_lab" value="In Analysis" style="padding: 10px 20px; background: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                        💾 Simpan Draft Analisa
                    </button>
                    <button type="button" onclick="submitForVerificationAction()" style="padding: 10px 22px; background: #10b981; color: white; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                        🚀 Ajukan ke Manager Lab
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const masterEmisiData = @json($masterEmisi);
    let activeSampleId = null;

    function openModalAnalisa(sample) {
        activeSampleId = sample.id;
        const modal = document.getElementById('modalAnalisa');
        const subtitle = document.getElementById('modalAnalisaSubtitle');
        const reworkAlert = document.getElementById('modalReworkAlert');
        const reworkText = document.getElementById('modalReworkText');
        const form = document.getElementById('formAnalisa');

        subtitle.textContent = `${sample.sample_id} - ${sample.coc?.company_name || ''} (${sample.nama_cerobong || ''})`;
        
        const prefix = window.location.pathname.includes('/pengujian') ? '/pengujian' : '';
        form.action = `${prefix}/analisa/${sample.id}`;

        if (sample.rework_reason) {
            reworkText.textContent = sample.rework_reason;
            reworkAlert.style.display = 'block';
        } else {
            reworkAlert.style.display = 'none';
        }

        // Fill QC values
        document.getElementById('qcBlank').value = sample.qc_blank_weight || '';
        document.getElementById('qcDup1').value = sample.qc_dup_weight_1 || '';
        document.getElementById('qcDup2').value = sample.qc_dup_weight_2 || '';
        document.getElementById('qcRpd').value = sample.qc_rpd || '';
        calculateQcRpd();

        // Render Parameters
        const tbody = document.getElementById('modalParamTableBody');
        tbody.innerHTML = '';

        let params = sample.parameters || [];
        if (typeof params === 'string') {
            try { params = JSON.parse(params); } catch(e) { params = []; }
        }

        params.forEach((p, idx) => {
            const pName = typeof p === 'string' ? p : (p.parameter || p.nama_parameter || '-');
            const r1 = typeof p === 'object' ? (p.konsentrasi_1 || p.r1 || '') : '';
            const r2 = typeof p === 'object' ? (p.konsentrasi_2 || p.r2 || '') : '';
            const r3 = typeof p === 'object' ? (p.konsentrasi_3 || p.r3 || '') : '';
            const avg = typeof p === 'object' ? (p.hasil || p.rata_rata || '') : '';
            const corr = typeof p === 'object' ? (p.terkoreksi_o2 || p.terkoreksi || '') : '';
            const sat = typeof p === 'object' ? (p.satuan || 'mg/Nm3') : 'mg/Nm3';

            const match = masterEmisiData.find(m => (m.nama_parameter || m.parameter || '').toLowerCase() === pName.toLowerCase());
            const bakuMutu = match ? match.baku_mutu : '-';
            const refO2 = match ? match.koreksi_o2 : null;

            const tr = document.createElement('tr');
            tr.className = 'modal-gas-row';
            tr.dataset.paramName = pName;
            tr.dataset.refO2 = refO2 || '';
            tr.innerHTML = `
                <td style="font-weight: 700; color: #0f172a;">
                    <input type="hidden" name="parameters[${idx}][parameter]" value="${escapeHtml(pName)}">
                    ${escapeHtml(pName)}
                    ${refO2 ? `<div style="font-size: 0.7rem; color: #0284c7;">(Koreksi O2: ${refO2}%)</div>` : ''}
                </td>
                <td>
                    <input type="text" name="parameters[${idx}][satuan]" value="${escapeHtml(sat)}" class="form-control" style="font-size: 0.75rem; text-align: center; padding: 4px;">
                </td>
                <td><input type="number" step="0.01" name="parameters[${idx}][konsentrasi_1]" value="${r1}" class="form-control m-r1" oninput="calculateModalGasRow(this)" style="padding: 6px; text-align: right;"></td>
                <td><input type="number" step="0.01" name="parameters[${idx}][konsentrasi_2]" value="${r2}" class="form-control m-r2" oninput="calculateModalGasRow(this)" style="padding: 6px; text-align: right;"></td>
                <td><input type="number" step="0.01" name="parameters[${idx}][konsentrasi_3]" value="${r3}" class="form-control m-r3" oninput="calculateModalGasRow(this)" style="padding: 6px; text-align: right;"></td>
                <td><input type="number" step="0.01" name="parameters[${idx}][hasil]" value="${avg}" class="form-control m-avg" readonly style="padding: 6px; text-align: right; font-weight: 700; background: #f8fafc;"></td>
                <td><input type="number" step="0.01" name="parameters[${idx}][terkoreksi_o2]" value="${corr}" class="form-control m-corr" readonly style="padding: 6px; text-align: right; font-weight: 800; color: #0284c7; background: #f0fdf4;"></td>
                <td style="text-align: center; font-weight: 700; font-size: 0.8rem; color: #475569;">${bakuMutu ? parseFloat(bakuMutu).toFixed(2) : '-'}</td>
            `;
            tbody.appendChild(tr);
        });

        modal.style.display = 'flex';
    }

    function closeModalAnalisa() {
        document.getElementById('modalAnalisa').style.display = 'none';
    }

    function calculateModalGasRow(elem) {
        const row = elem.closest('.modal-gas-row');
        if (!row) return;

        const r1 = parseFloat(row.querySelector('.m-r1')?.value) || 0;
        const r2 = parseFloat(row.querySelector('.m-r2')?.value) || 0;
        const r3 = parseFloat(row.querySelector('.m-r3')?.value) || 0;

        let count = 0;
        let sum = 0;
        if (row.querySelector('.m-r1')?.value !== '') { sum += r1; count++; }
        if (row.querySelector('.m-r2')?.value !== '') { sum += r2; count++; }
        if (row.querySelector('.m-r3')?.value !== '') { sum += r3; count++; }

        const avg = count > 0 ? (sum / count) : 0;
        const avgInput = row.querySelector('.m-avg');
        if (avgInput) avgInput.value = avg > 0 ? avg.toFixed(2) : '';

        // Measured O2
        let measuredO2 = 0;
        const o2Row = document.querySelector('.modal-gas-row[data-param-name*="Oxygen"], .modal-gas-row[data-param-name*="Oksigen"], .modal-gas-row[data-param-name*="O2"]');
        if (o2Row) {
            measuredO2 = parseFloat(o2Row.querySelector('.m-avg')?.value) || 0;
        }

        const refO2Str = row.dataset.refO2;
        const corrInput = row.querySelector('.m-corr');
        if (refO2Str && refO2Str !== '' && measuredO2 > 0 && measuredO2 < 21) {
            const refO2 = parseFloat(refO2Str);
            const corr = avg * ((21 - refO2) / (21 - measuredO2));
            if (corrInput) corrInput.value = corr > 0 ? corr.toFixed(2) : '';
        } else if (corrInput) {
            corrInput.value = avg > 0 ? avg.toFixed(2) : '';
        }
    }

    function calculateQcRpd() {
        const d1 = parseFloat(document.getElementById('qcDup1')?.value) || 0;
        const d2 = parseFloat(document.getElementById('qcDup2')?.value) || 0;
        const rpdInput = document.getElementById('qcRpd');
        const statusDisplay = document.getElementById('qcStatusDisplay');

        if (d1 > 0 && d2 > 0) {
            const rpd = (Math.abs(d1 - d2) / ((d1 + d2) / 2)) * 100;
            if (rpdInput) rpdInput.value = rpd.toFixed(2);
            if (statusDisplay) {
                if (rpd <= 10.0) {
                    statusDisplay.innerHTML = `<span style="color: #15803d;">✅ QC Presisi Gravimetri LOLOS (RPD ${rpd.toFixed(2)}% ≤ 10%)</span>`;
                } else {
                    statusDisplay.innerHTML = `<span style="color: #b91c1c;">⚠️ QC Presisi Gravimetri GAGAL (RPD ${rpd.toFixed(2)}% > 10%)</span>`;
                }
            }
        } else if (rpdInput) {
            rpdInput.value = '';
            if (statusDisplay) statusDisplay.innerHTML = '';
        }
    }

    function submitForVerificationAction() {
        if (!activeSampleId) return;
        if (confirm('Kirim hasil pengujian sampel ini ke Manajer Lab untuk proses verifikasi CoA?')) {
            const form = document.getElementById('formAnalisa');
            const prefix = window.location.pathname.includes('/pengujian') ? '/pengujian' : '';
            form.action = `${prefix}/analisa/${activeSampleId}/submit-verify`;
            form.submit();
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
</script>
@endsection
