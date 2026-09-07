<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Serah Terima (BAST) - {{ $order->no_bast }}</title>
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
            </div>
        </div>
        <div style="text-align: right;">
            <span style="font-family: monospace; font-weight: bold; color: #0284c7; font-size: 13px;">{{ $order->no_bast }}</span>
        </div>
    </div>

    <div class="title">BERITA ACARA SERAH TERIMA PEKERJAAN (BAST)</div>

    <p>Pada hari ini, bertempat di Cikarang, yang bertanda tangan di bawah ini:</p>
    <table class="no-border" style="margin-left: 20px; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%;"><strong>PIHAK PERTAMA</strong></td>
            <td>: <strong>PT ENVIROTAMA SOLUSINDO</strong> (Penyedia Jasa Pengujian)</td>
        </tr>
        <tr>
            <td style="width: 25%;"><strong>PIHAK KEDUA</strong></td>
            <td>: <strong>{{ $order->nama_pelanggan }}</strong> (Pemberi Kerja)</td>
        </tr>
    </table>

    <p>Menyatakan bahwa <strong>PIHAK PERTAMA</strong> telah menyelesaikan dan menyerahkan hasil pekerjaan pengujian laboratorium lingkungan kepada <strong>PIHAK KEDUA</strong> berdasarkan Purchase Order No: <strong>{{ $order->no_po }}</strong> dengan rincian titik uji sebanyak <strong>{{ $order->items->count() }} titik sampling</strong> dalam kondisi baik dan lengkap.</p>

    <p>Demikian Berita Acara Serah Terima ini dibuat dengan sebenarnya dalam rangkap 2 (dua) untuk dipergunakan sebagaimana mestinya.</p>

    <div style="display: flex; justify-content: space-between; margin-top: 60px;">
        <div style="width: 40%; text-align: center;">
            <p>PIHAK KEDUA,<br><strong>{{ $order->nama_pelanggan }}</strong></p>
            <div style="height: 60px;"></div>
            <p><strong>( {{ $order->kontak_person ?? 'Nama Penerima' }} )</strong></p>
        </div>
        <div style="width: 40%; text-align: center;">
            <p>PIHAK PERTAMA,<br><strong>PT ENVIROTAMA SOLUSINDO</strong></p>
            <div style="height: 60px;"></div>
            <p><strong>{{ $order->direktur_name }}</strong></p>
            <p style="font-size: 11px; color: #64748b;">Direktur</p>
        </div>
    </div>
</body>
</html>
