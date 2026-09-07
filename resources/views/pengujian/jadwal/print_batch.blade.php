<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pekerjaan Sampling Lapangan (Form-ES-7.4.6)</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        * { box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 10.5px; color: #000000; line-height: 1.3; margin: 0; padding: 16px; background: #f8fafc; }

        .no-print { margin-bottom: 20px; text-align: right; }

        .doc-sheet {
            background: #ffffff;
            padding: 24px;
            margin: 0 auto 30px auto;
            max-width: 1100px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        @media print {
            body { background: #ffffff !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .doc-sheet {
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                max-width: 100% !important;
            }
        }

        /* Letterhead Header */
        .kop-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 12px; }
        .kop-left { display: flex; align-items: center; gap: 12px; }
        .kop-logo { height: 48px; width: auto; object-fit: contain; }
        .kop-company { font-weight: 800; font-size: 13px; color: #000000; letter-spacing: 0.2px; text-transform: uppercase; }
        .kop-text { font-weight: 700; font-size: 9.5px; color: #334155; margin-top: 1px; }

        /* Document Title */
        .doc-title { text-align: center; font-size: 14px; font-weight: bold; margin: 12px 0 14px 0; letter-spacing: 0.5px; text-transform: uppercase; }

        /* Schedule Table */
        .schedule-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .schedule-table th, .schedule-table td { border: 1px solid #000000; padding: 6px 8px; vertical-align: top; }
        .schedule-table th { background: #f1f5f9; font-weight: 800; text-align: center; text-transform: uppercase; font-size: 10px; }

        /* Signature section */
        .sign-table { width: 100%; margin-top: 25px; border-collapse: collapse; }
        .sign-table td { text-align: center; vertical-align: top; width: 33.3%; }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 18px; background: #059669; color: #ffffff; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">
            🖨️ Cetak Jadwal (Print PDF)
        </button>
    </div>

    <div class="doc-sheet">
        <!-- Official Kop -->
        <div class="kop-header">
            <div class="kop-left">
                <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo ESLab" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';" class="kop-logo">
                <div>
                    <div class="kop-company">PT ENVIROTAMA SOLUSINDO</div>
                    <div class="kop-text">Laboratorium Pengujian Lingkungan Terakreditasi KAN LP-1813-IDN</div>
                    <div style="font-size: 8.5px; color: #64748b;">Kawasan Industri Delta Silicon, Cikarang Pusat, Bekasi - Jawa Barat</div>
                </div>
            </div>
            <div style="text-align: right; font-size: 9px; font-weight: bold;">
                <div>Form-ES-7.4.6/Rev.02</div>
                <div>Tanggal Cetak: {{ date('d F Y') }}</div>
            </div>
        </div>

        <div class="doc-title">
            JADWAL PENUGASAN SAMPLING & PENGUJIAN LAPANGAN
        </div>

        <table class="schedule-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 14%;">No. PO / Order</th>
                    <th style="width: 22%;">Nama Pelanggan & Alamat Lokasi</th>
                    <th style="width: 20%;">Titik Sampling & Regulasi</th>
                    <th style="width: 12%;">Tgl Rencana</th>
                    <th style="width: 16%;">Petugas Sampler (Tim)</th>
                    <th style="width: 12%;">No. Surat Tugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $idx => $o)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $o->no_po }}</strong>
                        <div style="font-size: 9px; color: #475569;">{{ $o->no_order }}</div>
                    </td>
                    <td>
                        <strong>{{ $o->nama_pelanggan }}</strong>
                        <div style="font-size: 9px; color: #475569; margin-top: 2px;">{{ $o->alamat_pelanggan }}</div>
                    </td>
                    <td>
                        <strong>{{ $o->items->count() }} Titik Cerobong</strong>
                        @foreach($o->items->take(2) as $it)
                            <div style="font-size: 9px; color: #475569;">• {{ $it->nama_sampel }}</div>
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        <strong>{{ $o->jadwal_sampling ? \Carbon\Carbon::parse($o->jadwal_sampling)->translatedFormat('d M Y') : 'Belum Dijadwalkan' }}</strong>
                    </td>
                    <td>
                        <strong>{{ $o->petugas_sampling ?? '-' }}</strong>
                    </td>
                    <td style="text-align: center;">
                        <strong>{{ $o->surat_tugas_no ?? 'Draft' }}</strong>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Tidak ada data jadwal sampling untuk dicetak.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signature Section -->
        <table class="sign-table">
            <tr>
                <td>
                    <div>Dibuat oleh,</div>
                    <div style="margin-top: 4px; font-weight: bold;">Koordinator Sampling</div>
                    <div style="height: 50px;"></div>
                    <div style="font-weight: bold; text-decoration: underline;">( Rian Pratama, S.T. )</div>
                </td>
                <td>
                    <div>Diperiksa oleh,</div>
                    <div style="margin-top: 4px; font-weight: bold;">Manajer Teknis</div>
                    <div style="height: 50px;"></div>
                    <div style="font-weight: bold; text-decoration: underline;">( Ahmad Fauzi, S.Si. )</div>
                </td>
                <td>
                    <div>Mengetahui & Menyetujui,</div>
                    <div style="margin-top: 4px; font-weight: bold;">Manajer Mutu / Lab</div>
                    <div style="height: 50px;"></div>
                    <div style="font-weight: bold; text-decoration: underline;">( Dr. Ir. Budi Santoso, M.Env. )</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
