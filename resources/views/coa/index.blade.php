@extends('layouts.app')

@section('title', 'Verifikasi & Penerbitan CoA')

@section('content')
<style>
    .coa-tab-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .coa-tab-btn {
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
    .coa-tab-btn.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .coa-box {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .btn-act-approve {
        padding: 6px 12px;
        background: #16a34a;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
    }
    .btn-act-reject {
        padding: 6px 12px;
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
    }
    .btn-act-preview {
        padding: 6px 12px;
        background: #0284c7;
        color: white;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    /* Modal Rework */
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
        max-width: 500px;
        padding: 30px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📜 Verifikasi & Penerbitan CoA Digital</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Sign-off Manajer Teknis, verifikasi QC hasil uji, dan penerbitan Certificate of Analysis resmi KAN LP-1813-IDN.</p>
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

<div class="coa-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coa.index' : 'coa.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%; max-width: 600px;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID Sampel, No. COC, Perusahaan..." class="form-control" style="flex: 2; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.88rem;">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Cari
            </button>
            @if($search)
            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coa.index' : 'coa.index') }}" style="padding: 10px 16px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none;">
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
                    <th>QC Presisi RPD</th>
                    <th>Status Verifikasi</th>
                    <th style="text-align: center; width: 230px;">Aksi Manajer Lab</th>
                </tr>
            </thead>
            <tbody>
                @forelse($samples as $s)
                <tr>
                    <td>
                        <strong style="color: #0284c7; font-family: monospace; font-size: 0.95rem;">{{ $s->sample_id }}</strong>
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
                        @if($s->qc_rpd)
                            <span class="tag tag-blue" style="font-weight: 700;">{{ $s->qc_rpd }}% ({{ $s->qc_status ?? 'Passed' }})</span>
                        @else
                            <span style="color: #94a3b8; font-size: 0.75rem;">Direct Reading</span>
                        @endif
                    </td>
                    <td>
                        @if($s->is_verified)
                            <span class="tag tag-green">✓ Verified ({{ \Carbon\Carbon::parse($s->verified_at)->translatedFormat('d M Y') }})</span>
                        @else
                            <span class="tag tag-orange">Menunggu Approval Manajer</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                            @if(!$s->is_verified)
                            <form action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coa.verify' : 'coa.verify', $s->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Verifikasi & setujui penerbitan Certificate of Analysis untuk sampel {{ $s->sample_id }}?')">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn-act-approve" title="Approve CoA">
                                    ✓ Approve
                                </button>
                            </form>
                            <button type="button" class="btn-act-reject" onclick="openRejectModal({{ $s->id }}, '{{ $s->sample_id }}')" title="Tolak & Minta Uji Ulang">
                                ✕ Rework
                            </button>
                            @endif

                            @if($s->coc)
                            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coa.preview' : 'coa.preview', $s->coc->id) }}" target="_blank" class="btn-act-preview" title="Preview Certificate of Analysis">
                                📜 Preview CoA
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                        Tidak ada sampel yang menunggu verifikasi CoA.
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

<!-- MODAL REJECT / REWORK -->
<div id="modalReject" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #991b1b; margin: 0;">⚠️ Kembalikan Sampel untuk Analisa Ulang (Rework)</h3>
            <button type="button" onclick="closeRejectModal()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b;">✕</button>
        </div>

        <form id="formReject" method="POST">
            @csrf
            <input type="hidden" name="action" value="reject">

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    Alasan Penolakan / Catatan Perbaikan untuk Analis *
                </label>
                <textarea name="rework_reason" rows="4" class="form-control" placeholder="Tuliskan catatan teknis mengapa hasil analisa ditolak atau perlu diuji ulang..." required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeRejectModal()" style="padding: 10px 18px; background: #f1f5f9; color: #475569; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 24px; background: #dc2626; color: white; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                    ✕ Kembalikan Sampel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(sampleId, sampleCode) {
        const modal = document.getElementById('modalReject');
        const form = document.getElementById('formReject');
        const prefix = window.location.pathname.includes('/pengujian') ? '/pengujian' : '';
        form.action = `${prefix}/coa/${sampleId}/verify`;
        modal.style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('modalReject').style.display = 'none';
    }
</script>
@endsection
