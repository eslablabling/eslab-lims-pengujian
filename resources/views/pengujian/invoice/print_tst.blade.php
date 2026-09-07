<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Serah Terima (TST) - {{ $order->no_tst }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 30px; font-size: 13px; color: #1e293b; line-height: 1.6; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0284c7; padding-bottom: 15px; margin-bottom: 20px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; text-decoration: underline; }
        .no-border td { border: none; padding: 6px 0; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        th { background: #f1f5f9; }
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
            </div>
        </div>
        <div style="text-align: right;">
            <span style="font-family: monospace; font-weight: bold; color: #0284c7; font-size: 13px;">{{ $order->no_tst }}</span>
        </div>
    </div>

    <div class="title">TANDA SERAH TERIMA DOKUMEN & SERTIFIKAT (TST)</div>

    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td style="width: 25%;"><strong>Nama Pelanggan</strong></td>
            <td>: <strong>{{ $order->nama_pelanggan }}</strong></td>
        </tr>
        <tr>
            <td><strong>No. PO / SPK</strong></td>
            <td>: {{ $order->no_po }}</td>
        </tr>
        <tr>
            <td><strong>No. Invoice</strong></td>
            <td>: {{ $order->no_invoice }}</td>
        </tr>
    </table>

    <p>Daftar dokumen & e-sertifikat yang diserahterimakan:</p>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 55%;">Jenis Dokumen / Sertifikat</th>
                <th style="width: 20%;">Jumlah</th>
                <th style="width: 20%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Sertifikat Hasil Uji / Certificate of Analysis (CoA Asli)</td>
                <td>{{ $order->items->count() }} Lembar</td>
                <td>Terverifikasi KAN</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Faktur Tagihan / Invoice Asli (No: {{ $order->no_invoice }})</td>
                <td>1 Rangkap</td>
                <td>Asli + Pajak</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Kwitansi Pembayaran (No: {{ $order->no_kwitansi }})</td>
                <td>1 Lembar</td>
                <td>Resmi</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Berita Acara Serah Terima (BAST)</td>
                <td>2 Rangkap</td>
                <td>Tandatangan Klien</td>
            </tr>
        </tbody>
    </table>

    <div style="display: flex; justify-content: space-between; margin-top: 50px;">
        <div style="width: 40%; text-align: center;">
            <p>Yang Menerima (Klien),</p>
            <div style="height: 60px;"></div>
            <p><strong>( {{ $order->kontak_person ?? 'Nama Penerima' }} )</strong></p>
        </div>
        <div style="width: 40%; text-align: center;">
            <p>Yang Menyerahkan,<br><strong>PT ENVIROTAMA SOLUSINDO</strong></p>
            <div style="height: 60px;"></div>
            <p><strong>( Administrasi Lab )</strong></p>
        </div>
    </div>
</body>
</html>
