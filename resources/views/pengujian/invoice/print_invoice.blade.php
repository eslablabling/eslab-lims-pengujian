<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FAKTUR TAGIHAN (INVOICE) - {{ $order->no_invoice }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 30px; font-size: 13px; color: #1e293b; line-height: 1.5; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0284c7; padding-bottom: 15px; margin-bottom: 20px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; }
        th { background: #f1f5f9; font-weight: bold; }
        .no-border td { border: none; padding: 4px 0; }
        .text-right { text-align: right; }
        .terbilang-box { background: #f8fafc; border: 1px dashed #cbd5e1; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-style: italic; }
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
                <p style="margin: 2px 0; font-size: 10px; color: #94a3b8;">Kawasan Industri Cikarang • Email: finance@envirotama.id</p>
            </div>
        </div>
        <div style="text-align: right;">
            <strong style="font-size: 15px; color: #0f172a;">FAKTUR TAGIHAN (INVOICE)</strong><br>
            <span style="font-family: monospace; font-weight: bold; font-size: 14px; color: #0284c7;">{{ $order->no_invoice }}</span>
        </div>
    </div>

    <table class="no-border" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 15%;"><strong>Ditujukan Kepada</strong></td>
            <td style="width: 45%;">: <strong>{{ $order->nama_pelanggan }}</strong></td>
            <td style="width: 15%;"><strong>Tanggal Invoice</strong></td>
            <td style="width: 25%;">: {{ $order->verified_finance_at ? \Carbon\Carbon::parse($order->verified_finance_at)->translatedFormat('d F Y') : date('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Alamat</strong></td>
            <td>: {{ $order->alamat_pelanggan ?? '-' }}</td>
            <td><strong>Jatuh Tempo</strong></td>
            <td>: {{ \Carbon\Carbon::parse($order->verified_finance_at ?? now())->addDays($order->top_days)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>No. PO / SPK</strong></td>
            <td>: {{ $order->no_po }}</td>
            <td><strong>Term of Payment</strong></td>
            <td>: {{ $order->top_days }} Hari</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 45%;">Deskripsi Pekerjaan Pengujian</th>
                <th style="width: 20%;">Kategori Uji</th>
                <th style="width: 10%; text-align: center;">Jumlah Titik</th>
                <th style="width: 20%; text-align: right;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $idx => $it)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td><strong>{{ $it->nama_titik_uji }}</strong></td>
                <td>{{ $it->kategori_uji }}</td>
                <td style="text-align: center;">{{ $it->jumlah_titik }} Titik</td>
                <td class="text-right">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right"><strong>Subtotal</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>PPN 11%</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($order->ppn_amount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr style="background: #f8fafc;">
                <td colspan="4" class="text-right"><strong style="font-size: 14px;">TOTAL TAGIHAN</strong></td>
                <td class="text-right"><strong style="font-size: 14px; color: #0284c7;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="terbilang-box">
        <strong>Terbilang:</strong> {{ $terbilang }}
    </div>

    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <div style="width: 55%; font-size: 12px; line-height: 1.6;">
            <strong>Pembayaran dapat ditransfer melalui:</strong><br>
            Bank Mandiri KCP Cikarang<br>
            No. Rekening: <strong>156-00-1234567-8</strong><br>
            Atas Nama: <strong>PT ENVIROTAMA SOLUSINDO</strong>
        </div>
        <div style="width: 40%; text-align: center;">
            <p>Hormat Kami,<br><strong>PT ENVIROTAMA SOLUSINDO</strong></p>
            <div style="height: 60px;"></div>
            <p><strong>{{ $order->direktur_name }}</strong></p>
            <p style="font-size: 11px; color: #64748b;">Direktur</p>
        </div>
    </div>
</body>
</html>
