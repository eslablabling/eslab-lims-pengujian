@extends('layouts.app')

@section('title', 'Chain of Custody (COC Digital)')

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
    .coc-card {
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
    .btn-act {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    .btn-act:hover {
        opacity: 0.85;
    }
    .btn-act-show { background: #0284c7; color: #ffffff; }
    .btn-act-edit { background: #f59e0b; color: #ffffff; }
    .btn-act-dup  { background: #8b5cf6; color: #ffffff; }
    .btn-act-del  { background: #ef4444; color: #ffffff; }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📑 Chain of Custody (COC Digital)</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Dokumen rantai lacak pengambilan sampel emisi (Form-ES-7.4.1) otomatis diterbitkan saat Anda membuat Quotation & Penawaran.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('pengujian.permintaan.index') }}" class="btn-primary" style="background: #0284c7; color: white; border: none; padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            ➕ Buat Quotation & Penawaran Baru
        </a>
    </div>
</div>

<div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 12px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 0.82rem; display: flex; align-items: center; gap: 10px;">
    <span style="font-size: 1.1rem;">💡</span>
    <div>
        <strong>Alur Otomatis:</strong> Anda tidak perlu mengisi ulang form COC secara manual. Saat Quotation dibuat di menu <strong>Quotation & Penawaran</strong>, dokumen COC dan titik sampel otomatis diterbitkan dan siap langsung dicetak di bawah ini.
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
    <button type="button" class="tab-btn active" id="tabBtnList" onclick="switchCocTab('list')">
        📋 Daftar COC Siap Cetak ({{ $cocs->total() }})
    </button>
    <button type="button" class="tab-btn" id="tabBtnForm" onclick="switchCocTab('form')">
        ➕ Form Input Manual / Impor Quotation
    </button>
</div>

<!-- TAB 1: DAFTAR COC TERSIMPAN -->
<div id="tabContentList" class="coc-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.index' : 'coc.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%; max-width: 800px;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. COC, Perusahaan, Kontak, Petugas..." class="form-control" style="flex: 2; min-width: 200px;">
            <input type="date" name="date_start" value="{{ $dateStart }}" class="form-control" style="flex: 1; min-width: 130px;" title="Dari Tanggal">
            <input type="date" name="date_end" value="{{ $dateEnd }}" class="form-control" style="flex: 1; min-width: 130px;" title="Sampai Tanggal">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Filter
            </button>
            @if($search || $dateStart || $dateEnd)
            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.index' : 'coc.index') }}" style="padding: 10px 16px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center;">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th style="width: 160px;">No. COC</th>
                    <th>Nama Perusahaan & Alamat</th>
                    <th>Kontak Person</th>
                    <th>Tgl. Sampling</th>
                    <th>Petugas</th>
                    <th>Titik Sampel</th>
                    <th>Status</th>
                    <th style="text-align: center; width: 220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cocs as $c)
                <tr>
                    <td>
                        <strong style="color: #0284c7; font-family: monospace; font-size: 0.95rem;">{{ $c->nomor_coc }}</strong>
                        @if($c->nomor_qt)
                            <div style="font-size: 0.75rem; color: #64748b;">QT: {{ $c->nomor_qt }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #0f172a;">{{ $c->company_name }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ Str::limit($c->alamat_perusahaan ?? $c->sampling_location, 40) }}</div>
                    </td>
                    <td>
                        <div>{{ $c->contact_person ?? '-' }}</div>
                        @if($c->no_telepon)
                            <div style="font-size: 0.75rem; color: #16a34a;">📞 {{ $c->no_telepon }}</div>
                        @endif
                    </td>
                    <td>
                        {{ $c->sampling_date ? \Carbon\Carbon::parse($c->sampling_date)->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td>
                        {{ $c->sampling_officer ?? '-' }}
                    </td>
                    <td>
                        <span class="tag tag-blue">{{ $c->samples->count() }} Cerobong</span>
                    </td>
                    <td>
                        @if($c->status === 'Verified')
                            <span class="tag tag-green">✓ Verified</span>
                        @elseif($c->status === 'Analisa')
                            <span class="tag tag-orange">Analisa Lab</span>
                        @elseif($c->status === 'Sampling')
                            <span class="tag tag-blue">Sampling</span>
                        @else
                            <span class="tag" style="background: #f1f5f9; color: #64748b;">{{ $c->status ?? 'Draft' }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 4px; justify-content: center; flex-wrap: wrap;">
                            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.show' : 'coc.show', $c->id) }}" class="btn-act btn-act-show" title="Lihat & Cetak Form-ES-7.4.1">
                                🖨️ Cetak
                            </a>
                            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.edit' : 'coc.edit', $c->id) }}" class="btn-act btn-act-edit" title="Edit COC">
                                ✏️ Edit
                            </a>
                            <form action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.duplicate' : 'coc.duplicate', $c->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Duplikasi dokumen COC ini sebagai draft baru?')">
                                @csrf
                                <button type="submit" class="btn-act btn-act-dup" title="Duplikasi">
                                    📑 Salin
                                </button>
                            </form>
                            <form action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.destroy' : 'coc.destroy', $c->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus dokumen COC ini beserta seluruh sampelnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-act btn-act-del" title="Hapus">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                        Belum ada dokumen Chain of Custody (COC). Silakan buat baru melalui tab "➕ Buat Form COC Baru".
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $cocs->links() }}
    </div>
</div>

<!-- TAB 2: BUAT FORM COC BARU -->
<div id="tabContentForm" class="coc-card" style="display: none;">
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px; padding: 16px 20px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
        <div>
            <strong style="color: #166534; font-size: 0.9rem;">⚡ Tarik Data Otomatis dari Quotation / Penawaran:</strong>
            <p style="font-size: 0.78rem; color: #15803d; margin-top: 2px;">Pilih nomor Quotation untuk mengisi otomatis seluruh data perusahaan, titik cerobong, regulasi, parameter & metodenya dalam 1 klik.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; min-width: 300px; flex: 1; max-width: 450px;">
            <select id="selectQuotationImport" class="form-control" onchange="importQuotationData(this.value)" style="border-color: #86efac;">
                <option value="">-- Pilih Nomor Quotation / Penawaran --</option>
                @foreach($quotations as $q)
                    <option value="{{ $q->id }}">{{ $q->no_quotation }} - {{ $q->nama_pelanggan }} ({{ $q->items->count() }} Titik)</option>
                @endforeach
            </select>
        </div>
    </div>

    <form action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.store' : 'coc.store') }}" method="POST" id="formCreateCoc">
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

        <datalist id="officerList">
            @foreach($officers as $off)
                <option value="{{ $off }}"></option>
            @endforeach
        </datalist>

        <!-- Bagian 1: Informasi Dokumen & Pelanggan -->
        <div class="section-title">
            <span>🏢</span> Bagian 1: Informasi Pelanggan & Dokumen
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Nomor COC (Form-ES-7.4.1) *</label>
                <input type="text" name="nomor_coc" id="nomorCoc" value="{{ old('nomor_coc', $newCocNumber) }}" class="form-control" required style="font-family: monospace; font-weight: 700; color: #0284c7;">
            </div>
            <div class="form-group">
                <label>Nomor Quotation (QT)</label>
                <input type="text" name="nomor_qt" id="inputNomorQt" value="{{ old('nomor_qt') }}" placeholder="Contoh: QT-ENV/2026/0001" class="form-control">
            </div>
            <div class="form-group">
                <label>Nama Perusahaan / Klien *</label>
                <input type="text" name="company_name" id="inputCompanyName" list="companyList" value="{{ old('company_name') }}" placeholder="Ketik atau pilih nama klien..." class="form-control" required>
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Contact Person (PIC)</label>
                <input type="text" name="contact_person" id="inputContactPerson" value="{{ old('contact_person') }}" placeholder="Nama PIC Perusahaan" class="form-control">
            </div>
            <div class="form-group">
                <label>No. Telepon / WhatsApp</label>
                <input type="text" name="no_telepon" id="inputPhoneNumber" value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789" class="form-control">
            </div>
            <div class="form-group">
                <label>Email Pengiriman COA</label>
                <input type="email" name="email_coa" id="inputEmailCoa" value="{{ old('email_coa') }}" placeholder="email@perusahaan.com" class="form-control">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Alamat Perusahaan / Kantor</label>
                <textarea name="alamat_perusahaan" id="inputCompanyAddress" rows="2" placeholder="Alamat lengkap perusahaan..." class="form-control">{{ old('alamat_perusahaan') }}</textarea>
            </div>
            <div class="form-group">
                <label>Lokasi / Titik Sampling (Site)</label>
                <textarea name="sampling_location" rows="2" placeholder="Alamat pabrik / lokasi titik cerobong..." class="form-control">{{ old('sampling_location') }}</textarea>
            </div>
        </div>

        <!-- Bagian 2: Parameter Waktu & Petugas Sampling -->
        <div class="section-title" style="margin-top: 25px;">
            <span>📅</span> Bagian 2: Waktu Sampling & Petugas Lapangan
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Tanggal Sampling *</label>
                <input type="date" name="sampling_date" id="samplingDate" value="{{ old('sampling_date', date('Y-m-d')) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>TAT / Estimasi Selesai (Hari)</label>
                <input type="number" name="tat_days" id="tatDays" value="{{ old('tat_days', 14) }}" min="1" max="90" class="form-control">
            </div>
            <div class="form-group">
                <label>Tanggal Target Selesai COA</label>
                <input type="date" name="tgl_selesai" id="tglSelesai" value="{{ old('tgl_selesai') }}" class="form-control">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Petugas Sampling (Pisahkan dengan koma)</label>
                <input type="text" name="sampling_officer" list="officerList" value="{{ old('sampling_officer', auth()->user()->name ?? '') }}" placeholder="Contoh: Dely, Rian, Bayu" class="form-control">
            </div>
            <div class="form-group">
                <label>Jenis Usaha / Keterangan Pekerjaan</label>
                <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha') }}" placeholder="Contoh: Industri Pembangkit Listrik / Semen / Makanan" class="form-control">
            </div>
        </div>

        <!-- Bagian 3: Titik Sampling Cerobong & Parameter Uji Matrix -->
        <div class="section-title" style="margin-top: 25px; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span>🧪</span> Bagian 3: Titik Cerobong & Pemilihan Parameter Uji
            </div>
            <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">(Minimal 1 Titik Cerobong)</span>
        </div>

        <div style="width: 100%;">
            <table class="sample-table" id="tableSamples">
                <thead>
                    <tr>
                        <th style="width: 4%; text-align: center;">No</th>
                        <th style="width: 11%;">Sample ID</th>
                        <th style="width: 17%;">Nama Cerobong *</th>
                        <th style="width: 24%;">Regulasi Baku Mutu</th>
                        <th style="width: 40%;">Selected Parameters & Methods</th>
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

        <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
            <button type="reset" class="tab-btn" onclick="setTimeout(refreshSampleRows, 100)">
                🔄 Reset Form
            </button>
            <button type="submit" class="btn-primary" style="background: #2563eb; color: white; border: none; padding: 14px 30px; border-radius: 12px; font-weight: 800; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                💾 Terbitkan Dokumen COC
            </button>
        </div>
    </form>
</div>

<!-- Master Regulations & Parameter Methods Cache for JS -->
<script>
    const masterRegulations = @json($regulations);
    const masterParameters = @json($masterParameters);
    const paramMethodsMap = @json($paramMethodsMap);
    const quotationDataList = @json($quotations);
    let sampleRowIndex = 0;

    function importQuotationData(qId) {
        if (!qId) return;
        const q = quotationDataList.find(item => item.id == qId);
        if (!q) return;

        // Fill company info
        if (document.getElementById('inputNomorQt')) document.getElementById('inputNomorQt').value = q.no_quotation || '';
        if (document.getElementById('inputCompanyName')) document.getElementById('inputCompanyName').value = q.nama_pelanggan || '';
        if (document.getElementById('inputContactPerson')) document.getElementById('inputContactPerson').value = q.kontak_person || '';
        if (document.getElementById('inputPhoneNumber')) document.getElementById('inputPhoneNumber').value = q.no_hp || '';
        if (document.getElementById('inputEmailCoa')) document.getElementById('inputEmailCoa').value = q.email || '';
        if (document.getElementById('inputCompanyAddress')) document.getElementById('inputCompanyAddress').value = q.alamat_pelanggan || '';
        if (document.getElementById('samplingDate') && q.tanggal_masuk) document.getElementById('samplingDate').value = q.tanggal_masuk.substring(0, 10);
        if (document.getElementById('tatDays') && q.tat_days) document.getElementById('tatDays').value = q.tat_days;

        // Populate sample rows
        const tbody = document.getElementById('sampleTableBody');
        tbody.innerHTML = '';
        sampleRowIndex = 0;

        if (q.items && q.items.length > 0) {
            q.items.forEach(it => {
                let pNames = [];
                if (Array.isArray(it.parameter_uji_json)) {
                    it.parameter_uji_json.forEach(p => {
                        if (typeof p === 'object' && p !== null) pNames.push(p.parameter);
                        else pNames.push(p);
                    });
                }
                addSampleRowWithParams(it.nama_titik_uji || 'Cerobong', it.regulasi || '', pNames.length > 0 ? pNames : defaultParams);
            });
        } else {
            addSampleRow('Cerobong Genset 1');
        }

        calculateTargetDate();
        alert('Data dari Quotation ' + q.no_quotation + ' berhasil dimuat ke formulir COC!');
    }

    function addSampleRowWithParams(defaultName, defaultReg, paramsList) {
        sampleRowIndex++;
        const tbody = document.getElementById('sampleTableBody');
        const currentCount = tbody.querySelectorAll('tr').length + 1;
        const seq = getCocSequence();
        const sampleId = `${seq}.${currentCount}`;

        let regulationOptions = `<option value="">-- Pilih Regulasi Baku Mutu --</option>`;
        masterRegulations.forEach(reg => {
            regulationOptions += `<option value="${escapeHtml(reg)}" ${reg === defaultReg ? 'selected' : ''}>${escapeHtml(reg)}</option>`;
        });

        const tr = document.createElement('tr');
        tr.id = `sampleRow_${sampleRowIndex}`;
        tr.innerHTML = `
            <td style="text-align: center; font-weight: 700; color: #64748b;" class="row-number">${currentCount}</td>
            <td>
                <input type="text" name="samples[${sampleRowIndex}][sample_id]" value="${sampleId}" class="form-control sample-id-input" style="font-family: monospace; font-weight: 700; color: #0284c7;" required readonly>
            </td>
            <td>
                <input type="text" name="samples[${sampleRowIndex}][nama_cerobong]" value="${escapeHtml(defaultName || `Cerobong Titik ${currentCount}`)}" placeholder="Contoh: Cerobong Genset 1" class="form-control" required>
            </td>
            <td>
                <select name="samples[${sampleRowIndex}][regulasi]" class="form-control regulation-select" onchange="onRegulationChanged(${sampleRowIndex}, this.value)">
                    ${regulationOptions}
                </select>
            </td>
            <td>
                <div style="margin-bottom: 6px; padding: 4px 8px; background: #eff6ff; border-radius: 6px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="selectAll_coc_${sampleRowIndex}" onchange="toggleSelectAllRow(this, ${sampleRowIndex})" class="param-checkbox" style="width: 16px; height: 16px; margin: 0; cursor: pointer;">
                    <label for="selectAll_coc_${sampleRowIndex}" style="font-size: 0.76rem; font-weight: 800; color: #1d4ed8; cursor: pointer; user-select: none; margin: 0;">
                        SELECT ALL (PILIH SEMUA)
                    </label>
                </div>
                <div id="paramContainer_${sampleRowIndex}">
                    <!-- Rendered by renderParameterRows -->
                </div>
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <button type="button" class="btn-remove-row" onclick="removeSampleRow(${sampleRowIndex})" title="Hapus Baris">
                    ✕
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        renderParameterRows(sampleRowIndex, paramsList);
        refreshSampleRows();
    }

    // Switch between List and Form Tabs
    function switchCocTab(tab) {
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
                addSampleRow('Cerobong Genset 1');
            }
        }
    }

    // Autocomplete handler for company name
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

    // Auto calculate target selesai date
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

    // Get sequence 4-digits from nomor COC
    function getCocSequence() {
        const cocVal = document.getElementById('nomorCoc').value || '';
        const match = cocVal.match(/(\d{4})$/);
        return match ? match[1] : '0001';
    }

    // Refresh row numbers & sample IDs
    function refreshSampleRows() {
        const rows = document.querySelectorAll('#sampleTableBody tr');
        const seq = getCocSequence();
        rows.forEach((row, idx) => {
            row.querySelector('.row-number').textContent = idx + 1;
            const idInput = row.querySelector('.sample-id-input');
            if (idInput) {
                idInput.value = `${seq}.${idx + 1}`;
            }
        });
    }

    document.getElementById('nomorCoc').addEventListener('input', refreshSampleRows);

    // Default emission parameters
    const defaultParams = [
        "Carbon Dioxide (CO2)", "Carbon Monoxide (CO)", "Nitrogen Monoxide (NO)", 
        "Nitrogen Oxide (NOx)", "Num of Traverse Point", "Oxygen (O2)", 
        "Percent of Isokinetic", "Velocity", "Volumetric Flow Rate", 
        "Water Vapor In flue gas", "Nitrogen Dioxide (NO2)", "Opacity", 
        "Particulate", "Sulfur Dioxide (SO2)"
    ];

    // Add a new sample row
    function addSampleRow(defaultName = '') {
        sampleRowIndex++;
        const tbody = document.getElementById('sampleTableBody');
        const currentCount = tbody.querySelectorAll('tr').length + 1;
        const seq = getCocSequence();
        const sampleId = `${seq}.${currentCount}`;

        let regulationOptions = `<option value="">-- Pilih Regulasi Baku Mutu --</option>`;
        masterRegulations.forEach(reg => {
            regulationOptions += `<option value="${escapeHtml(reg)}">${escapeHtml(reg)}</option>`;
        });

        const tr = document.createElement('tr');
        tr.id = `sampleRow_${sampleRowIndex}`;
        tr.innerHTML = `
            <td style="text-align: center; font-weight: 700; color: #64748b;" class="row-number">${currentCount}</td>
            <td>
                <input type="text" name="samples[${sampleRowIndex}][sample_id]" value="${sampleId}" class="form-control sample-id-input" style="font-family: monospace; font-weight: 700; color: #0284c7;" required readonly>
            </td>
            <td>
                <input type="text" name="samples[${sampleRowIndex}][nama_cerobong]" value="${escapeHtml(defaultName || `Cerobong Titik ${currentCount}`)}" placeholder="Contoh: Cerobong Genset 1" class="form-control" required>
            </td>
            <td>
                <select name="samples[${sampleRowIndex}][regulasi]" class="form-control regulation-select" onchange="onRegulationChanged(${sampleRowIndex}, this.value)">
                    ${regulationOptions}
                </select>
            </td>
            <td>
                <div style="margin-bottom: 6px; padding: 4px 8px; background: #eff6ff; border-radius: 6px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="selectAll_coc_${sampleRowIndex}" onchange="toggleSelectAllRow(this, ${sampleRowIndex})" class="param-checkbox" style="width: 16px; height: 16px; margin: 0; cursor: pointer;">
                    <label for="selectAll_coc_${sampleRowIndex}" style="font-size: 0.76rem; font-weight: 800; color: #1d4ed8; cursor: pointer; user-select: none; margin: 0;">
                        SELECT ALL (PILIH SEMUA)
                    </label>
                </div>
                <div id="paramContainer_${sampleRowIndex}">
                    <!-- Rendered by renderParameterRows -->
                </div>
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
                alert('Dokumen COC minimal harus memiliki 1 titik cerobong / sampel.');
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
                        <input type="checkbox" id="cb_${rowIndex}_${safeId}" class="param-checkbox param-checkbox-${rowIndex}" name="samples[${rowIndex}][parameters][]" value="${escapeHtml(pName)}" checked onchange="toggleParamMethodSlot(this, ${rowIndex}, '${escapeHtml(pName)}')">
                    </div>
                    <label for="cb_${rowIndex}_${safeId}" class="param-title-col">${escapeHtml(pName)}</label>
                    <div id="slot-metode-${rowIndex}-${safeId}">
                        <select name="samples[${rowIndex}][methods][${escapeHtml(pName)}]" class="select-metode" data-param="${escapeHtml(pName)}">
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
                <select name="samples[${rowIndex}][methods][${escapeHtml(paramName)}]" class="select-metode" data-param="${escapeHtml(paramName)}">
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
        // Check exact or partial match from MySQL masterParameters
        for (let k in paramMethodsMap) {
            if (k.toLowerCase() === pLower || k.toLowerCase().includes(pLower) || pLower.includes(k.toLowerCase())) {
                if (paramMethodsMap[k] && paramMethodsMap[k].length > 0) {
                    return paramMethodsMap[k];
                }
            }
        }

        // Standard emission fallback methods
        if (pLower.includes('carbon dioxide') || pLower.includes('co2')) {
            return ['IKM ESP 7.2.5', 'SNI 19-7117.10-2005', 'USEPA Method 3A'];
        }
        if (pLower.includes('carbon monoxide') || pLower.includes('co')) {
            return ['IKM-ESP-7.2.5', 'SNI 19-7117.10-2005', 'USEPA Method 10'];
        }
        if (pLower.includes('nitrogen oxide') || pLower.includes('nox') || pLower.includes('nitrogen dioxide') || pLower.includes('no2')) {
            return ['IKM-ESP-7.2.10', 'SNI 19-7117.10-2005', 'USEPA Method 7E'];
        }
        if (pLower.includes('sulfur dioxide') || pLower.includes('so2')) {
            return ['IKM-ESP-7.2.17', 'SNI 19-7117.10-2005', 'USEPA Method 6C'];
        }
        if (pLower.includes('oxygen') || pLower.includes('o2')) {
            return ['IKM-ESP-7.2.5', 'SNI 19-7117.10-2005', 'USEPA Method 3A'];
        }
        if (pLower.includes('particulate') || pLower.includes('partikulat')) {
            return ['EPA Method 5 Tahun 2020', 'SNI 7117.17:2009', 'SNI 7117.21:2021'];
        }
        if (pLower.includes('traverse point')) {
            return ['EPA Method 1 Tahun 2023', 'SNI 7117.13:2009'];
        }
        if (pLower.includes('velocity') || pLower.includes('volumetric flow')) {
            return ['EPA Method 2 Tahun 2017', 'SNI 7117.14:2009'];
        }
        if (pLower.includes('isokinetic')) {
            return ['EPA Method 5 Tahun 2020', 'SNI 7117.17:2009'];
        }
        if (pLower.includes('water vapor')) {
            return ['EPA Method 4 Tahun 2017', 'SNI 7117.16:2009'];
        }
        if (pLower.includes('opacity') || pLower.includes('opasitas')) {
            return ['SNI 19-7117.11-2005 (Ringelmann)'];
        }

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
        // Always include combustion gases and isokinetic parameters
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

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Auto initialize on load
    document.addEventListener('DOMContentLoaded', () => {
        calculateTargetDate();
    });
</script>
@endsection
