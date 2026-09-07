@extends('layouts.app')

@section('title', 'Quotation & Penawaran Pengujian')

@section('content')
<style>
    .tab-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 8px;
    }
    .tab-btn {
        padding: 10px 20px;
        border-radius: 12px 12px 0 0;
        font-weight: 700;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        background: transparent;
        color: #64748b;
        transition: all 0.2s;
    }
    .tab-btn.active {
        background: #2563eb;
        color: #ffffff;
    }
    .qt-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .section-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-left: 4px solid #2563eb;
        padding-left: 10px;
    }
    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-group label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-control {
        box-sizing: border-box;
        width: 100%;
        max-width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.88rem;
        outline: none;
        font-family: inherit;
        background: #ffffff;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #2563eb;
    }
    .sample-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .sample-table input,
    .sample-table select {
        box-sizing: border-box;
        width: 100%;
        max-width: 100%;
    }
    .sample-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 10px 8px;
        border: 1px solid #e2e8f0;
        text-align: left;
    }
    .sample-table td {
        padding: 10px 8px;
        border: 1px solid #e2e8f0;
        vertical-align: top;
        font-size: 0.82rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .regulation-select {
        width: 100%;
        max-width: 100%;
        font-size: 0.78rem;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #1e293b;
        outline: none;
    }
    .param-row {
        display: grid;
        grid-template-columns: 20px 1fr 135px;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
        min-height: 28px;
        padding: 3px 6px;
        border-radius: 6px;
        transition: background 0.15s;
        border-bottom: 1px solid #f1f5f9;
    }
    .param-row:hover {
        background: #f8fafc;
    }
    .param-check-col {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .param-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
        margin: 0;
        accent-color: #2563eb;
    }
    .param-title-col {
        font-size: 0.78rem;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        user-select: none;
        line-height: 1.3;
        margin: 0;
        word-break: normal;
        overflow-wrap: break-word;
    }
    .select-metode {
        font-size: 0.72rem;
        height: 26px;
        padding: 1px 6px;
        border-radius: 6px;
        border: 1px solid #93c5fd;
        width: 100%;
        max-width: 135px;
        background: #ffffff;
        color: #1e293b;
        outline: none;
        cursor: pointer;
    }
    .btn-add-row {
        background: #f8fafc;
        color: #2563eb;
        border: 2px dashed #93c5fd;
        padding: 12px 18px;
        width: 100%;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-add-row:hover {
        background: #eff6ff;
        border-color: #2563eb;
    }
    .btn-remove-row {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: 800;
        font-size: 0.9rem;
        transition: background 0.15s;
    }
    .btn-remove-row:hover {
        background: #fecaca;
    }
    .calc-box {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
    }
    .calc-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.88rem;
        color: #475569;
    }
    .calc-row.total {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        border-top: 2px dashed #cbd5e1;
        padding-top: 12px;
        margin-top: 12px;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📑 Quotation & Penawaran Pengujian</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Buat penawaran harga pengujian laboratorium dan otomatis terbitkan dokumen COC Digital siap cetak.</p>
    </div>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; font-size: 0.88rem; display: flex; align-items: center; gap: 10px;">
    <span style="font-size: 1.2rem;">✓</span>
    <div>{{ session('success') }}</div>
</div>
@endif

@if($errors->any())
<div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 0.88rem;">
    <div style="font-weight: 800; margin-bottom: 6px;">⚠️ Terjadi kesalahan input:</div>
    <ul style="margin-left: 20px;">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Tab Navigation -->
<div class="tab-nav">
    <button type="button" class="tab-btn active" id="tabBtnList" onclick="switchQtTab('list')">
        📋 Daftar Penawaran ({{ $orders->total() }})
    </button>
    <button type="button" class="tab-btn" id="tabBtnForm" onclick="switchQtTab('form')">
        ➕ Buat Quotation Baru
    </button>
</div>

<!-- ============================================================ -->
<!-- TAB 1: DAFTAR QUOTATION TERSIMPAN -->
<!-- ============================================================ -->
<div id="tabContentList" class="qt-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route('pengujian.permintaan.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%; max-width: 600px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. QT, Order, PO, Perusahaan..." class="form-control" style="flex: 1; min-width: 250px;">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Cari
            </button>
            @if(request()->has('search'))
            <a href="{{ route('pengujian.permintaan.index') }}" style="padding: 10px 16px; background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none;">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th style="width: 150px;">No. Quotation</th>
                    <th>Nama Pelanggan / Perusahaan</th>
                    <th>Titik Sampling</th>
                    <th>Biaya Sampling & Mop</th>
                    <th>Grand Total</th>
                    <th>Status PO / COC</th>
                    <th style="text-align: center; width: 220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td>
                        <strong style="color: #0284c7; font-family: monospace; font-size: 0.95rem;">{{ $o->no_quotation ?? '-' }}</strong>
                        <div style="font-size: 0.72rem; color: #64748b;">{{ $o->no_order }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #0f172a;">{{ $o->nama_pelanggan }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $o->kontak_person }} ({{ $o->no_hp ?? '-' }})</div>
                    </td>
                    <td>
                        <span class="tag tag-blue">{{ $o->items->count() }} Titik Cerobong</span>
                    </td>
                    <td>
                        <div style="font-size: 0.75rem;">Sampling: <strong>Rp {{ number_format($o->biaya_sampling ?? 0, 0, ',', '.') }}</strong></div>
                        <div style="font-size: 0.75rem; color: #64748b;">Mop/Demop: Rp {{ number_format($o->biaya_mop_demop ?? 0, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <strong style="color: #15803d; font-size: 0.95rem;">Rp {{ number_format($o->grand_total, 0, ',', '.') }}</strong>
                        @if($o->is_ppn)
                            <div style="font-size: 0.7rem; color: #16a34a; font-weight: 700;">(Termasuk PPN 11%)</div>
                        @else
                            <div style="font-size: 0.7rem; color: #64748b;">(Non-PPN)</div>
                        @endif
                    </td>
                    <td>
                        @if($o->no_po)
                            <span class="tag tag-green">✓ PO: {{ $o->no_po }}</span>
                        @else
                            <button onclick="openModalPo({{ $o->id }}, '{{ $o->no_quotation }}')" style="background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; padding: 4px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 700; cursor: pointer;">
                                + Input No. PO
                            </button>
                        @endif

                        @if($o->coc)
                            <div style="margin-top: 4px;">
                                <a href="{{ route('pengujian.coc.show', $o->coc->id) }}" style="font-size: 0.72rem; color: #0284c7; font-weight: 700; text-decoration: none;">
                                    📑 COC: {{ $o->coc->nomor_coc }}
                                </a>
                            </div>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <a href="{{ route('pengujian.permintaan.print', $o->id) }}" target="_blank" style="padding: 6px 12px; background: #0284c7; color: white; border-radius: 8px; font-size: 0.75rem; font-weight: 700; text-decoration: none;" title="Cetak Surat Penawaran Harga">
                                🖨️ Cetak QT
                            </a>
                            @if($o->coc)
                            <a href="{{ route('pengujian.coc.show', $o->coc->id) }}" target="_blank" style="padding: 6px 10px; background: #16a34a; color: white; border-radius: 8px; font-size: 0.75rem; font-weight: 700; text-decoration: none;" title="Cetak COC Form-ES-7.4.1">
                                📄 Form-ES
                            </a>
                            @endif
                            <form action="{{ route('pengujian.permintaan.destroy', $o->id) }}" method="POST" onsubmit="return confirm('Hapus data penawaran ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 10px; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                        Belum ada data Quotation / Penawaran Pengujian. Silakan buat baru melalui tab "➕ Buat Quotation Baru".
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $orders->links() }}
    </div>
</div>

<!-- ============================================================ -->
<!-- TAB 2: BUAT FORM QUOTATION BARU (SESUAI FORMAT COC + HARGA) -->
<!-- ============================================================ -->
<div id="tabContentForm" class="qt-card" style="display: none;">
    <form action="{{ route('pengujian.permintaan.store') }}" method="POST" id="formCreateQt">
        @csrf

        <datalist id="companyList">
            @foreach($companies as $cmp)
                <option value="{{ $cmp->company_name }}" 
                        data-address="{{ $cmp->alamat_perusahaan }}" 
                        data-contact="{{ $cmp->contact_person }}" 
                        data-phone="{{ $cmp->no_telepon }}" 
                        data-email="{{ $cmp->email_coa }}"></option>
            @endforeach
        </datalist>

        <!-- Bagian 1: Informasi Pelanggan & Dokumen -->
        <div class="section-title">
            <span>🏢</span> Bagian 1: Informasi Pelanggan & Nomor Penawaran
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Nomor Quotation (QT) *</label>
                <input type="text" name="no_quotation" id="noQuotation" value="{{ old('no_quotation', $newQuotationNo) }}" class="form-control" required style="font-family: monospace; font-weight: 700; color: #0284c7;">
            </div>
            <div class="form-group">
                <label>Nama Perusahaan / Klien *</label>
                <input type="text" name="nama_pelanggan" id="inputCompanyName" list="companyList" value="{{ old('nama_pelanggan') }}" placeholder="Ketik atau pilih nama klien..." class="form-control" required>
            </div>
            <div class="form-group">
                <label>Contact Person (PIC)</label>
                <input type="text" name="kontak_person" id="inputContactPerson" value="{{ old('kontak_person') }}" placeholder="Nama PIC Perusahaan" class="form-control">
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>No. Telepon / WhatsApp</label>
                <input type="text" name="no_hp" id="inputPhoneNumber" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789" class="form-control">
            </div>
            <div class="form-group">
                <label>Email Pengiriman Dokumen / COA</label>
                <input type="email" name="email" id="inputEmailCoa" value="{{ old('email') }}" placeholder="email@perusahaan.com" class="form-control">
            </div>
            <div class="form-group">
                <label>Jenis Usaha / Sektor Industri</label>
                <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha') }}" placeholder="Contoh: Industri Semen / Kimia / Pembangkit Listrik" class="form-control">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Alamat Lengkap Perusahaan</label>
                <textarea name="alamat_pelanggan" id="inputCompanyAddress" rows="2" placeholder="Alamat lengkap kantor/perusahaan..." class="form-control">{{ old('alamat_pelanggan') }}</textarea>
            </div>
            <div class="form-group">
                <label>Lokasi / Titik Sampling (Site)</label>
                <textarea name="lokasi_sampling" rows="2" placeholder="Alamat pabrik / lokasi titik cerobong..." class="form-control">{{ old('lokasi_sampling') }}</textarea>
            </div>
        </div>

        <!-- Bagian 2: Waktu & Ketentuan Layanan -->
        <div class="section-title" style="margin-top: 25px;">
            <span>📅</span> Bagian 2: Waktu Sampling & Ketentuan Pembayaran
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Tanggal Rencana Sampling *</label>
                <input type="date" name="tanggal_masuk" id="samplingDate" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>TAT / Estimasi Selesai (Hari)</label>
                <input type="number" name="tat_days" id="tatDays" value="{{ old('tat_days', 14) }}" min="1" max="90" class="form-control">
            </div>
            <div class="form-group">
                <label>Tanggal Target Selesai COA</label>
                <input type="date" name="target_selesai" id="tglSelesai" value="{{ old('target_selesai') }}" class="form-control">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Term of Payment (TOP Hari)</label>
                <input type="number" name="top_days" value="{{ old('top_days', 30) }}" min="0" max="180" class="form-control">
            </div>
            <div class="form-group">
                <label>Tipe Layanan Pekerjaan</label>
                <select name="tipe_pekerjaan" class="form-control">
                    <option value="on_site">On Site (Sampling Lapangan oleh Tim Lab)</option>
                    <option value="in_lab">In Lab (Sampel Diantar Mandiri oleh Klien)</option>
                </select>
            </div>
        </div>

        <!-- Bagian 3: Titik Cerobong, Parameter Uji & Harga Satuan -->
        <div class="section-title" style="margin-top: 25px; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span>🧪</span> Bagian 3: Titik Cerobong, Parameter Uji & Harga
            </div>
            <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">(Minimal 1 Titik Cerobong)</span>
        </div>

        <div style="width: 100%;">
            <table class="sample-table" id="tableSamples">
                <thead>
                    <tr>
                        <th style="width: 3%; text-align: center;">No</th>
                        <th style="width: 8%;">Sample ID</th>
                        <th style="width: 14%;">Nama Cerobong *</th>
                        <th style="width: 18%;">Regulasi Baku Mutu</th>
                        <th style="width: 40%;">Selected Parameters & Methods</th>
                        <th style="width: 13%; text-align: right;">Harga Titik (Rp) *</th>
                        <th style="width: 4%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="sampleTableBody">
                    <!-- Baris sampel akan digenerate dinamis melalui JavaScript -->
                </tbody>
            </table>
        </div>

        <button type="button" class="btn-add-row" onclick="addSampleRow()">
            <span>➕</span> Tambah Titik Cerobong / Sampel Baru
        </button>

        <!-- Bagian 4: Biaya Tambahan, Pajak & Kalkulasi Total -->
        <div class="section-title" style="margin-top: 30px;">
            <span>💰</span> Bagian 4: Biaya Sampling, Mobilisasi & Pajak
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Biaya Sampling Lapangan (Rp)</label>
                <input type="text" name="biaya_sampling" id="inputBiayaSampling" value="{{ old('biaya_sampling', '0') }}" class="form-control" oninput="formatRupiahInput(this)" placeholder="Contoh: 1.500.000" style="font-weight: 700;">
            </div>
            <div class="form-group">
                <label>Biaya Mobilisasi / Demobilisasi (Mop / Demop) (Rp)</label>
                <input type="text" name="biaya_mop_demop" id="inputBiayaMop" value="{{ old('biaya_mop_demop', '0') }}" class="form-control" oninput="formatRupiahInput(this)" placeholder="Contoh: 1.000.000" style="font-weight: 700;">
            </div>
            <div class="form-group">
                <label>Diskon / Potongan Harga (Rp)</label>
                <input type="text" name="diskon" id="inputDiskon" value="{{ old('diskon', '0') }}" class="form-control" oninput="formatRupiahInput(this)" placeholder="Contoh: 500.000" style="font-weight: 700; color: #dc2626;">
            </div>
        </div>

        <div style="margin-top: 15px; margin-bottom: 20px; background: #eff6ff; border: 1px solid #bfdbfe; padding: 14px 18px; border-radius: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <label style="font-size: 0.88rem; font-weight: 700; color: #1e40af; display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                <input type="checkbox" name="is_ppn" id="cbIsPpn" value="1" checked onchange="calculateGrandTotal()" style="width: 18px; height: 18px; cursor: pointer;">
                <span>Kenakan Pajak Pertambahan Nilai (PPN 11%)</span>
            </label>
            <span style="font-size: 0.78rem; color: #3b82f6; font-weight: 600;">(Hilangkan centang jika penawaran bersifat Non-PPN)</span>
        </div>

        <!-- Box Ringkasan Total Penawaran -->
        <div class="calc-box">
            <div class="calc-row">
                <span>Subtotal Pengujian Titik Cerobong:</span>
                <strong id="labelSubtotalTitik">Rp 0</strong>
            </div>
            <div class="calc-row">
                <span>Biaya Sampling Lapangan:</span>
                <strong id="labelBiayaSampling">Rp 0</strong>
            </div>
            <div class="calc-row">
                <span>Biaya Mobilisasi / Demobilisasi (Mop/Demop):</span>
                <strong id="labelBiayaMop">Rp 0</strong>
            </div>
            <div class="calc-row" style="color: #dc2626;">
                <span>Diskon / Potongan:</span>
                <strong id="labelDiskon">- Rp 0</strong>
            </div>
            <div class="calc-row" style="border-top: 1px solid #e2e8f0; padding-top: 6px;">
                <span>Dasar Pengenaan Pajak (DPP):</span>
                <strong id="labelDpp">Rp 0</strong>
            </div>
            <div class="calc-row" id="rowPpn">
                <span>PPN (11%):</span>
                <strong id="labelPpn">Rp 0</strong>
            </div>
            <div class="calc-row total">
                <span>GRAND TOTAL PENAWARAN:</span>
                <span id="labelGrandTotal" style="color: #2563eb;">Rp 0</span>
            </div>
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
            <button type="reset" class="tab-btn" onclick="setTimeout(refreshSampleRows, 100)">
                🔄 Reset Form
            </button>
            <button type="submit" class="btn-primary" style="background: #2563eb; color: white; border: none; padding: 14px 30px; border-radius: 12px; font-weight: 800; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                💾 Terbitkan Quotation & Siapkan Dokumen COC
            </button>
        </div>
    </form>
</div>

<!-- Modal Input PO Pelanggan -->
<div id="modalInputPo" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 450px; padding: 25px;">
        <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Konfirmasi PO Pelanggan</h3>
        <p id="labelModalPo" style="font-size: 0.8rem; color: #64748b; margin-bottom: 20px;"></p>

        <form id="formInputPo" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Nomor PO / SPK Pelanggan *</label>
                <input type="text" name="no_po" required placeholder="PO/ENV/2026/..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Upload File PO (PDF/JPG)</label>
                <input type="file" name="file_po" accept=".pdf,.jpg,.jpeg,.png" style="width: 100%; font-size: 0.85rem;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('modalInputPo').style.display='none'" style="padding: 8px 16px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 8px 18px; background: #16a34a; color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                    Simpan PO
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Master Regulations & Parameter Methods Cache for JS -->
<script>
    const masterRegulations = @json($regulations);
    const masterParameters = @json($masterParameters);
    const paramMethodsMap = @json($paramMethodsMap);
    let sampleRowIndex = 0;

    function switchQtTab(tab) {
        const tabList = document.getElementById('tabContentList');
        const tabForm = document.getElementById('tabContentForm');
        const btnList = document.getElementById('tabBtnList');
        const btnForm = document.getElementById('tabBtnForm');

        if (tab === 'list') {
            tabList.style.display = 'block';
            tabForm.style.display = 'none';
            btnList.classList.add('active');
            btnForm.classList.remove('active');
        } else {
            tabList.style.display = 'none';
            tabForm.style.display = 'block';
            btnList.classList.remove('active');
            btnForm.classList.add('active');

            if (document.querySelectorAll('#sampleTableBody tr').length === 0) {
                addSampleRow('Cerobong Genset 1', 2500000);
            }
        }
    }

    document.getElementById('inputCompanyName').addEventListener('input', function(e) {
        const val = e.target.value.trim().toLowerCase();
        const options = document.querySelectorAll('#companyList option');
        for (let opt of options) {
            if (opt.value.trim().toLowerCase() === val) {
                if (opt.dataset.address) document.getElementById('inputCompanyAddress').value = opt.dataset.address;
                if (opt.dataset.contact) document.getElementById('inputContactPerson').value = opt.dataset.contact;
                if (opt.dataset.phone) document.getElementById('inputPhoneNumber').value = opt.dataset.phone;
                if (opt.dataset.email) document.getElementById('inputEmailCoa').value = opt.dataset.email;
                break;
            }
        }
    });

    function calculateTargetDate() {
        const sDate = document.getElementById('samplingDate').value;
        const tat = parseInt(document.getElementById('tatDays').value) || 14;
        if (sDate) {
            const d = new Date(sDate);
            d.setDate(d.getDate() + tat);
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            document.getElementById('tglSelesai').value = `${yyyy}-${mm}-${dd}`;
        }
    }
    document.getElementById('samplingDate').addEventListener('change', calculateTargetDate);
    document.getElementById('tatDays').addEventListener('input', calculateTargetDate);

    function getQtSequence() {
        const qtVal = document.getElementById('noQuotation').value || '';
        const match = qtVal.match(/(\d{4})$/);
        return match ? match[1] : '0001';
    }

    function refreshSampleRows() {
        const rows = document.querySelectorAll('#sampleTableBody tr');
        const seq = getQtSequence();
        rows.forEach((row, idx) => {
            row.querySelector('.row-number').textContent = idx + 1;
            const idInput = row.querySelector('.sample-id-input');
            if (idInput) {
                idInput.value = `${seq}.${idx + 1}`;
            }
        });
        calculateGrandTotal();
    }

    const defaultParams = [
        "Carbon Dioxide (CO2)", "Carbon Monoxide (CO)", "Nitrogen Monoxide (NO)", 
        "Nitrogen Oxide (NOx)", "Num of Traverse Point", "Oxygen (O2)", 
        "Percent of Isokinetic", "Velocity", "Volumetric Flow Rate", 
        "Water Vapor In flue gas", "Nitrogen Dioxide (NO2)", "Opacity", 
        "Particulate", "Sulfur Dioxide (SO2)"
    ];

    function addSampleRow(defaultName = '', defaultPrice = 2500000) {
        sampleRowIndex++;
        const tbody = document.getElementById('sampleTableBody');
        const currentCount = tbody.querySelectorAll('tr').length + 1;
        const seq = getQtSequence();
        const sampleId = `${seq}.${currentCount}`;
        const formattedPrice = typeof defaultPrice === 'number' ? new Intl.NumberFormat('id-ID').format(defaultPrice) : (defaultPrice || '0');

        let regulationOptions = `<option value="">-- Pilih Regulasi Baku Mutu --</option>`;
        masterRegulations.forEach(reg => {
            regulationOptions += `<option value="${escapeHtml(reg)}">${escapeHtml(reg)}</option>`;
        });

        const tr = document.createElement('tr');
        tr.id = `sampleRow_${sampleRowIndex}`;
        tr.innerHTML = `
            <td style="text-align: center; font-weight: 700; color: #64748b;" class="row-number">${currentCount}</td>
            <td>
                <input type="text" name="items[${sampleRowIndex}][sample_id]" value="${sampleId}" class="form-control sample-id-input" style="font-family: monospace; font-weight: 700; color: #0284c7;" required readonly>
            </td>
            <td>
                <input type="text" name="items[${sampleRowIndex}][nama_titik_uji]" value="${escapeHtml(defaultName || `Cerobong Titik ${currentCount}`)}" placeholder="Contoh: Cerobong Genset 1" class="form-control" required>
            </td>
            <td>
                <select name="items[${sampleRowIndex}][regulasi]" class="form-control regulation-select" onchange="onRegulationChanged(${sampleRowIndex}, this.value)">
                    ${regulationOptions}
                </select>
            </td>
            <td>
                <div style="margin-bottom: 6px; padding: 4px 8px; background: #eff6ff; border-radius: 6px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="selectAll_${sampleRowIndex}" onchange="toggleSelectAllRow(this, ${sampleRowIndex})" class="param-checkbox" style="width: 16px; height: 16px; margin: 0; cursor: pointer;">
                    <label for="selectAll_${sampleRowIndex}" style="font-size: 0.76rem; font-weight: 800; color: #1d4ed8; cursor: pointer; user-select: none; margin: 0;">
                        SELECT ALL (PILIH SEMUA)
                    </label>
                </div>
                <div id="paramContainer_${sampleRowIndex}">
                    <!-- Rendered by renderParameterRows -->
                </div>
            </td>
            <td>
                <div style="position: relative; width: 100%;">
                    <input type="text" name="items[${sampleRowIndex}][harga_satuan]" value="${formattedPrice}" class="form-control item-price-input" style="width: 100%; box-sizing: border-box; text-align: right; font-weight: 700; font-size: 0.88rem; color: #0f172a; padding: 8px 10px;" oninput="formatRupiahInput(this)" placeholder="0" required>
                </div>
                <input type="hidden" name="items[${sampleRowIndex}][jumlah_titik]" value="1">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <button type="button" class="btn-remove-row" onclick="removeSampleRow(${sampleRowIndex})" title="Hapus Baris">
                    ✕
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        renderParameterRows(sampleRowIndex, defaultParams);
        refreshSampleRows();
    }

    function removeSampleRow(idx) {
        const row = document.getElementById(`sampleRow_${idx}`);
        if (row) {
            const totalRows = document.querySelectorAll('#sampleTableBody tr').length;
            if (totalRows <= 1) {
                alert('Quotation penawaran minimal harus memiliki 1 titik cerobong / sampel.');
                return;
            }
            row.remove();
            refreshSampleRows();
        }
    }

    function renderParameterRows(rowIndex, paramsList) {
        const container = document.getElementById(`paramContainer_${rowIndex}`);
        if (!container) return;

        let html = '';
        paramsList.forEach(pName => {
            const safeId = pName.replace(/[^a-z0-9]/gi, '-');
            const availableMethods = getMethodsForParam(pName);
            const defaultMethod = availableMethods[0] || 'IKM-ESP-7.2.11';

            html += `
                <div class="param-row">
                    <div class="param-check-col">
                        <input type="checkbox" id="cb_${rowIndex}_${safeId}" class="param-checkbox param-checkbox-${rowIndex}" name="items[${rowIndex}][parameters][]" value="${escapeHtml(pName)}" checked onchange="toggleParamMethodSlot(this, ${rowIndex}, '${escapeHtml(pName)}')">
                    </div>
                    <label for="cb_${rowIndex}_${safeId}" class="param-title-col">${escapeHtml(pName)}</label>
                    <div id="slot-metode-${rowIndex}-${safeId}">
                        <select name="items[${rowIndex}][methods][${escapeHtml(pName)}]" class="select-metode" data-param="${escapeHtml(pName)}">
                            ${availableMethods.map(m => `<option value="${escapeHtml(m)}" ${m === defaultMethod ? 'selected' : ''}>${escapeHtml(m)}</option>`).join('')}
                        </select>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function toggleParamMethodSlot(checkbox, rowIndex, paramName) {
        const safeId = paramName.replace(/[^a-z0-9]/gi, '-');
        const slot = document.getElementById(`slot-metode-${rowIndex}-${safeId}`);
        if (!slot) return;

        if (checkbox.checked) {
            const availableMethods = getMethodsForParam(paramName);
            const defaultMethod = availableMethods[0] || 'IKM-ESP-7.2.11';
            slot.innerHTML = `
                <select name="items[${rowIndex}][methods][${escapeHtml(paramName)}]" class="select-metode" data-param="${escapeHtml(paramName)}">
                    ${availableMethods.map(m => `<option value="${escapeHtml(m)}" ${m === defaultMethod ? 'selected' : ''}>${escapeHtml(m)}</option>`).join('')}
                </select>
            `;
        } else {
            slot.innerHTML = '';
        }
    }

    function toggleSelectAllRow(masterCb, rowIndex) {
        const checkboxes = document.querySelectorAll(`.param-checkbox-${rowIndex}`);
        checkboxes.forEach(cb => {
            cb.checked = masterCb.checked;
            toggleParamMethodSlot(cb, rowIndex, cb.value);
        });
    }

    function getMethodsForParam(paramName) {
        const pLower = paramName.toLowerCase();
        for (let k in paramMethodsMap) {
            if (k.toLowerCase() === pLower || k.toLowerCase().includes(pLower) || pLower.includes(k.toLowerCase())) {
                if (paramMethodsMap[k] && paramMethodsMap[k].length > 0) {
                    return paramMethodsMap[k];
                }
            }
        }

        if (pLower.includes('carbon dioxide') || pLower.includes('co2')) return ['IKM ESP 7.2.5', 'SNI 19-7117.10-2005', 'USEPA Method 3A'];
        if (pLower.includes('carbon monoxide') || pLower.includes('co')) return ['IKM-ESP-7.2.5', 'SNI 19-7117.10-2005', 'USEPA Method 10'];
        if (pLower.includes('nitrogen oxide') || pLower.includes('nox') || pLower.includes('nitrogen dioxide') || pLower.includes('no2')) return ['IKM-ESP-7.2.10', 'SNI 19-7117.10-2005', 'USEPA Method 7E'];
        if (pLower.includes('sulfur dioxide') || pLower.includes('so2')) return ['IKM-ESP-7.2.17', 'SNI 19-7117.10-2005', 'USEPA Method 6C'];
        if (pLower.includes('oxygen') || pLower.includes('o2')) return ['IKM-ESP-7.2.5', 'SNI 19-7117.10-2005', 'USEPA Method 3A'];
        if (pLower.includes('particulate') || pLower.includes('partikulat')) return ['EPA Method 5 Tahun 2020', 'SNI 7117.17:2009', 'SNI 7117.21:2021'];
        if (pLower.includes('traverse point')) return ['EPA Method 1 Tahun 2023', 'SNI 7117.13:2009'];
        if (pLower.includes('velocity') || pLower.includes('volumetric flow')) return ['EPA Method 2 Tahun 2017', 'SNI 7117.14:2009'];
        if (pLower.includes('isokinetic')) return ['EPA Method 5 Tahun 2020', 'SNI 7117.17:2009'];
        if (pLower.includes('water vapor')) return ['EPA Method 4 Tahun 2017', 'SNI 7117.16:2009'];
        if (pLower.includes('opacity') || pLower.includes('opasitas')) return ['SNI 19-7117.11-2005 (Ringelmann)'];

        return ['IKM-ESP-7.2.11', 'SNI 7117.17:2009', 'USEPA Standard'];
    }

    function onRegulationChanged(rowIndex, selectedReg) {
        if (!selectedReg) {
            renderParameterRows(rowIndex, defaultParams);
            return;
        }
        const matched = masterParameters.filter(p => p.regulasi === selectedReg);
        let paramNames = [];
        if (matched.length > 0) {
            matched.forEach(p => {
                const name = p.nama_parameter || p.parameter;
                if (name && !paramNames.includes(name)) paramNames.push(name);
            });
        }
        const standardAdditions = [
            "Carbon Dioxide (CO2)", "Num of Traverse Point", "Oxygen (O2)", 
            "Percent of Isokinetic", "Velocity", "Volumetric Flow Rate", 
            "Water Vapor In flue gas", "Particulate"
        ];
        standardAdditions.forEach(extra => {
            if (!paramNames.some(existing => existing.toLowerCase() === extra.toLowerCase())) {
                paramNames.push(extra);
            }
        });

        renderParameterRows(rowIndex, paramNames.length > 0 ? paramNames : defaultParams);
    }

    function getRawNumber(val) {
        if (!val) return 0;
        if (typeof val === 'number') return val;
        return parseFloat(val.toString().replace(/[^0-9]/g, '')) || 0;
    }

    function formatRupiahInput(el) {
        let raw = el.value.replace(/[^0-9]/g, '');
        if (raw === '') {
            el.value = '';
        } else {
            el.value = new Intl.NumberFormat('id-ID').format(raw);
        }
        calculateGrandTotal();
    }

    // Calculate Grand Total Live
    function calculateGrandTotal() {
        let subtotalTitik = 0;
        const priceInputs = document.querySelectorAll('.item-price-input');
        priceInputs.forEach(input => {
            subtotalTitik += getRawNumber(input.value);
        });

        const biayaSampling = getRawNumber(document.getElementById('inputBiayaSampling').value);
        const biayaMop = getRawNumber(document.getElementById('inputBiayaMop').value);
        const diskon = getRawNumber(document.getElementById('inputDiskon').value);
        const isPpn = document.getElementById('cbIsPpn').checked;

        const dpp = Math.max(0, (subtotalTitik + biayaSampling + biayaMop) - diskon);
        const ppn = isPpn ? (dpp * 0.11) : 0;
        const grandTotal = dpp + ppn;

        document.getElementById('labelSubtotalTitik').textContent = formatRupiah(subtotalTitik);
        document.getElementById('labelBiayaSampling').textContent = formatRupiah(biayaSampling);
        document.getElementById('labelBiayaMop').textContent = formatRupiah(biayaMop);
        document.getElementById('labelDiskon').textContent = '- ' + formatRupiah(diskon);
        document.getElementById('labelDpp').textContent = formatRupiah(dpp);
        document.getElementById('labelPpn').textContent = formatRupiah(ppn);
        document.getElementById('labelGrandTotal').textContent = formatRupiah(grandTotal);

        document.getElementById('rowPpn').style.display = isPpn ? 'flex' : 'none';
    }

    function formatRupiah(number) {
        return 'Rp ' + (new Intl.NumberFormat('id-ID').format(Math.round(number)));
    }

    function openModalPo(id, noQt) {
        document.getElementById('labelModalPo').innerText = 'Quotation: ' + noQt;
        document.getElementById('formInputPo').action = '/pengujian/permintaan/' + id + '/po';
        document.getElementById('modalInputPo').style.display = 'flex';
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateTargetDate();
        calculateGrandTotal();
    });
</script>
@endsection
