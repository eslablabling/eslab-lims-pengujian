<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penawaran Harga - {{ $order->no_quotation }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9pt;
            color: #0f172a;
            line-height: 1.4;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2.5pt solid #0284c7;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-logo img {
            height: 50px;
        }
        .header-title {
            text-align: right;
        }
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            border-bottom: 1pt solid #cbd5e1;
            padding-bottom: 4px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 8.5pt;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8pt;
        }
        .table-data th {
            border: 1pt solid #475569;
            background-color: #f1f5f9;
            padding: 6px 8px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
        }
        .table-data td {
            border: 1pt solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: top;
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .terms-box {
            background: #f8fafc;
            border: 1pt solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            font-size: 8pt;
            margin-bottom: 20px;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
            text-align: center;
            font-size: 8.5pt;
            page-break-inside: avoid;
        }
        .sig-box {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right; background: #f1f5f9; padding: 10px; border-radius: 8px;">
        <button onclick="window.print()" style="padding: 8px 18px; background: #0284c7; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 13px;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <!-- Header / Kop Surat -->
    <div class="header">
        <div class="header-logo" style="display: flex; align-items: center; gap: 15px;">
            <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo Envirotama" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" style="max-height: 55px; width: auto; object-fit: contain;">
            <div>
                <h2 style="margin: 0; color: #0284c7; font-size: 14pt; font-weight: 800; text-transform: uppercase;">PT ENVIROTAMA SOLUSINDO</h2>
                <div style="font-size: 8pt; color: #475569; font-weight: bold; margin-top: 2px;">
                    Laboratorium Pengujian Lingkungan Terakreditasi KAN LP-1813-IDN
                </div>
                <div style="font-size: 7.5pt; color: #64748b;">
                    Jl. Raya Lingkungan No. 45, Cibubur, Jakarta Timur | Telp: (021) 8459-0012 | Email: info@envirotama.id
                </div>
            </div>
        </div>
        <div class="header-title" style="display: flex; flex-direction: column; align-items: flex-end;">
            <div style="font-size: 11pt; font-weight: 800; color: #0f172a; text-transform: uppercase;">SURAT PENAWARAN HARGA</div>
            <div style="font-family: monospace; font-size: 10pt; font-weight: bold; color: #0284c7; margin-top: 3px;">
                {{ $order->no_quotation }}
            </div>
            <div style="font-size: 7.5pt; color: #64748b; margin-top: 2px;">No. Order: {{ $order->no_order }}</div>
        </div>
    </div>

    <!-- Informasi Pelanggan -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%;"><strong>Kepada Yth.</strong></td>
            <td style="width: 45%;">: <strong>{{ $order->nama_pelanggan }}</strong></td>
            <td style="width: 15%;"><strong>Tanggal Penawaran</strong></td>
            <td style="width: 25%;">: {{ $order->tanggal_masuk ? \Carbon\Carbon::parse($order->tanggal_masuk)->translatedFormat('d F Y') : date('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Alamat Perusahaan</strong></td>
            <td>: {{ $order->alamat_pelanggan ?? '-' }}</td>
            <td><strong>Target Selesai COA</strong></td>
            <td>: {{ $order->target_selesai ? \Carbon\Carbon::parse($order->target_selesai)->translatedFormat('d F Y') : ($order->tat_days ?? 14) . ' Hari Kerja' }}</td>
        </tr>
        <tr>
            <td><strong>Attn (PIC)</strong></td>
            <td>: {{ $order->kontak_person ?? '-' }} ({{ $order->no_hp ?? '-' }})</td>
            <td><strong>Term of Payment (TOP)</strong></td>
            <td>: {{ $order->top_days ?? 30 }} Hari setelah invoice terbit</td>
        </tr>
        <tr>
            <td><strong>Lokasi / Site</strong></td>
            <td>: {{ $order->lokasi_sampling ?? ($order->alamat_pelanggan ?? 'Sesuai Lokasi Pelanggan') }}</td>
            <td><strong>Tipe Layanan</strong></td>
            <td>: {{ $order->tipe_pekerjaan === 'on_site' ? 'On Site (Sampling Lapangan)' : 'In Lab (Sampel Diantar)' }}</td>
        </tr>
    </table>

    <div class="title">RINCIAN BIAYA PENGUJIAN LINGKUNGAN</div>

    <!-- Tabel Rincian Titik & Parameter -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 12%;">Sample ID</th>
                <th style="width: 22%;">Titik Uji / Deskripsi</th>
                <th style="width: 44%;">Regulasi & Parameter Uji yang Diuji</th>
                <th style="width: 18%; text-align: right;">Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $idx => $it)
            @php
                $paramList = [];
                if (is_array($it->parameter_uji_json)) {
                    foreach ($it->parameter_uji_json as $p) {
                        if (is_array($p)) {
                            $pStr = $p['parameter'] ?? '';
                            if (isset($p['metode']) && $p['metode']) $pStr .= " (" . $p['metode'] . ")";
                            $paramList[] = $pStr;
                        } else {
                            $paramList[] = (string)$p;
                        }
                    }
                }
            @endphp
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td><strong style="font-family: monospace; color: #0284c7;">{{ $it->sample_id ?? ($order->coc ? ($order->coc->nomor_coc . '.' . ($idx + 1)) : ('SMP-' . ($idx + 1))) }}</strong></td>
                <td>
                    <strong>{{ $it->nama_titik_uji }}</strong>
                    <div style="font-size: 7pt; color: #64748b;">{{ $it->kategori_uji }}</div>
                </td>
                <td>
                    @if($it->regulasi)
                        <div style="font-size: 7.5pt; font-weight: bold; color: #0284c7; margin-bottom: 3px;">
                            📋 {{ $it->regulasi }}
                        </div>
                    @endif
                    <div style="font-size: 7.5pt; color: #334155;">
                        {{ !empty($paramList) ? implode(', ', $paramList) : 'Parameter baku mutu standar emisi' }}
                    </div>
                </td>
                <td class="text-right">
                    <strong>Rp {{ number_format($it->subtotal, 0, ',', '.') }}</strong>
                </td>
            </tr>
            @endforeach

            <!-- Subtotals & Additional Commercial Fees -->
            <tr>
                <td colspan="4" class="text-right"><strong>Subtotal Pengujian Titik Cerobong</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($order->total_harga_titik, 0, ',', '.') }}</strong></td>
            </tr>
            @if($order->biaya_sampling > 0)
            <tr>
                <td colspan="4" class="text-right">Biaya Sampling Lapangan (Personnel & Equipment)</td>
                <td class="text-right">Rp {{ number_format($order->biaya_sampling, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($order->biaya_mop_demop > 0)
            <tr>
                <td colspan="4" class="text-right">Biaya Mobilisasi & Demobilisasi (Mop / Demop)</td>
                <td class="text-right">Rp {{ number_format($order->biaya_mop_demop, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($order->diskon > 0)
            <tr>
                <td colspan="4" class="text-right" style="color: #dc2626;">Potongan Harga / Diskon Khusus</td>
                <td class="text-right" style="color: #dc2626;">- Rp {{ number_format($order->diskon, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="4" class="text-right"><strong>Dasar Pengenaan Pajak (DPP)</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($order->subtotal_dpp, 0, ',', '.') }}</strong></td>
            </tr>
            @if($order->is_ppn)
            <tr>
                <td colspan="4" class="text-right"><strong>PPN 11%</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($order->ppn_amount, 0, ',', '.') }}</strong></td>
            </tr>
            @else
            <tr>
                <td colspan="4" class="text-right" style="color: #64748b;"><em>Pajak Pertambahan Nilai (Non-PPN)</em></td>
                <td class="text-right" style="color: #64748b;">Rp 0</td>
            </tr>
            @endif
            <tr style="background: #f8fafc;">
                <td colspan="4" class="text-right"><strong style="font-size: 9.5pt; color: #0f172a;">GRAND TOTAL PENAWARAN HARGA</strong></td>
                <td class="text-right"><strong style="font-size: 10pt; color: #0284c7;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Syarat & Ketentuan -->
    <div class="terms-box">
        <strong>Syarat dan Ketentuan Penawaran:</strong>
        <ol style="margin: 4px 0 0 15px; padding: 0;">
            <li>Penawaran harga ini berlaku selama 30 (tiga puluh) hari kalender sejak tanggal penerbitan.</li>
            <li>Hasil pengujian akan diterbitkan dalam bentuk <em>Certificate of Analysis (COA)</em> resmi terakreditasi KAN LP-1813-IDN setelah proses analisa selesai.</li>
            <li>Pembayaran ditransfer ke rekening resmi: <strong>Bank Mandiri KCP Jakarta No. Rek: 123-00-9876543-2 a.n. PT Envirotama Solusindo</strong>.</li>
            <li>Konfirmasi persetujuan penawaran dapat dilakukan dengan menerbitkan Purchase Order (PO) atau menandatangani lembar penawaran ini.</li>
        </ol>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-grid">
        <div>
            <div>Disetujui dan Diterima Oleh (Pelanggan),</div>
            <div class="sig-box"></div>
            <div style="font-weight: bold; border-top: 1pt dotted #94a3b8; display: inline-block; padding-top: 3px; min-width: 160px;">
                {{ $order->kontak_person ?? '( Nama PIC Pelanggan )' }}
            </div>
            <div style="font-size: 7.5pt; color: #64748b;">{{ $order->nama_pelanggan }}</div>
        </div>
        <div>
            <div>Hormat Kami,</div>
            <div class="sig-box">
                <span style="color: #0284c7; font-weight: bold; font-family: monospace; font-size: 8pt; border: 1pt dashed #93c5fd; padding: 4px 8px; border-radius: 4px;">
                    DIGITALLY SIGNED<br>PT ENVIROTAMA SOLUSINDO
                </span>
            </div>
            <div style="font-weight: bold; border-top: 1pt dotted #94a3b8; display: inline-block; padding-top: 3px; min-width: 160px;">
                {{ $order->direktur_name ?? 'Fany Kusuma Hadi' }}
            </div>
            <div style="font-size: 7.5pt; color: #64748b;">Direktur Utama / Commercial Lead</div>
        </div>
    </div>
</body>
</html>
