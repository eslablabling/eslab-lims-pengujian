<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas Sampling - {{ $order->surat_tugas_no }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 30px; font-size: 13px; color: #1e293b; line-height: 1.6; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0284c7; padding-bottom: 15px; margin-bottom: 20px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; text-decoration: underline; }
        .no-border td { border: none; padding: 6px 0; vertical-align: top; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 18px; background: #0284c7; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo Envirotama" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" style="max-height: 52px; width: auto; object-fit: contain;">
            <div>
                <h2 style="margin: 0; color: #0284c7; font-size: 16px; font-weight: 800; text-transform: uppercase;">PT ENVIROTAMA SOLUSINDO</h2>
                <p style="margin: 2px 0; font-size: 11px; color: #64748b; font-weight: 600;">Laboratorium Pengujian Lingkungan Terakreditasi KAN LP-1813-IDN</p>
                <p style="margin: 0; font-size: 10px; color: #94a3b8;">Jl. Raya Lingkungan No. 45, Cibubur, Jakarta Timur | Telp: (021) 8459-0012</p>
            </div>
        </div>
        <div style="text-align: right;">
            <span style="font-family: monospace; font-weight: bold; color: #0284c7; font-size: 13px;">{{ $order->surat_tugas_no }}</span>
        </div>
    </div>

    <div class="title">SURAT PERINTAH TUGAS SAMPLING & SPPD</div>
    <div style="text-align: center; font-size: 12px; margin-top: -15px; margin-bottom: 25px; color: #64748b;">
        Nomor: {{ $order->surat_tugas_no }}
    </div>

    <p>Yang bertanda tangan di bawah ini:</p>
    <table class="no-border" style="margin-left: 20px; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%;"><strong>Nama</strong></td>
            <td>: {{ $order->lab_manager_name }}</td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td>: Manager Teknis / Operasional Laboratorium Lingkungan</td>
        </tr>
        <tr>
            <td><strong>Instansi</strong></td>
            <td>: PT Envirotama Solusindo (LP-1813-IDN)</td>
        </tr>
    </table>

    <p>Dengan ini memberikan tugas kepada:</p>
    <table class="no-border" style="margin-left: 20px; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%;"><strong>Petugas Sampling</strong></td>
            <td>: <strong>{{ $order->petugas_sampling }}</strong></td>
        </tr>
        <tr>
            <td><strong>Perusahaan / Klien</strong></td>
            <td>: {{ $order->nama_pelanggan }}</td>
        </tr>
        <tr>
            <td><strong>No. PO / SPK</strong></td>
            <td>: {{ $order->no_po }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Pelaksanaan</strong></td>
            <td>: {{ $order->jadwal_sampling ? \Carbon\Carbon::parse($order->jadwal_sampling)->translatedFormat('l, d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Lokasi Sampling</strong></td>
            <td>: {{ $order->lokasi_sampling ?? $order->alamat_pelanggan }}</td>
        </tr>
    </table>

    <p>Untuk melaksanakan pekerjaan pengambilan sampel lingkungan (Sampling Emisi / Ambien / Air) sesuai dengan Standar Prosedur Operasional (SOP) dan metode acuan standar SNI / USEPA terakreditasi KAN.</p>

    <div style="display: flex; justify-content: space-between; margin-top: 60px;">
        <div style="width: 40%; text-align: center;">
            <p>Petugas Pelaksana,</p>
            <div style="height: 60px;"></div>
            <p><strong>( {{ $order->petugas_sampling }} )</strong></p>
        </div>
        <div style="width: 40%; text-align: center;">
            <p>Diterbitkan di Cikarang,<br>Manager Teknis,</p>
            <div style="height: 60px;"></div>
            <p><strong>{{ $order->lab_manager_name }}</strong></p>
        </div>
    </div>
</body>
</html>
