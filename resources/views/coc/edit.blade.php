@extends('layouts.app')

@section('title', 'Edit Chain of Custody ' . $coc->nomor_coc)

@section('content')
<style>
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
        padding: 6px 10px;
        cursor: pointer;
        font-weight: 700;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.show' : 'coc.show', $coc->id) }}" style="color: #64748b; text-decoration: none; font-size: 1.2rem;" title="Kembali">
            ⬅️
        </a>
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">
                ✏️ Edit Dokumen COC: <span style="color: #0284c7; font-family: monospace;">{{ $coc->nomor_coc }}</span>
            </h2>
            <p style="font-size: 0.85rem; color: #64748b;">Perbarui informasi rantai lacak pengambilan sampel, titik cerobong, dan pemilihan parameter & metode uji.</p>
        </div>
    </div>
</div>

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

<div class="coc-card">
    <form action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.update' : 'coc.update', $coc->id) }}" method="POST">
        @csrf
        @method('PUT')

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
                <input type="text" name="nomor_coc" id="nomorCoc" value="{{ old('nomor_coc', $coc->nomor_coc) }}" class="form-control" required readonly style="font-family: monospace; font-weight: 700; color: #0284c7; background: #f8fafc;">
            </div>
            <div class="form-group">
                <label>Nomor Quotation (QT)</label>
                <input type="text" name="nomor_qt" value="{{ old('nomor_qt', $coc->nomor_qt) }}" placeholder="Contoh: QT/2026/08/0012" class="form-control">
            </div>
            <div class="form-group">
                <label>Nama Perusahaan / Klien *</label>
                <input type="text" name="company_name" id="inputCompanyName" list="companyList" value="{{ old('company_name', $coc->company_name) }}" class="form-control" required>
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Contact Person (PIC)</label>
                <input type="text" name="contact_person" id="inputContactPerson" value="{{ old('contact_person', $coc->contact_person) }}" class="form-control">
            </div>
            <div class="form-group">
                <label>No. Telepon / WhatsApp</label>
                <input type="text" name="no_telepon" id="inputPhoneNumber" value="{{ old('no_telepon', $coc->no_telepon) }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Email Pengiriman COA</label>
                <input type="email" name="email_coa" id="inputEmailCoa" value="{{ old('email_coa', $coc->email_coa) }}" class="form-control">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Alamat Perusahaan / Kantor</label>
                <textarea name="alamat_perusahaan" id="inputCompanyAddress" rows="2" class="form-control">{{ old('alamat_perusahaan', $coc->alamat_perusahaan) }}</textarea>
            </div>
            <div class="form-group">
                <label>Lokasi / Titik Sampling (Site)</label>
                <textarea name="sampling_location" rows="2" class="form-control">{{ old('sampling_location', $coc->sampling_location) }}</textarea>
            </div>
        </div>

        <!-- Bagian 2: Waktu & Petugas -->
        <div class="section-title" style="margin-top: 25px;">
            <span>📅</span> Bagian 2: Waktu Sampling & Petugas Lapangan
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Tanggal Sampling *</label>
                <input type="date" name="sampling_date" id="samplingDate" value="{{ old('sampling_date', $coc->sampling_date ? $coc->sampling_date->format('Y-m-d') : '') }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>TAT / Estimasi Selesai (Hari)</label>
                <input type="number" name="tat_days" id="tatDays" value="{{ old('tat_days', $coc->tat_days ?? 14) }}" min="1" max="90" class="form-control">
            </div>
            <div class="form-group">
                <label>Tanggal Target Selesai COA</label>
                <input type="date" name="tgl_selesai" id="tglSelesai" value="{{ old('tgl_selesai', $coc->tgl_selesai ? $coc->tgl_selesai->format('Y-m-d') : '') }}" class="form-control">
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Petugas Sampling</label>
                <input type="text" name="sampling_officer" list="officerList" value="{{ old('sampling_officer', $coc->sampling_officer) }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Status Dokumen</label>
                <select name="status" class="form-control">
                    <option value="Draft" {{ $coc->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Sampling" {{ $coc->status === 'Sampling' ? 'selected' : '' }}>Sampling</option>
                    <option value="Analisa" {{ $coc->status === 'Analisa' ? 'selected' : '' }}>Analisa Lab</option>
                    <option value="Verified" {{ $coc->status === 'Verified' ? 'selected' : '' }}>Verified (Selesai)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Jenis Usaha / Keterangan</label>
                <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha', $coc->jenis_usaha) }}" class="form-control">
            </div>
        </div>

        <!-- Bagian 3: Titik Cerobong & Parameter -->
        <div class="section-title" style="margin-top: 25px;">
            <span>🧪</span> Bagian 3: Titik Cerobong & Pemilihan Parameter Uji
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
                    @foreach($coc->samples as $idx => $sample)
                    @php
                        $sampleParamsList = [];
                        $sampleMethodsMap = [];
                        if (is_array($sample->parameters)) {
                            foreach ($sample->parameters as $pKey => $pVal) {
                                if (is_array($pVal)) {
                                    $pName = $pVal['parameter'] ?? $pKey;
                                    $sampleParamsList[] = $pName;
                                    if (isset($pVal['metode'])) $sampleMethodsMap[$pName] = $pVal['metode'];
                                } else {
                                    $sampleParamsList[] = (string)$pVal;
                                }
                            }
                        }
                        $currentReg = is_array($sample->regulations) ? ($sample->regulations[0] ?? '') : $sample->regulations;

                        $standardList = [
                            "Carbon Dioxide (CO2)", "Carbon Monoxide (CO)", "Nitrogen Monoxide (NO)", 
                            "Nitrogen Oxide (NOx)", "Num of Traverse Point", "Oxygen (O2)", 
                            "Percent of Isokinetic", "Velocity", "Volumetric Flow Rate", 
                            "Water Vapor In flue gas", "Nitrogen Dioxide (NO2)", "Opacity", 
                            "Particulate", "Sulfur Dioxide (SO2)"
                        ];
                        $allPossible = array_unique(array_merge($sampleParamsList, $standardList));
                    @endphp
                    <tr id="sampleRow_{{ $idx }}">
                        <td style="text-align: center; font-weight: 700; color: #64748b;" class="row-number">{{ $idx + 1 }}</td>
                        <td>
                            <input type="hidden" name="samples[{{ $idx }}][id]" value="{{ $sample->id }}">
                            <input type="text" name="samples[{{ $idx }}][sample_id]" value="{{ $sample->sample_id }}" class="form-control sample-id-input" style="font-family: monospace; font-weight: 700; color: #0284c7;" required readonly>
                        </td>
                        <td>
                            <input type="text" name="samples[{{ $idx }}][nama_cerobong]" value="{{ $sample->nama_cerobong }}" class="form-control" required>
                        </td>
                        <td>
                            <select name="samples[{{ $idx }}][regulasi]" class="form-control regulation-select" onchange="onRegulationChanged({{ $idx }}, this.value)">
                                <option value="">-- Pilih Regulasi Baku Mutu --</option>
                                @foreach($regulations as $reg)
                                    <option value="{{ $reg }}" {{ $currentReg === $reg ? 'selected' : '' }}>{{ $reg }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <div style="margin-bottom: 6px; padding: 4px 8px; background: #eff6ff; border-radius: 6px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="selectAll_edit_{{ $idx }}" onchange="toggleSelectAllRow(this, {{ $idx }})" class="param-checkbox" style="width: 16px; height: 16px; margin: 0; cursor: pointer;">
                                <label for="selectAll_edit_{{ $idx }}" style="font-size: 0.76rem; font-weight: 800; color: #1d4ed8; cursor: pointer; user-select: none; margin: 0;">
                                    SELECT ALL (PILIH SEMUA)
                                </label>
                            </div>
                            <div id="paramContainer_{{ $idx }}">
                                @foreach($allPossible as $pName)
                                @php
                                    $safeId = preg_replace('/[^a-z0-9]/i', '-', $pName);
                                    $isChecked = in_array($pName, $sampleParamsList);
                                    $savedMethod = $sampleMethodsMap[$pName] ?? null;
                                @endphp
                                <div class="param-row">
                                    <div class="param-check-col">
                                        <input type="checkbox" id="cb_edit_{{ $idx }}_{{ $safeId }}" class="param-checkbox param-checkbox-{{ $idx }}" name="samples[{{ $idx }}][parameters][]" value="{{ $pName }}" {{ $isChecked ? 'checked' : '' }} onchange="toggleParamMethodSlot(this, {{ $idx }}, '{{ $pName }}')">
                                    </div>
                                    <label for="cb_edit_{{ $idx }}_{{ $safeId }}" class="param-title-col">{{ $pName }}</label>
                                    <div id="slot-metode-{{ $idx }}-{{ $safeId }}">
                                        @if($isChecked)
                                            <select name="samples[{{ $idx }}][methods][{{ $pName }}]" class="select-metode" data-param="{{ $pName }}">
                                                <option value="{{ $savedMethod ?? 'IKM-ESP-7.2.11' }}" selected>{{ $savedMethod ?? 'IKM-ESP-7.2.11' }}</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <button type="button" class="btn-remove-row" onclick="removeSampleRow({{ $idx }})" title="Hapus Baris">✕</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="button" class="btn-add-row" onclick="addSampleRow()">
            <span>➕</span> Tambah Titik Cerobong / Sampel Baru
        </button>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.coc.show' : 'coc.show', $coc->id) }}" class="tab-btn">
                Batal
            </a>
            <button type="submit" class="btn-primary" style="background: #2563eb; color: white; border: none; padding: 14px 30px; border-radius: 12px; font-weight: 800; font-size: 0.95rem; cursor: pointer;">
                💾 Simpan Perubahan COC
            </button>
        </div>
    </form>
</div>

<!-- Master Regulations & Parameter Methods Cache for JS -->
<script>
    const masterRegulations = @json($regulations);
    const masterParameters = @json($masterParameters);
    const paramMethodsMap = @json($paramMethodsMap);
    let sampleRowIndex = {{ $coc->samples->count() + 10 }};

    function getCocSequence() {
        const cocVal = document.getElementById('nomorCoc').value || '';
        const match = cocVal.match(/(\d{4})$/);
        return match ? match[1] : '0001';
    }

    function refreshSampleRows() {
        const rows = document.querySelectorAll('#sampleTableBody tr');
        const seq = getCocSequence();
        rows.forEach((row, idx) => {
            row.querySelector('.row-number').textContent = idx + 1;
            const idInput = row.querySelector('.sample-id-input');
            if (idInput && !idInput.value) {
                idInput.value = `${seq}.${idx + 1}`;
            }
        });
    }

    const defaultParams = [
        "Carbon Dioxide (CO2)", "Carbon Monoxide (CO)", "Nitrogen Monoxide (NO)", 
        "Nitrogen Oxide (NOx)", "Num of Traverse Point", "Oxygen (O2)", 
        "Percent of Isokinetic", "Velocity", "Volumetric Flow Rate", 
        "Water Vapor In flue gas", "Nitrogen Dioxide (NO2)", "Opacity", 
        "Particulate", "Sulfur Dioxide (SO2)"
    ];

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
                    <input type="checkbox" id="selectAll_edit_${sampleRowIndex}" onchange="toggleSelectAllRow(this, ${sampleRowIndex})" class="param-checkbox" style="width: 16px; height: 16px; margin: 0; cursor: pointer;">
                    <label for="selectAll_edit_${sampleRowIndex}" style="font-size: 0.76rem; font-weight: 800; color: #1d4ed8; cursor: pointer; user-select: none; margin: 0;">
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
                        <input type="checkbox" id="cb_edit_${rowIndex}_${safeId}" class="param-checkbox param-checkbox-${rowIndex}" name="samples[${rowIndex}][parameters][]" value="${escapeHtml(pName)}" checked onchange="toggleParamMethodSlot(this, ${rowIndex}, '${escapeHtml(pName)}')">
                    </div>
                    <label for="cb_edit_${rowIndex}_${safeId}" class="param-title-col">${escapeHtml(pName)}</label>
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
        for (let k in paramMethodsMap) {
            if (k.toLowerCase() === pLower || k.toLowerCase().includes(pLower) || pLower.includes(k.toLowerCase())) {
                if (paramMethodsMap[k] && paramMethodsMap[k].length > 0) {
                    return paramMethodsMap[k];
                }
            }
        }

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
</script>
@endsection
