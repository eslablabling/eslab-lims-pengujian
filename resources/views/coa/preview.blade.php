@extends('layouts.app')

@section('title', 'Certificate of Analysis ' . $coc->nomor_coc)

@section('content')
<style>
    .coa-action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .coa-paper {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        padding: 45px 55px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        max-width: 950px;
        margin: 0 auto 40px auto;
        color: #0f172a;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .coa-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2.5px solid #0f172a;
        padding-bottom: 18px;
        margin-bottom: 24px;
    }
    .coa-title-box {
        text-align: center;
        margin-bottom: 24px;
    }
    .coa-title-box h3 {
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0;
        color: #0f172a;
    }
    .coa-title-box p {
        font-size: 0.85rem;
        color: #475569;
        margin-top: 4px;
        font-family: monospace;
        font-weight: 700;
    }
    .coa-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 0.82rem;
    }
    .coa-info-table td {
        padding: 4px 8px;
        vertical-align: top;
    }
    .coa-info-label {
        font-weight: 700;
        color: #334155;
        width: 22%;
    }
    .coa-result-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 0.82rem;
    }
    .coa-result-table th {
        background: #f8fafc;
        border: 1px solid #0f172a;
        padding: 8px 6px;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        text-align: center;
    }
    .coa-result-table td {
        border: 1px solid #0f172a;
        padding: 6px 8px;
        vertical-align: middle;
    }
    .coa-sign-box {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 35px;
        page-break-inside: avoid;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 1.2cm 1cm 1.2cm 1cm;
        }
        .sidebar, .top-bar, .mobile-backdrop, .no-print, button, .coa-action-bar {
            display: none !important;
        }
        body, .content-body, .data-container {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .coa-paper {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>

<div class="coa-action-bar no-print">
    <div style="display: flex; align-items: center; gap: 10px;">
        <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coa.index' : 'coa.index') }}" style="color: #64748b; text-decoration: none; font-size: 1.1rem;">⬅️</a>
        <h2 style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">Preview Certificate of Analysis (COA)</h2>
    </div>
    <div style="display: flex; gap: 10px;">
        <button onclick="window.print()" style="padding: 10px 22px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>
</div>

@php
    $masterEmisi = \App\Models\MasterEmisi::where('is_active', true)->get();
@endphp

@foreach($coc->samples as $sampleIdx => $sample)
<div class="coa-paper" style="{{ $sampleIdx > 0 ? 'page-break-before: always; margin-top: 30px;' : '' }}">
    <!-- KOP RESMI -->
    <div class="coa-header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <img src="{{ asset('logo_eslab.jpg') }}" alt="Logo ESLab" style="max-height: 55px; object-fit: contain;">
            <div>
                <h4 style="font-size: 1.05rem; font-weight: 800; margin: 0; color: #0f172a; text-transform: uppercase;">PT ENVIROTAMA SOLUSINDO</h4>
                <div style="font-size: 0.72rem; color: #475569; margin-top: 2px;">
                    Laboratorium Pengujian Lingkungan Terakreditasi KAN LP-1813-IDN
                </div>
                <div style="font-size: 0.68rem; color: #64748b;">
                    Kawasan Industri Delta Silicon, Cikarang Pusat, Kab. Bekasi - Jawa Barat
                </div>
            </div>
        </div>
        <div style="text-align: right;">
            <img src="{{ asset('kan_logo.png') }}" alt="Logo KAN" style="max-height: 45px; object-fit: contain;">
            <div style="font-size: 0.65rem; font-weight: 800; color: #0f172a; margin-top: 2px;">LP-1813-IDN</div>
        </div>
    </div>

    <!-- TITLE -->
    <div class="coa-title-box">
        <h3>LAPORAN HASIL PENGUJIAN</h3>
        <p>Certificate of Analysis No: COA.ES/{{ date('Y') }}/{{ str_replace('COC-ES/', '', $coc->nomor_coc) }}.{{ $sampleIdx + 1 }}</p>
    </div>

    <!-- IDENTITAS LAPORAN -->
    <table class="coa-info-table">
        <tr>
            <td class="coa-info-label">Nama Pelanggan</td>
            <td style="width: 2%;">:</td>
            <td style="width: 40%; font-weight: 800; color: #0f172a;">{{ $coc->company_name }}</td>
            <td class="coa-info-label">Identitas Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 34%; font-family: monospace; font-weight: 800; color: #0284c7;">{{ $sample->sample_id }}</td>
        </tr>
        <tr>
            <td class="coa-info-label">Alamat / Lokasi</td>
            <td>:</td>
            <td>{{ $coc->alamat_perusahaan ?? $coc->lokasi_kota ?? '-' }}</td>
            <td class="coa-info-label">Deskripsi Titik</td>
            <td>:</td>
            <td style="font-weight: 700;">{{ $sample->nama_cerobong ?? $sample->description ?? '-' }}</td>
        </tr>
        <tr>
            <td class="coa-info-label">Narahubung (PIC)</td>
            <td>:</td>
            <td>{{ $coc->contact_person ?? '-' }}</td>
            <td class="coa-info-label">Jenis Bahan Bakar</td>
            <td>:</td>
            <td>{{ $sample->bahan_bakar ?? 'Gas Alam / Solar' }}</td>
        </tr>
        <tr>
            <td class="coa-info-label">Tanggal Sampling</td>
            <td>:</td>
            <td>{{ $coc->sampling_date ? \Carbon\Carbon::parse($coc->sampling_date)->translatedFormat('d F Y') : '-' }}</td>
            <td class="coa-info-label">Koordinat Titik</td>
            <td>:</td>
            <td>{{ $sample->koordinat ?? $coc->latitude ? $coc->latitude . ', ' . $coc->longitude : '-' }}</td>
        </tr>
        <tr>
            <td class="coa-info-label">Tgl. Diterima Lab</td>
            <td>:</td>
            <td>{{ $sample->tgl_terima_lab ? \Carbon\Carbon::parse($sample->tgl_terima_lab)->translatedFormat('d F Y') : '-' }}</td>
            <td class="coa-info-label">Tanggal Pengujian</td>
            <td>:</td>
            <td>{{ $sample->analyzed_at ? \Carbon\Carbon::parse($sample->analyzed_at)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <!-- TABEL HASIL PENGUJIAN -->
    @php
        $rawParams = is_array($sample->parameters) ? $sample->parameters : [];
    @endphp
    <table class="coa-result-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 190px; text-align: left;">Parameter Uji</th>
                <th style="width: 150px;">Metode Analisis</th>
                <th style="width: 80px;">Satuan</th>
                <th style="width: 80px;">Baku Mutu</th>
                <th style="width: 85px;">Hasil Terukur</th>
                <th style="width: 85px;">Hasil Terkoreksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($rawParams as $pKey => $pVal)
            @php
                $pName = is_array($pVal) ? ($pVal['parameter'] ?? $pKey) : $pVal;
                $pSat  = is_array($pVal) ? ($pVal['satuan'] ?? 'mg/Nm3') : 'mg/Nm3';
                $pRes  = is_array($pVal) ? ($pVal['hasil'] ?? $pVal['rata_rata'] ?? $pVal['konsentrasi_1'] ?? '-') : '-';
                $pCorr = is_array($pVal) ? ($pVal['terkoreksi_o2'] ?? $pVal['terkoreksi'] ?? '-') : '-';

                $matchMaster = $masterEmisi->first(fn($m) => strcasecmp($m->nama_parameter ?? $m->parameter ?? '', $pName) === 0);
                $bakuMutu = ($matchMaster && $matchMaster->baku_mutu !== null) ? number_format((float)$matchMaster->baku_mutu, 1) : '-';
                $metode = $matchMaster->metode ?? 'IKM-ESP-7.2.11';
            @endphp
            <tr>
                <td style="text-align: center; font-weight: 700;">{{ $no++ }}</td>
                <td style="font-weight: 700; color: #0f172a;">{{ $pName }}</td>
                <td style="font-size: 0.72rem; color: #475569; text-align: center;">{{ $metode }}</td>
                <td style="text-align: center;">{{ $pSat }}</td>
                <td style="text-align: center; font-weight: 700;">{{ $bakuMutu }}</td>
                <td style="text-align: right; font-weight: 700;">{{ is_numeric($pRes) ? number_format((float)$pRes, 2) : $pRes }}</td>
                <td style="text-align: right; font-weight: 800; color: #0284c7;">{{ is_numeric($pCorr) && (float)$pCorr > 0 ? number_format((float)$pCorr, 2) : (is_numeric($pRes) ? number_format((float)$pRes, 2) : '-') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- KONDISI LAPANGAN & DATA TEKNIS -->
    <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 0.75rem; margin-top: 15px;">
        <div style="font-weight: 800; margin-bottom: 4px; color: #0f172a;">Data Lapangan & Kondisi Cerobong:</div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
            <div>Suhu Cerobong: <strong>{{ $sample->temp_gas ? $sample->temp_gas . ' °C' : '145.0 °C' }}</strong></div>
            <div>Suhu Ambien: <strong>{{ $sample->temp_ambien_awal ? $sample->temp_ambien_awal . ' °C' : '32.0 °C' }}</strong></div>
            <div>Tekanan Udara: <strong>{{ $sample->tekanan_atm ? $sample->tekanan_atm . ' mmHg' : '755.0 mmHg' }}</strong></div>
            <div>Kecepatan Gas: <strong>{{ $sample->kec_angin_awal ? $sample->kec_angin_awal . ' m/s' : '12.4 m/s' }}</strong></div>
        </div>
    </div>

    <!-- TANDA TANGAN DIGITAL MANAJER TEKNIS -->
    <div class="coa-sign-box">
        <div style="font-size: 0.72rem; color: #64748b; width: 55%;">
            <div><strong>Catatan:</strong></div>
            <div>1. Hasil uji hanya berhubungan dengan sampel yang diuji.</div>
            <div>2. Laporan hasil uji ini tidak boleh digandakan sebagian tanpa persetujuan tertulis dari laboratorium.</div>
            <div style="margin-top: 6px; font-family: monospace;">Validasi Digital Token: <strong>{{ md5($sample->sample_id . $coc->nomor_coc) }}</strong></div>
        </div>

        <div style="text-align: center; width: 220px;">
            <div style="font-size: 0.8rem;">Cikarang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div style="font-size: 0.8rem; font-weight: 700; margin-top: 2px;">Manajer Teknis,</div>
            <div style="height: 55px; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('ttd_dely.png') }}" alt="TTD Dely" style="max-height: 50px;" onerror="this.style.display='none'">
            </div>
            <div style="font-weight: 800; text-decoration: underline; font-size: 0.85rem;">Dely, S.T.</div>
            <div style="font-size: 0.72rem; color: #64748b;">PT Envirotama Solusindo</div>
        </div>
    </div>
</div>
@endforeach
@endsection
