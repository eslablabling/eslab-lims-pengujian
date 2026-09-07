@extends('layouts.app')

@section('title', 'Input Sampling ' . $sample->sample_id)

@section('content')
<style>
    .sampling-edit-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        margin-bottom: 30px;
    }
    .sampling-subtab-nav {
        display: flex;
        gap: 8px;
        background: #f8fafc;
        padding: 6px;
        border-radius: 14px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }
    .sampling-subtab-btn {
        padding: 10px 18px;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sampling-subtab-btn.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }
    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }
    .form-group {
        margin-bottom: 14px;
    }
    .form-group label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.88rem;
        font-family: inherit;
        color: #1e293b;
        background: #ffffff;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .form-control[readonly] {
        background: #f8fafc;
        color: #64748b;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }
    .gas-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .gas-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 10px;
        border: 1px solid #e2e8f0;
        text-align: center;
    }
    .gas-table td {
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .badge-limit-safe {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
        display: inline-block;
    }
    .badge-limit-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.75rem;
        display: inline-block;
    }
    .btn-weather-fetch {
        background: #0284c7;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }
    .btn-weather-fetch:hover {
        background: #0369a1;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.index' : 'sampling.index') }}" style="color: #64748b; text-decoration: none; font-size: 1.1rem;">⬅️</a>
            <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">
                Input Data Sampling: <span style="color: #0284c7; font-family: monospace;">{{ $sample->sample_id }}</span>
            </h2>
        </div>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">
            Perusahaan: <strong>{{ $sample->coc?->company_name }}</strong> | Cerobong: <strong>{{ $sample->nama_cerobong }}</strong> | No. COC: <strong>{{ $sample->coc?->nomor_coc }}</strong>
        </p>
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

<form method="POST" action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.update' : 'sampling.update', $sample->id) }}" id="formSamplingData">
    @csrf
    @method('PUT')

    <div class="sampling-subtab-nav">
        <button type="button" class="sampling-subtab-btn active" id="btnTabCuaca" onclick="switchSamplingSection('cuaca')">
            🌦️ 1. Meteorologi & Cuaca Lapangan
        </button>
        <button type="button" class="sampling-subtab-btn" id="btnTabGas" onclick="switchSamplingSection('gas')">
            🔥 2. Direct Reading Gas & Emisi
        </button>
        <button type="button" class="sampling-subtab-btn" id="btnTabOpasitas" onclick="switchSamplingSection('opasitas')">
            👁️ 3. Pengamatan Opasitas Ringelmann
        </button>
        <button type="button" class="sampling-subtab-btn" id="btnTabIsokinetik" onclick="switchSamplingSection('isokinetik')">
            💨 4. Laju Alir & Isokinetik
        </button>
    </div>

    <!-- ============================================================ -->
    <!-- SECTION 1: METEOROLOGI & CUACA LAPANGAN -->
    <!-- ============================================================ -->
    <div id="sectionCuaca" class="sampling-edit-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <div class="section-title" style="margin-bottom: 0; border-bottom: none;">
                <span>🌤️</span> Kondisi Meteorologi & Pengamatan Fisik Lapangan
            </div>
            <button type="button" class="btn-weather-fetch" onclick="fetchLiveWeather()">
                🛰️ Tarik Cuaca Realtime Geolocation
            </button>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Nama Cerobong / Titik *</label>
                <input type="text" name="nama_cerobong" value="{{ old('nama_cerobong', $sample->nama_cerobong) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis Bahan Bakar</label>
                <input type="text" name="bahan_bakar" value="{{ old('bahan_bakar', $sample->bahan_bakar) }}" placeholder="Contoh: Gas Alam / Solar / Batubara" class="form-control">
            </div>
            <div class="form-group">
                <label>Koordinat Titik Sampling (GPS)</label>
                <input type="text" name="koordinat" id="inputKoordinat" value="{{ old('koordinat', $sample->koordinat) }}" placeholder="-6.321456, 107.123456" class="form-control">
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Waktu Pengukuran Gas</label>
                <input type="text" name="waktu_gas" value="{{ old('waktu_gas', $sample->waktu_gas ?? '09:00 - 10:00 WIB') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Nomor Alat Gas Analyzer</label>
                <input type="text" name="no_alat_gas" value="{{ old('no_alat_gas', $sample->no_alat_gas ?? 'FLUE GAS ANALYZER') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Suhu Gas Cerobong (°C)</label>
                <input type="number" step="0.1" name="temp_gas" id="tempGas" value="{{ old('temp_gas', $sample->temp_gas) }}" placeholder="Contoh: 145.5" class="form-control">
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Tekanan Atmosfer (mmHg)</label>
                <input type="number" step="0.1" name="tekanan_atm" id="tekananAtm" value="{{ old('tekanan_atm', $sample->tekanan_atm ?? 755.0) }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Suhu Ambien Awal (°C)</label>
                <input type="number" step="0.1" name="temp_ambien_awal" id="tempAmbienAwal" value="{{ old('temp_ambien_awal', $sample->temp_ambien_awal) }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Suhu Ambien Akhir (°C)</label>
                <input type="number" step="0.1" name="temp_ambien_akhir" id="tempAmbienAkhir" value="{{ old('temp_ambien_akhir', $sample->temp_ambien_akhir) }}" class="form-control">
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Kelembaban Awal (%)</label>
                <input type="number" step="0.1" name="kelembaban_awal" id="kelembabanAwal" value="{{ old('kelembaban_awal', $sample->kelembaban_awal) }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Kelembaban Akhir (%)</label>
                <input type="number" step="0.1" name="kelembaban_akhir" id="kelembabanAkhir" value="{{ old('kelembaban_akhir', $sample->kelembaban_akhir) }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Kecepatan Angin (m/s)</label>
                <input type="number" step="0.1" name="kec_angin_awal" id="kecAnginAwal" value="{{ old('kec_angin_awal', $sample->kec_angin_awal ?? 1.2) }}" class="form-control">
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Arah Angin</label>
                <input type="text" name="arah_angin_awal" id="arahAnginAwal" value="{{ old('arah_angin_awal', $sample->arah_angin_awal ?? 'Timur ke Barat') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Kondisi Langit</label>
                <input type="text" name="kondisi_langit_awal" value="{{ old('kondisi_langit_awal', $sample->kondisi_langit_awal ?? 'Cerah Berawan') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Warna Emisi Asap</label>
                <input type="text" name="warna_emisi_awal" value="{{ old('warna_emisi_awal', $sample->warna_emisi_awal ?? 'Tidak Berwarna / Transparan') }}" class="form-control">
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SECTION 2: PARAMETER GAS DIRECT READING & EMISI -->
    <!-- ============================================================ -->
    <div id="sectionGas" class="sampling-edit-card" style="display: none;">
        <div class="section-title">
            <span>🔥</span> Parameter Direct Reading Gas & Koreksi Oksigen ($O_2$)
        </div>

        @php
            $rawParams = is_array($sample->parameters) ? $sample->parameters : [];
            // Normalize parameter items
            $paramList = [];
            foreach($rawParams as $item) {
                if (is_string($item)) {
                    $paramList[] = ['parameter' => $item, 'r1' => '', 'r2' => '', 'r3' => '', 'rata_rata' => '', 'terkoreksi' => '', 'satuan' => 'mg/Nm3'];
                } elseif (is_array($item)) {
                    $paramList[] = array_merge([
                        'parameter'   => $item['parameter'] ?? $item['nama_parameter'] ?? '-',
                        'r1'          => $item['konsentrasi_1'] ?? $item['r1'] ?? '',
                        'r2'          => $item['konsentrasi_2'] ?? $item['r2'] ?? '',
                        'r3'          => $item['konsentrasi_3'] ?? $item['r3'] ?? '',
                        'rata_rata'   => $item['hasil'] ?? $item['rata_rata'] ?? '',
                        'terkoreksi'  => $item['terkoreksi_o2'] ?? $item['terkoreksi'] ?? '',
                        'satuan'      => $item['satuan'] ?? 'mg/Nm3'
                    ], $item);
                }
            }
        @endphp

        <div style="overflow-x: auto; width: 100%;">
            <table class="gas-table" id="tableGasParameters">
                <thead>
                    <tr>
                        <th style="width: 220px; text-align: left;">Nama Parameter</th>
                        <th style="width: 80px;">Satuan</th>
                        <th style="width: 100px;">R1 (Uji 1)</th>
                        <th style="width: 100px;">R2 (Uji 2)</th>
                        <th style="width: 100px;">R3 (Uji 3)</th>
                        <th style="width: 110px;">Rata-rata Terukur</th>
                        <th style="width: 110px;">Terkoreksi $O_2$</th>
                        <th style="width: 100px;">Baku Mutu</th>
                        <th style="width: 110px;">Status Mutu</th>
                    </tr>
                </thead>
                <tbody id="gasTableBody">
                    @foreach($paramList as $pIdx => $p)
                    @php
                        $pName = $p['parameter'];
                        // Find matching master emisi
                        $matchMaster = $masterEmisi->first(fn($m) => strcasecmp($m->nama_parameter ?? $m->parameter, $pName) === 0);
                        $bakuMutu = $matchMaster->baku_mutu ?? null;
                        $refO2 = $matchMaster->koreksi_o2 ?? null;
                    @endphp
                    <tr class="gas-param-row" data-param-name="{{ $pName }}" data-baku-mutu="{{ $bakuMutu ?? '' }}" data-ref-o2="{{ $refO2 ?? '' }}">
                        <td style="font-weight: 700; color: #0f172a;">
                            <input type="hidden" name="parameters[{{ $pIdx }}][parameter]" value="{{ $pName }}">
                            {{ $pName }}
                            @if($refO2)
                            <div style="font-size: 0.7rem; color: #0284c7;">(Koreksi $O_2$ Ref: {{ $refO2 }}%)</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <input type="text" name="parameters[{{ $pIdx }}][satuan]" value="{{ $p['satuan'] ?? 'mg/Nm3' }}" class="form-control" style="font-size: 0.75rem; padding: 4px; text-align: center;">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="parameters[{{ $pIdx }}][konsentrasi_1]" value="{{ $p['r1'] }}" class="form-control gas-input-r1" oninput="calculateGasRow(this)" style="padding: 6px; text-align: right;">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="parameters[{{ $pIdx }}][konsentrasi_2]" value="{{ $p['r2'] }}" class="form-control gas-input-r2" oninput="calculateGasRow(this)" style="padding: 6px; text-align: right;">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="parameters[{{ $pIdx }}][konsentrasi_3]" value="{{ $p['r3'] }}" class="form-control gas-input-r3" oninput="calculateGasRow(this)" style="padding: 6px; text-align: right;">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="parameters[{{ $pIdx }}][hasil]" value="{{ $p['rata_rata'] }}" class="form-control gas-input-avg" readonly style="padding: 6px; text-align: right; font-weight: 700; background: #f8fafc;">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="parameters[{{ $pIdx }}][terkoreksi_o2]" value="{{ $p['terkoreksi'] }}" class="form-control gas-input-corrected" readonly style="padding: 6px; text-align: right; font-weight: 800; color: #0284c7; background: #f0fdf4;">
                        </td>
                        <td style="text-align: center; font-weight: 700; font-size: 0.8rem; color: #475569;">
                            {{ $bakuMutu ? number_format((float)$bakuMutu, 2) : '-' }}
                        </td>
                        <td style="text-align: center;" class="gas-status-cell">
                            <span class="badge-limit-safe">✅ Aman</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SECTION 3: OPASITAS RINGELMANN -->
    <!-- ============================================================ -->
    <div id="sectionOpasitas" class="sampling-edit-card" style="display: none;">
        <div class="section-title">
            <span>👁️</span> Pengamatan Opasitas Cerobong (SNI 19-7117.11-2005)
        </div>

        <div class="form-grid-3" style="margin-bottom: 20px;">
            <div class="form-group">
                <label>Waktu Mulai Pengamatan</label>
                <input type="time" name="opasitas_mulai" value="{{ old('opasitas_mulai', $sample->opasitas_mulai ?? '09:00') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Waktu Selesai Pengamatan</label>
                <input type="time" name="opasitas_akhir" value="{{ old('opasitas_akhir', $sample->opasitas_akhir ?? '09:06') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Rata-rata Opasitas Keseluruhan (%)</label>
                <input type="number" step="0.1" name="opasitas_avg" id="inputOpasitasAvg" value="{{ old('opasitas_avg', $sample->opasitas_avg ?? 10.0) }}" class="form-control" readonly style="font-weight: 800; color: #0284c7; background: #eff6ff;">
            </div>
        </div>

        <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 12px;">
            Catat persentase opasitas asap tiap interval 15 detik untuk 6 periode pengamatan (total 24 pembacaan):
        </p>

        @php
            $opMatrix = is_array($sample->opasitas_matrix) ? $sample->opasitas_matrix : [];
        @endphp

        <div style="overflow-x: auto; width: 100%;">
            <table class="gas-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">Menit Ke-</th>
                        <th>Detik 00</th>
                        <th>Detik 15</th>
                        <th>Detik 30</th>
                        <th>Detik 45</th>
                        <th>Rata-rata Menit (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @for($m = 1; $m <= 6; $m++)
                    @php
                        $mRow = $opMatrix[$m-1] ?? [10, 10, 10, 10];
                    @endphp
                    <tr class="opacity-period-row" data-minute="{{ $m }}">
                        <td style="font-weight: 800; text-align: center;">Menit {{ $m }}</td>
                        <td><input type="number" name="opasitas_matrix[{{ $m-1 }}][0]" value="{{ $mRow[0] ?? 10 }}" min="0" max="100" class="form-control op-sec" oninput="calculateOpacityTotal()" style="text-align: center; padding: 6px;"></td>
                        <td><input type="number" name="opasitas_matrix[{{ $m-1 }}][1]" value="{{ $mRow[1] ?? 10 }}" min="0" max="100" class="form-control op-sec" oninput="calculateOpacityTotal()" style="text-align: center; padding: 6px;"></td>
                        <td><input type="number" name="opasitas_matrix[{{ $m-1 }}][2]" value="{{ $mRow[2] ?? 10 }}" min="0" max="100" class="form-control op-sec" oninput="calculateOpacityTotal()" style="text-align: center; padding: 6px;"></td>
                        <td><input type="number" name="opasitas_matrix[{{ $m-1 }}][3]" value="{{ $mRow[3] ?? 10 }}" min="0" max="100" class="form-control op-sec" oninput="calculateOpacityTotal()" style="text-align: center; padding: 6px;"></td>
                        <td style="text-align: center; font-weight: 700; background: #f8fafc;" class="op-min-avg">10.0%</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SECTION 4: ISOKINETIK & LAJU ALIR -->
    <!-- ============================================================ -->
    <div id="sectionIsokinetik" class="sampling-edit-card" style="display: none;">
        <div class="section-title">
            <span>💨</span> Parameter Laju Alir & Isokinetik Partikulat Cerobong
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Kecepatan Laju Alir Gas ($v_s$) [m/s]</label>
                <input type="number" step="0.01" name="parameters[velocity][hasil]" value="{{ old('velocity', 12.45) }}" class="form-control" placeholder="Contoh: 12.45">
            </div>
            <div class="form-group">
                <label>Kadar Air Gas Cerobong ($X_w$) [%]</label>
                <input type="number" step="0.01" name="parameters[moisture][hasil]" value="{{ old('moisture', 4.25) }}" class="form-control" placeholder="Contoh: 4.25">
            </div>
            <div class="form-group">
                <label>Persentase Isokinetik (%I) [90 - 110%]</label>
                <input type="number" step="0.01" name="parameters[isokinetic][hasil]" value="{{ old('isokinetic', 98.65) }}" class="form-control" placeholder="Contoh: 98.65" style="font-weight: 800; color: #166534;">
            </div>
        </div>

        <div class="form-group" style="margin-top: 15px;">
            <label>Catatan Teknis Sampling Lapangan</label>
            <textarea name="catatan_cuaca" rows="3" class="form-control" placeholder="Catatan kondisi sampling di lapangan...">{{ old('catatan_cuaca', $sample->catatan_cuaca) }}</textarea>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 40px;">
        <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.index' : 'sampling.index') }}" style="padding: 12px 24px; background: #f1f5f9; color: #475569; border-radius: 10px; font-weight: 700; text-decoration: none;">
            Batal
        </a>
        <button type="submit" style="padding: 12px 30px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 800; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            💾 Simpan Data Sampling Lapangan
        </button>
    </div>
</form>

<script>
    function switchSamplingSection(section) {
        document.getElementById('sectionCuaca').style.display = section === 'cuaca' ? 'block' : 'none';
        document.getElementById('sectionGas').style.display = section === 'gas' ? 'block' : 'none';
        document.getElementById('sectionOpasitas').style.display = section === 'opasitas' ? 'block' : 'none';
        document.getElementById('sectionIsokinetik').style.display = section === 'isokinetik' ? 'block' : 'none';

        document.getElementById('btnTabCuaca').classList.toggle('active', section === 'cuaca');
        document.getElementById('btnTabGas').classList.toggle('active', section === 'gas');
        document.getElementById('btnTabOpasitas').classList.toggle('active', section === 'opasitas');
        document.getElementById('btnTabIsokinetik').classList.toggle('active', section === 'isokinetik');
    }

    // Live weather fetch via browser navigator
    function fetchLiveWeather() {
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda.');
            return;
        }
        navigator.geolocation.getCurrentPosition(async (pos) => {
            const lat = pos.coords.latitude;
            const lon = pos.coords.longitude;
            document.getElementById('inputKoordinat').value = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;

            try {
                const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,surface_pressure,wind_speed_10m,wind_direction_10m`);
                const data = await res.json();
                if (data && data.current) {
                    const c = data.current;
                    document.getElementById('tempAmbienAwal').value = c.temperature_2m;
                    document.getElementById('tempAmbienAkhir').value = c.temperature_2m;
                    document.getElementById('kelembabanAwal').value = c.relative_humidity_2m;
                    document.getElementById('kelembabanAkhir').value = c.relative_humidity_2m;
                    document.getElementById('kecAnginAwal').value = (c.wind_speed_10m / 3.6).toFixed(2);
                    if (c.surface_pressure) {
                        document.getElementById('tekananAtm').value = (c.surface_pressure * 0.750062).toFixed(1);
                    }
                    alert('✓ Berhasil menarik data cuaca realtime dari satelit!');
                }
            } catch (err) {
                alert('Berhasil mengambil koordinat GPS.');
            }
        }, () => {
            alert('Tidak dapat mengakses lokasi GPS perangkat.');
        });
    }

    // Gas Calculation Engine
    function calculateGasRow(inputElem) {
        const row = inputElem.closest('.gas-param-row');
        if (!row) return;

        const r1 = parseFloat(row.querySelector('.gas-input-r1')?.value) || 0;
        const r2 = parseFloat(row.querySelector('.gas-input-r2')?.value) || 0;
        const r3 = parseFloat(row.querySelector('.gas-input-r3')?.value) || 0;

        let count = 0;
        let sum = 0;
        if (row.querySelector('.gas-input-r1')?.value !== '') { sum += r1; count++; }
        if (row.querySelector('.gas-input-r2')?.value !== '') { sum += r2; count++; }
        if (row.querySelector('.gas-input-r3')?.value !== '') { sum += r3; count++; }

        const avg = count > 0 ? (sum / count) : 0;
        const avgInput = row.querySelector('.gas-input-avg');
        if (avgInput) {
            avgInput.value = avg > 0 ? avg.toFixed(2) : '';
        }

        // Get Measured Oxygen from Oxygen row if available
        let measuredO2 = 0;
        const o2Row = document.querySelector('.gas-param-row[data-param-name*="Oxygen"], .gas-param-row[data-param-name*="Oksigen"], .gas-param-row[data-param-name*="O2"]');
        if (o2Row) {
            measuredO2 = parseFloat(o2Row.querySelector('.gas-input-avg')?.value) || 0;
        }

        // Calculate O2 corrected concentration
        const refO2Str = row.dataset.refO2;
        const correctedInput = row.querySelector('.gas-input-corrected');
        let finalVal = avg;

        if (refO2Str && refO2Str !== '' && measuredO2 > 0 && measuredO2 < 21) {
            const refO2 = parseFloat(refO2Str);
            const correctedVal = avg * ((21 - refO2) / (21 - measuredO2));
            if (correctedInput) {
                correctedInput.value = correctedVal > 0 ? correctedVal.toFixed(2) : '';
                finalVal = correctedVal;
            }
        } else if (correctedInput) {
            correctedInput.value = avg > 0 ? avg.toFixed(2) : '';
        }

        // Compare against Baku Mutu
        const bakuMutuStr = row.dataset.bakuMutu;
        const statusCell = row.querySelector('.gas-status-cell');
        if (statusCell && bakuMutuStr && bakuMutuStr !== '') {
            const limit = parseFloat(bakuMutuStr);
            if (finalVal > limit) {
                statusCell.innerHTML = `<span class="badge-limit-danger">⚠️ Melebihi (${finalVal.toFixed(1)} > ${limit})</span>`;
            } else {
                statusCell.innerHTML = `<span class="badge-limit-safe">✅ Aman</span>`;
            }
        }
    }

    // Calculate Opacity Matrix
    function calculateOpacityTotal() {
        const rows = document.querySelectorAll('.opacity-period-row');
        let grandSum = 0;
        let totalCount = 0;

        rows.forEach(row => {
            const secs = row.querySelectorAll('.op-sec');
            let mSum = 0;
            let mCount = 0;
            secs.forEach(sec => {
                const v = parseFloat(sec.value);
                if (!isNaN(v)) {
                    mSum += v;
                    mCount++;
                    grandSum += v;
                    totalCount++;
                }
            });
            const mAvg = mCount > 0 ? (mSum / mCount) : 0;
            row.querySelector('.op-min-avg').textContent = mAvg.toFixed(1) + '%';
        });

        const overallAvg = totalCount > 0 ? (grandSum / totalCount) : 0;
        document.getElementById('inputOpasitasAvg').value = overallAvg.toFixed(1);
    }

    // Initialize row calculations
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.gas-param-row').forEach(row => {
            const r1Input = row.querySelector('.gas-input-r1');
            if (r1Input) calculateGasRow(r1Input);
        });
        calculateOpacityTotal();
    });
</script>
@endsection
