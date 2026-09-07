<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Lengkap Dokumen Keuangan - {{ $order->no_invoice }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 30px; font-size: 13px; color: #1e293b; line-height: 1.5; }
        .page { page-break-after: always; padding: 20px 0; }
        .page:last-child { page-break-after: avoid; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0284c7; padding-bottom: 15px; margin-bottom: 20px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; }
        th { background: #f1f5f9; font-weight: bold; }
        .no-border td { border: none; padding: 4px 0; }
        .text-right { text-align: right; }
        .terbilang-box { background: #f8fafc; border: 1px dashed #cbd5e1; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-style: italic; }
        .kwitansi-border { border: 2px solid #0284c7; padding: 25px; border-radius: 12px; }
        .nominal-box { background: #f1f5f9; border: 2px solid #0284c7; padding: 10px 20px; font-size: 18px; font-weight: bold; color: #0284c7; display: inline-block; border-radius: 8px; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 22px; background: #0284c7; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px;">
            🖨️ Cetak Seluruh Paket Dokumen (Print All)
        </button>
    </div>

    <!-- HALAMAN 1: INVOICE -->
    <div class="page">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo Envirotama" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" style="max-height: 50px; width: auto; object-fit: contain;">
                <div>
                    <h2 style="margin: 0; color: #0284c7; font-size: 16px; font-weight: 800; text-transform: uppercase;">PT ENVIROTAMA SOLUSINDO</h2>
                    <p style="margin: 2px 0; font-size: 11px; color: #64748b; font-weight: 600;">Laboratorium Pengujian Lingkungan Terakreditasi KAN LP-1813-IDN</p>
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
    </div>

    <!-- HALAMAN 2: KWITANSI -->
    <div class="page">
        <div class="kwitansi-border">
            <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo Envirotama" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" style="max-height: 50px; width: auto; object-fit: contain;">
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
    </div>

    <!-- HALAMAN 3: BAST -->
    <div class="page">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo Envirotama" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" style="max-height: 50px; width: auto; object-fit: contain;">
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
    </div>

    <!-- HALAMAN 4: TST -->
    <div class="page">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo Envirotama" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" style="max-height: 50px; width: auto; object-fit: contain;">
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
    </div>
</body>
</html>
