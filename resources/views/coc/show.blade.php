@extends('layouts.app')

@section('title', 'Detail COC ' . $coc->nomor_coc)

@section('content')
<style>
    .coc-header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .print-tab-nav {
        display: flex;
        gap: 8px;
        background: #f1f5f9;
        padding: 6px;
        border-radius: 14px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .print-tab-btn {
        padding: 8px 16px;
        border: none;
        background: transparent;
        color: #475569;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .print-tab-btn.active {
        background: #ffffff;
        color: #2563eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .coc-sheet {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        margin-bottom: 30px;
    }
    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    /* ============================================================
       PRINT MEDIA STYLES (A4 Landscape for COC, Portrait for SPK/SJ)
       ============================================================ */
    @media print {
        @page {
            size: A4 landscape;
            margin: 0.8cm 0.6cm 0.8cm 0.6cm;
        }
        .sidebar, .top-bar, .mobile-backdrop, .no-print, button, .coc-header-actions, .print-tab-nav {
            display: none !important;
        }
        body, .content-body, .data-container, .coc-sheet {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .print-container {
            display: block !important;
            width: 100% !important;
        }
        .print-hide-when-other {
            display: none !important;
        }
        .active-print-mode {
            display: block !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #000000 !important;
            color: #000000 !important;
        }
    }
</style>

<div class="coc-header-actions no-print">
    <div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.index' : 'coc.index') }}" style="color: #64748b; text-decoration: none; font-size: 1.1rem;" title="Kembali ke Daftar">
                ⬅️
            </a>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">
                Dokumen COC: <span style="color: #0284c7; font-family: monospace;">{{ $coc->nomor_coc }}</span>
            </h2>
            @if($coc->status === 'Verified')
                <span class="badge-status" style="background: #dcfce7; color: #166534;">✓ Verified</span>
            @elseif($coc->status === 'Analisa')
                <span class="badge-status" style="background: #ffedd5; color: #c2410c;">Analisa Lab</span>
            @else
                <span class="badge-status" style="background: #f1f5f9; color: #475569;">{{ $coc->status ?? 'Draft' }}</span>
            @endif
        </div>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">Perusahaan: <strong>{{ $coc->company_name }}</strong> | Tanggal Sampling: <strong>{{ $coc->sampling_date ? \Carbon\Carbon::parse($coc->sampling_date)->translatedFormat('d F Y') : '-' }}</strong></p>
    </div>

    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.edit' : 'coc.edit', $coc->id) }}" style="padding: 10px 18px; background: #f59e0b; color: white; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 6px;">
            ✏️ Edit COC
        </a>
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            🖨️ Cetak Dokumen
        </button>
    </div>
</div>

<!-- Print View Selector Tabs -->
<div class="print-tab-nav no-print">
    <button type="button" class="print-tab-btn active" id="btnModeCoc" onclick="switchPrintMode('coc')">
        📄 Cetak Form-ES-7.4.1 (Chain of Custody Landscape)
    </button>
    <button type="button" class="print-tab-btn" id="btnModeSpk" onclick="switchPrintMode('spk')">
        📋 Cetak Form-ES-7.4.6 (Surat Tugas Sampling)
    </button>
</div>

<!-- ============================================================ -->
<!-- 1. DOKUMEN COC RESMI (Form-ES-7.4.1) Landscape -->
<!-- ============================================================ -->
<div id="printAreaCoc" class="coc-sheet print-container active-print-mode">
    <!-- KOP FORM-ES-7.4.1 -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; border: 1.5px solid #0f172a;">
        <tr>
            <td style="width: 130px; padding: 10px; text-align: center; border-right: 1.5px solid #0f172a; vertical-align: middle;">
                <img src="{{ asset('logo_eslab.jpg') }}" alt="Logo ESLab" style="max-width: 110px; max-height: 55px; object-fit: contain;">
            </td>
            <td style="text-align: center; padding: 10px; vertical-align: middle; border-right: 1.5px solid #0f172a;">
                <h3 style="font-size: 1.05rem; font-weight: 800; text-transform: uppercase; margin: 0; color: #0f172a;">PT ENVIROTAMA SOLUSINDO</h3>
                <h4 style="font-size: 0.95rem; font-weight: 800; text-transform: uppercase; margin: 4px 0 0 0; color: #2563eb;">RANTAI PENGAWASAN SAMPEL (CHAIN OF CUSTODY)</h4>
                <div style="font-size: 0.75rem; color: #475569; margin-top: 2px;">EMISI SUMBER TIDAK BERGERAK & KUALITAS UDARA LINGKUNGAN</div>
            </td>
            <td style="width: 180px; padding: 8px 12px; font-size: 0.72rem; vertical-align: middle;">
                <div><strong>No. Dokumen:</strong> Form-ES-7.4.1</div>
                <div><strong>Revisi / Edisi:</strong> 02 / 01</div>
                <div><strong>Tgl. Terbit:</strong> 01-08-2024</div>
                <div><strong>Halaman:</strong> 1 dari 1</div>
            </td>
        </tr>
    </table>

    <!-- INFORMASI PELANGGAN & SAMPLING -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 0.8rem; border: 1px solid #cbd5e1;">
        <tr>
            <td style="padding: 6px 10px; width: 18%; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">No. COC</td>
            <td style="padding: 6px 10px; width: 32%; border: 1px solid #cbd5e1; font-family: monospace; font-weight: 800; color: #0284c7;">{{ $coc->nomor_coc }}</td>
            <td style="padding: 6px 10px; width: 18%; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Tanggal Sampling</td>
            <td style="padding: 6px 10px; width: 32%; border: 1px solid #cbd5e1; font-weight: 700;">{{ $coc->sampling_date ? \Carbon\Carbon::parse($coc->sampling_date)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Nama Perusahaan</td>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1; font-weight: 800; color: #0f172a;">{{ $coc->company_name }}</td>
            <td style="padding: 6px 10px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Target Selesai COA</td>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1;">{{ $coc->tgl_selesai ? \Carbon\Carbon::parse($coc->tgl_selesai)->translatedFormat('d F Y') : ($coc->tat_days ? $coc->tat_days . ' Hari Kerja' : '-') }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Alamat Perusahaan</td>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1;">{{ $coc->alamat_perusahaan ?? $coc->lokasi_kota ?? '-' }}</td>
            <td style="padding: 6px 10px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Petugas Sampling</td>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1; font-weight: 700;">{{ $coc->sampling_officer ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Kontak Person (PIC)</td>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1;">{{ $coc->contact_person ?? '-' }} ({{ $coc->no_telepon ?? '-' }})</td>
            <td style="padding: 6px 10px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Email Pengiriman COA</td>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1;">{{ $coc->email_coa ?? '-' }}</td>
        </tr>
    </table>

    <!-- TABEL TITIK SAMPEL & PARAMETER -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 0.78rem; border: 1.5px solid #0f172a;">
        <thead>
            <tr style="background: #f1f5f9; text-transform: uppercase;">
                <th style="padding: 8px 6px; border: 1px solid #0f172a; text-align: center; width: 35px;">No</th>
                <th style="padding: 8px 8px; border: 1px solid #0f172a; width: 120px;">Sample ID</th>
                <th style="padding: 8px 10px; border: 1px solid #0f172a; width: 220px;">Titik Cerobong / Lokasi</th>
                <th style="padding: 8px 10px; border: 1px solid #0f172a; width: 240px;">Regulasi Baku Mutu</th>
                <th style="padding: 8px 10px; border: 1px solid #0f172a;">Parameter Uji yang Diambil</th>
                <th style="padding: 8px 6px; border: 1px solid #0f172a; text-align: center; width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coc->samples as $idx => $s)
            <tr>
                <td style="padding: 8px 6px; border: 1px solid #0f172a; text-align: center; font-weight: 700;">{{ $idx + 1 }}</td>
                <td style="padding: 8px 8px; border: 1px solid #0f172a; font-family: monospace; font-weight: 800; color: #0284c7;">{{ $s->sample_id }}</td>
                <td style="padding: 8px 10px; border: 1px solid #0f172a; font-weight: 700;">{{ $s->nama_cerobong ?? $s->description ?? '-' }}</td>
                <td style="padding: 8px 10px; border: 1px solid #0f172a; font-size: 0.72rem;">
                    {{ is_array($s->regulations) ? implode(', ', $s->regulations) : ($s->regulations ?? '-') }}
                </td>
                <td style="padding: 8px 10px; border: 1px solid #0f172a;">
                    @if(is_array($s->parameters) && count($s->parameters) > 0)
                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                            @foreach($s->parameters as $p)
                                @php
                                    $pLabel = is_array($p) ? ($p['parameter'] ?? $p['nama_parameter'] ?? '') : (string)$p;
                                @endphp
                                <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 0.72rem; border: 1px solid #e2e8f0;">{{ $pLabel }}</span>
                            @endforeach
                        </div>
                    @else
                        <span style="color: #94a3b8;">-</span>
                    @endif
                </td>
                <td style="padding: 8px 6px; border: 1px solid #0f172a; text-align: center; font-size: 0.72rem; font-weight: 700;">
                    {{ $s->status ?? 'Draft' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- LEMBAR TANDA TANGAN (4 PIHAK) -->
    <table style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.75rem; border: 1px solid #cbd5e1;">
        <tr style="background: #f8fafc; font-weight: 700;">
            <td style="padding: 8px; width: 25%; border: 1px solid #cbd5e1;">Pengambil Contoh (Sampler)</td>
            <td style="padding: 8px; width: 25%; border: 1px solid #cbd5e1;">Saksi / Pelanggan</td>
            <td style="padding: 8px; width: 25%; border: 1px solid #cbd5e1;">Diterima Petugas Lab</td>
            <td style="padding: 8px; width: 25%; border: 1px solid #cbd5e1;">Diverifikasi Manajer Teknis</td>
        </tr>
        <tr style="height: 75px;">
            <td style="border: 1px solid #cbd5e1; vertical-align: bottom; padding-bottom: 6px;">
                <div style="font-weight: 800;">( {{ $coc->sampling_officer ?? '..........................' }} )</div>
                <div style="font-size: 0.68rem; color: #64748b;">Tgl: {{ $coc->sampling_date ? \Carbon\Carbon::parse($coc->sampling_date)->format('d/m/Y') : '..../..../20...' }}</div>
            </td>
            <td style="border: 1px solid #cbd5e1; vertical-align: bottom; padding-bottom: 6px;">
                <div style="font-weight: 800;">( {{ $coc->contact_person ?? '..........................' }} )</div>
                <div style="font-size: 0.68rem; color: #64748b;">Tgl: {{ $coc->sampling_date ? \Carbon\Carbon::parse($coc->sampling_date)->format('d/m/Y') : '..../..../20...' }}</div>
            </td>
            <td style="border: 1px solid #cbd5e1; vertical-align: bottom; padding-bottom: 6px;">
                <div style="font-weight: 800;">( .......................... )</div>
                <div style="font-size: 0.68rem; color: #64748b;">Tgl: ..../..../20...</div>
            </td>
            <td style="border: 1px solid #cbd5e1; vertical-align: bottom; padding-bottom: 6px;">
                <div style="font-weight: 800;">( Dely, S.T. )</div>
                <div style="font-size: 0.68rem; color: #64748b;">Tgl: ..../..../20...</div>
            </td>
        </tr>
    </table>
</div>

<!-- ============================================================ -->
<!-- 2. SURAT TUGAS SAMPLING (Form-ES-7.4.6) Portrait -->
<!-- ============================================================ -->
<div id="printAreaSpk" class="coc-sheet print-container print-hide-when-other" style="display: none; max-width: 850px; margin: 0 auto;">
    <div style="text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 20px;">
        <img src="{{ asset('logo_eslab.jpg') }}" alt="Logo ESLab" style="max-height: 60px; margin-bottom: 8px;">
        <h3 style="font-size: 1.15rem; font-weight: 800; margin: 0; text-transform: uppercase;">PT ENVIROTAMA SOLUSINDO</h3>
        <h4 style="font-size: 0.95rem; font-weight: 800; margin: 4px 0 0 0; color: #2563eb; text-transform: uppercase;">SURAT TUGAS PENGAMBILAN CONTOH UJI (SAMPLING)</h4>
        <div style="font-size: 0.8rem; color: #64748b;">No. Form-ES-7.4.6 / Rev. 02 | Terkait COC No: <strong>{{ $coc->nomor_coc }}</strong></div>
    </div>

    <p style="font-size: 0.88rem; line-height: 1.6; margin-bottom: 15px;">
        Dengan ini Manajemen Laboratorium Lingkungan <strong>PT Envirotama Solusindo</strong> menugaskan kepada personel berikut:
    </p>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid #cbd5e1;">
        <tr>
            <td style="padding: 8px 12px; width: 30%; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Nama Petugas Sampling</td>
            <td style="padding: 8px 12px; border: 1px solid #cbd5e1; font-weight: 800;">{{ $coc->sampling_officer ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Perusahaan / Klien</td>
            <td style="padding: 8px 12px; border: 1px solid #cbd5e1; font-weight: 800; color: #0f172a;">{{ $coc->company_name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Lokasi Pekerjaan</td>
            <td style="padding: 8px 12px; border: 1px solid #cbd5e1;">{{ $coc->sampling_location ?? $coc->alamat_perusahaan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Tanggal Pelaksanaan</td>
            <td style="padding: 8px 12px; border: 1px solid #cbd5e1; font-weight: 700;">{{ $coc->sampling_date ? \Carbon\Carbon::parse($coc->sampling_date)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 12px; background: #f8fafc; font-weight: 700; border: 1px solid #cbd5e1;">Jumlah Titik Cerobong</td>
            <td style="padding: 8px 12px; border: 1px solid #cbd5e1; font-weight: 800; color: #0284c7;">{{ $coc->samples->count() }} Titik Sampel</td>
        </tr>
    </table>

    <p style="font-size: 0.85rem; line-height: 1.6; color: #334155; margin-bottom: 30px;">
        Demikian Surat Tugas ini diterbitkan untuk dilaksanakan dengan penuh tanggung jawab sesuai prosedur K3 dan standar ISO/IEC 17025:2017.
    </p>

    <div style="display: flex; justify-content: flex-end;">
        <div style="text-align: center; width: 240px; font-size: 0.85rem;">
            <div>Cikarang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div style="font-weight: 700; margin-top: 4px;">Manajer Teknis,</div>
            <div style="height: 60px;"></div>
            <div style="font-weight: 800; text-decoration: underline;">Dely, S.T.</div>
            <div style="font-size: 0.75rem; color: #64748b;">PT Envirotama Solusindo</div>
        </div>
    </div>
</div>

<script>
    function switchPrintMode(mode) {
        const areaCoc = document.getElementById('printAreaCoc');
        const areaSpk = document.getElementById('printAreaSpk');
        const btnCoc = document.getElementById('btnModeCoc');
        const btnSpk = document.getElementById('btnModeSpk');

        if (mode === 'coc') {
            areaCoc.style.display = 'block';
            areaSpk.style.display = 'none';
            btnCoc.classList.add('active');
            btnSpk.classList.remove('active');
        } else {
            areaCoc.style.display = 'none';
            areaSpk.style.display = 'block';
            btnCoc.classList.remove('active');
            btnSpk.classList.add('active');
        }
    }
</script>
@endsection
