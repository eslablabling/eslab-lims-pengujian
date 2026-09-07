<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KWITANSI PEMBAYARAN - {{ $order->no_kwitansi }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 40px; font-size: 13px; color: #1e293b; line-height: 1.6; }
        .kwitansi-border { border: 2px solid #0284c7; padding: 25px; border-radius: 12px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0284c7; padding-bottom: 12px; margin-bottom: 20px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; text-decoration: underline; color: #0f172a; }
        .no-border td { border: none; padding: 8px 0; vertical-align: top; }
        .nominal-box { background: #f1f5f9; border: 2px solid #0284c7; padding: 10px 20px; font-size: 18px; font-weight: bold; color: #0284c7; display: inline-block; border-radius: 8px; }
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

    <div class="kwitansi-border">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo Envirotama" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" style="max-height: 52px; width: auto; object-fit: contain;">
                <div>
                    <h2 style="margin: 0; color: #0284c7; font-size: 16px; font-weight: 800; text-transform: uppercase;">PT ENVIROTAMA SOLUSINDO</h2>
                    <p style="margin: 2px 0; font-size: 11px; color: #64748b; font-weight: 600;">Laboratorium Pengujian Lingkungan Terakreditasi KAN LP-1813-IDN</p>
                </div>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 12px; font-weight: bold; color: #64748b;">No. Kwitansi:</span><br>
                <span style="font-family: monospace; font-weight: bold; font-size: 14px; color: #0284c7;">{{ $order->no_kwitansi }}</span>
            </div>
        </div>

        <div class="title">KWITANSI RESMI</div>

        <table class="no-border" style="width: 100%; margin-bottom: 25px;">
            <tr>
                <td style="width: 25%;"><strong>Telah Diterima Dari</strong></td>
                <td style="width: 75%;">: <strong>{{ $order->nama_pelanggan }}</strong></td>
            </tr>
            <tr>
                <td><strong>Uang Sejumlah</strong></td>
                <td>: <span style="background: #f8fafc; border: 1px dashed #cbd5e1; padding: 4px 8px; border-radius: 4px; font-style: italic;">{{ $terbilang }}</span></td>
            </tr>
            <tr>
                <td><strong>Untuk Pembayaran</strong></td>
                <td>: Jasa Pengujian Laboratorium Lingkungan sesuai Invoice No. <strong>{{ $order->no_invoice }}</strong> (PO No: {{ $order->no_po }})</td>
            </tr>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 30px;">
            <div>
                <div class="nominal-box">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </div>
            </div>
            <div style="text-align: center; width: 250px;">
                <p style="margin: 0;">Cikarang, {{ date('d F Y') }}</p>
                <p style="margin: 0; font-size: 11px; color: #64748b;">PT ENVIROTAMA SOLUSINDO</p>
                <div style="height: 60px;"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $order->direktur_name }}</p>
                <p style="margin: 0; font-size: 11px; color: #64748b;">Finance / Direktur</p>
            </div>
        </div>
    </div>
</body>
</html>
