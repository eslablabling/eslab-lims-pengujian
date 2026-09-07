@extends('layouts.app')

@section('title', 'Penerbitan Jadwal Pekerjaan (Form-ES-7.4.6/Rev.02)')

@section('content')

<!-- Custom Styling for Interactive Calendar & Controls (Matching LIMS Kalibrasi) -->
<style>
    .view-mode-badge {
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .view-mode-active {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }
    .view-mode-inactive {
        background: #f1f5f9;
        color: #64748b !important;
        border: 1px solid #cbd5e1;
    }
    .view-mode-inactive:hover {
        background: #e2e8f0;
        color: #1e293b !important;
    }

    /* Monthly Calendar Grid Styling */
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin-top: 16px;
    }
    .calendar-header-day {
        text-align: center;
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
        color: #475569;
        padding: 8px;
        background: #f1f5f9;
        border-radius: 8px;
    }
    .calendar-day-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        min-height: 115px;
        padding: 8px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
        position: relative;
    }
    .calendar-day-box:hover {
        border-color: #0284c7;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .calendar-day-box.is-today {
        background: #f0f9ff;
        border: 2px solid #0284c7;
    }
    .calendar-day-box.is-other-month {
        background: #f8fafc;
        opacity: 0.4;
    }
    .calendar-day-number {
        font-weight: 800;
        font-size: 13px;
        color: #1e293b;
    }
    .calendar-event-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 5px 7px;
        margin-top: 5px;
        font-size: 10.5px;
        line-height: 1.25;
        cursor: pointer;
        transition: transform 0.1s ease;
        text-align: left;
    }
    .calendar-event-card:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .calendar-event-onsite {
        border-left: 3.5px solid #d97706;
        background: #fffbeb;
    }
    .calendar-event-inlab {
        border-left: 3.5px solid #0284c7;
        background: #f0f9ff;
    }

    .btn-primary-kal {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.2);
    }
    .btn-primary-kal:hover {
        opacity: 0.95;
        color: white;
    }
</style>

<div class="card" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <!-- Header Title & View Mode Switcher -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-calendar-days" style="color:#0284c7;"></i> Penerbitan Jadwal Pekerjaan Sampling
            </h3>
            <p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Format Resmi KAN (Form-ES-7.4.6) - Jadwal Harian, Penugasan Sampler Lapangan, & Kalender Visual Bulanan</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- View Mode Switcher -->
            <a href="{{ route('pengujian.jadwal.index', array_merge(request()->query(), ['view_mode' => 'list'])) }}" class="view-mode-badge {{ $viewMode === 'list' ? 'view-mode-active' : 'view-mode-inactive' }}">
                <i class="fa-solid fa-list-check"></i> Tabel List (Harian)
            </a>
            <a href="{{ route('pengujian.jadwal.index', array_merge(request()->query(), ['view_mode' => 'calendar'])) }}" class="view-mode-badge {{ $viewMode === 'calendar' ? 'view-mode-active' : 'view-mode-inactive' }}">
                <i class="fa-solid fa-calendar-week"></i> Kalender Bulanan Visual
            </a>

            <button type="button" onclick="submitPrintSelectedForm()" class="btn-primary-kal" style="background: linear-gradient(135deg, #059669, #047857);">
                <i class="fa-solid fa-print me-1"></i> Cetak Jadwal Pekerjaan (Form-ES-7.4.6)
            </button>
        </div>
    </div>

    <!-- Datalist Master Petugas & Quotations for Autocomplete -->
    <datalist id="datalistMasterPetugas">
        @if(isset($masterPetugas))
            @foreach($masterPetugas as $pName)
                <option value="{{ $pName }}">
            @endforeach
        @endif
    </datalist>

    <datalist id="datalistQuotationFilter">
        @if(isset($ordersData))
            @foreach($ordersData as $oItem)
                <option value="{{ $oItem->no_po }}"> {{ $oItem->nama_pelanggan }} ({{ $oItem->no_order }})
            @endforeach
        @endif
    </datalist>

    <!-- Search & Filter Bar Toolbar (Matching LIMS Kalibrasi) -->
    <form action="{{ route('pengujian.jadwal.index') }}" method="GET" id="filterFormJadwal" style="background: #f8fafc; padding: 16px 20px; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
        <input type="hidden" name="view_mode" id="inputViewMode" value="{{ $viewMode }}">

        <!-- Top Search Row -->
        <div style="display: grid; grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr auto; gap: 12px; align-items: flex-end;">
            <!-- Live Search Bar -->
            <div>
                <label style="font-size: 11px; font-weight: 800; color: #475569; display: block; margin-bottom: 4px;">
                    <i class="fa-solid fa-magnifying-glass text-primary me-1"></i> Pencarian Kata Kunci:
                </label>
                <input type="text" name="q" value="{{ request('q') ?? request('search') }}" class="form-control" placeholder="Cari No PO, Customer, Lokasi, Titik..." style="font-size: 12px; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; width: 100%;">
            </div>

            <!-- 1 per 1 Quotation Selector -->
            <div>
                <label style="font-size: 11px; font-weight: 800; color: #475569; display: block; margin-bottom: 4px;">
                    <i class="fa-solid fa-file-invoice text-primary me-1"></i> Pilih 1 per 1 Quotation / PO:
                </label>
                <input type="text" name="no_quo_filter" value="{{ request('no_quo_filter') }}" class="form-control" list="datalistQuotationFilter" placeholder="Ketik / Pilih No PO / Order..." style="font-size: 12px; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; width: 100%;" onchange="document.getElementById('inputViewMode').value='list'; document.getElementById('filterFormJadwal').submit()">
            </div>

            <!-- Filter Nama Personel (Petugas) -->
            <div>
                <label style="font-size: 11px; font-weight: 800; color: #475569; display: block; margin-bottom: 4px;">
                    <i class="fa-solid fa-user-gear text-primary me-1"></i> Nama Personel:
                </label>
                <select name="petugas_filter" class="form-control" style="font-size: 12px; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; width: 100%;" onchange="this.form.submit()">
                    <option value="">Semua Personel</option>
                    <option value="unassigned" {{ request('petugas_filter') == 'unassigned' ? 'selected' : '' }}>Belum Ditugaskan</option>
                    @if(isset($masterPetugas))
                        @foreach($masterPetugas as $pName)
                            <option value="{{ $pName }}" {{ request('petugas_filter') == $pName ? 'selected' : '' }}>{{ $pName }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Filter Lokasi -->
            <div>
                <label style="font-size: 11px; font-weight: 800; color: #475569; display: block; margin-bottom: 4px;">Lokasi Pekerjaan:</label>
                <select name="lokasi" class="form-control" style="font-size: 12px; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; width: 100%;" onchange="this.form.submit()">
                    <option value="">Semua Lokasi</option>
                    <option value="On Site" {{ request('lokasi') == 'On Site' ? 'selected' : '' }}>On Site (Sampling)</option>
                    <option value="In Lab" {{ request('lokasi') == 'In Lab' ? 'selected' : '' }}>In Lab (Uji Lab)</option>
                </select>
            </div>

            <!-- Filter Tanggal Harian -->
            <div>
                <label style="font-size: 11px; font-weight: 800; color: #475569; display: block; margin-bottom: 4px;">Tanggal Jadwal:</label>
                <input type="date" name="tanggal" value="{{ $tanggalJadwal }}" class="form-control" style="font-size: 12px; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; width: 100%;" onchange="document.getElementById('inputViewMode').value='list'; this.form.submit()">
            </div>

            <!-- Filter Buttons -->
            <div style="display: flex; gap: 6px;">
                <button type="submit" class="btn btn-primary btn-sm fw-bold" style="padding: 8px 14px; font-size: 12px; border-radius: 8px; background: #0284c7; border: none; color: #ffffff; cursor: pointer;">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['q', 'search', 'no_quo_filter', 'petugas_filter', 'lokasi', 'tanggal']))
                    <a href="{{ route('pengujian.jadwal.index', ['view_mode' => $viewMode]) }}" class="btn btn-outline-secondary btn-sm fw-bold" style="padding: 8px 12px; font-size: 12px; border-radius: 8px; border: 1px solid #cbd5e1; color: #475569; text-decoration: none;" title="Reset Semua Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- IF VIEW MODE IS LIST TABLE -->
    @if($viewMode === 'list')

    <!-- Sub-Tab Category Navigation -->
    <div style="display: flex; gap: 8px; margin-bottom: 14px; flex-wrap: wrap;">
        <a href="{{ route('pengujian.jadwal.index', array_merge(request()->except('tab'), ['view_mode' => 'list', 'tab' => 'date'])) }}" class="btn btn-sm {{ $activeTab === 'date' ? 'btn-primary fw-bold' : 'btn-light border text-muted' }}" style="font-size: 12px; border-radius: 8px; padding: 6px 14px; text-decoration: none; {{ $activeTab === 'date' ? 'background: #0284c7; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
            <i class="fa-solid fa-calendar-day me-1"></i> Jadwal Terverifikasi Tanggal Ini
        </a>
        @if($hasSearchFilter)
            <a href="#" class="btn btn-sm btn-primary fw-bold" style="font-size: 12px; border-radius: 8px; padding: 6px 14px; background: #0284c7; color: white; text-decoration: none;">
                <i class="fa-solid fa-magnifying-glass me-1"></i> Hasil Pencarian ({{ $orders->count() }} Order)
            </a>
        @endif
        <a href="{{ route('pengujian.jadwal.index', array_merge(request()->except('tab'), ['view_mode' => 'list', 'tab' => 'unscheduled'])) }}" class="btn btn-sm {{ $activeTab === 'unscheduled' ? 'btn-warning text-dark fw-bold' : 'btn-light border text-muted' }}" style="font-size: 12px; border-radius: 8px; padding: 6px 14px; text-decoration: none; {{ $activeTab === 'unscheduled' ? 'background: #f59e0b; color: #1e293b;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
            <i class="fa-solid fa-clock me-1"></i> Belum Ada Jadwal / Unscheduled <span style="background: #ef4444; color: white; border-radius: 10px; padding: 2px 6px; font-size: 10px; margin-left: 4px;">{{ $unscheduledCount }}</span>
        </a>
        <a href="{{ route('pengujian.jadwal.index', array_merge(request()->except(['tab', 'tanggal']), ['view_mode' => 'list', 'tab' => 'all'])) }}" class="btn btn-sm {{ $activeTab === 'all' ? 'btn-dark fw-bold' : 'btn-light border text-muted' }}" style="font-size: 12px; border-radius: 8px; padding: 6px 14px; text-decoration: none; {{ $activeTab === 'all' ? 'background: #0f172a; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
            <i class="fa-solid fa-list me-1"></i> Tampilkan Semua Data Pengujian
        </a>
    </div>

    <!-- Inline Form Wrapper for Batch Print & Selection -->
    <form action="{{ route('pengujian.jadwal.bulk-update') }}" method="POST" id="formInlineSpreadsheet">
        @csrf

        <!-- Batch Selection Toolbar -->
        <div style="display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 12px 18px; border-radius: 12px; border: 1px solid #cbd5e1; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <span style="font-size: 12px; font-weight: 800; color: #334155;">Pilih Item Cetak:</span>
                <button type="button" onclick="selectAllCheckboxes(true)" style="padding: 4px 10px; border-radius: 6px; border: 1px solid #0284c7; background: #f0f9ff; color: #0284c7; font-weight: 700; font-size: 11px; cursor: pointer;">
                    <i class="fa-solid fa-square-check me-1"></i> Centang Semua
                </button>
                <button type="button" onclick="selectAllCheckboxes(false)" style="padding: 4px 10px; border-radius: 6px; border: 1px solid #cbd5e1; background: #f8fafc; color: #64748b; font-weight: 700; font-size: 11px; cursor: pointer;">
                    <i class="fa-solid fa-square me-1"></i> Clear
                </button>
                <span id="selectionSummaryText" style="font-size: 11.5px; font-weight: 700; color: #0369a1; margin-left: 8px;"></span>
            </div>

            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button type="submit" style="padding: 6px 14px; background: #0284c7; color: white; border: none; border-radius: 8px; font-size: 11.5px; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Batch
                </button>
            </div>
        </div>

        <div style="overflow-x: auto; width: 100%; border: 1px solid #e2e8f0; border-radius: 12px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 12px;">
                <thead>
                    <tr style="background: #f1f5f9; color: #334155; border-bottom: 2px solid #cbd5e1;">
                        <th style="padding: 10px; width: 35px; text-align: center;">
                            <input type="checkbox" onchange="selectAllCheckboxes(this.checked)">
                        </th>
                        <th style="padding: 10px; font-weight: 800; width: 16%;">No. PO / Order</th>
                        <th style="padding: 10px; font-weight: 800; width: 22%;">Nama Pelanggan & Lokasi</th>
                        <th style="padding: 10px; font-weight: 800; width: 18%;">Titik Sampling & Regulasi</th>
                        <th style="padding: 10px; font-weight: 800; width: 15%;">Jadwal Sampling</th>
                        <th style="padding: 10px; font-weight: 800; width: 15%;">Petugas Sampler</th>
                        <th style="padding: 10px; font-weight: 800; width: 14%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                    <tr class="item-row-jadwal" id="row_order_{{ $o->id }}" style="border-bottom: 1px solid #e2e8f0; transition: background 0.15s ease;">
                        <td style="padding: 10px; text-align: center;">
                            <input type="checkbox" name="selected_ids[]" value="{{ $o->id }}" class="item-checkbox" onchange="updateSelectionSummary()">
                        </td>
                        <td style="padding: 10px;">
                            <strong style="color: #0f172a; font-size: 12.5px;">{{ $o->no_po }}</strong>
                            <div style="font-size: 10.5px; color: #64748b;">{{ $o->no_order }}</div>
                            @if($o->surat_tugas_no)
                                <div style="margin-top: 4px;">
                                    <span style="background: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 6px; font-size: 9.5px; font-weight: 700;">
                                        {{ $o->surat_tugas_no }}
                                    </span>
                                </div>
                            @else
                                <div style="margin-top: 4px;">
                                    <span style="background: #fef2f2; color: #dc2626; padding: 2px 6px; border-radius: 6px; font-size: 9.5px; font-weight: 700;">
                                        Draft ST
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td style="padding: 10px;">
                            <div style="font-weight: 800; color: #0f172a; font-size: 12.5px;">{{ $o->nama_pelanggan }}</div>
                            <div style="font-size: 10.5px; color: #64748b; margin-top: 2px; line-height: 1.3;">
                                {{ Str::limit($o->alamat_pelanggan, 65) }}
                            </div>
                        </td>
                        <td style="padding: 10px;">
                            <div style="font-weight: 700; color: #0369a1;">
                                <i class="fa-solid fa-smog me-1"></i> {{ $o->items->count() }} Titik Cerobong
                            </div>
                            <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">
                                @if($o->items->first())
                                    {{ Str::limit($o->items->first()->nama_sampel, 25) }}
                                @endif
                            </div>
                        </td>
                        <td style="padding: 10px;">
                            <input type="date" 
                                   name="items[{{ $o->id }}][jadwal_sampling]" 
                                   value="{{ $o->jadwal_sampling ? \Carbon\Carbon::parse($o->jadwal_sampling)->format('Y-m-d') : '' }}" 
                                   id="input_tgl_{{ $o->id }}"
                                   style="width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11px;">
                        </td>
                        <td style="padding: 10px;">
                            <input type="text" 
                                   name="items[{{ $o->id }}][petugas_sampling]" 
                                   value="{{ $o->petugas_sampling }}" 
                                   list="datalistMasterPetugas"
                                   id="input_petugas_{{ $o->id }}"
                                   placeholder="Pilih / isi sampler..."
                                   style="width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11px;">
                        </td>
                        <td style="padding: 10px; text-align: center;">
                            <div style="display: flex; gap: 4px; justify-content: center; flex-wrap: wrap;">
                                <button type="button" onclick="openModalJadwal({{ $o->id }}, '{{ addslashes($o->nama_pelanggan) }}', '{{ $o->jadwal_sampling ? \Carbon\Carbon::parse($o->jadwal_sampling)->format('Y-m-d') : '' }}', '{{ addslashes($o->petugas_sampling ?? '') }}', '{{ addslashes($o->alamat_pelanggan ?? '') }}', '{{ addslashes($o->catatan ?? '') }}')" style="padding: 5px 10px; background: #0284c7; color: white; border: none; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Plot
                                </button>
                                @if($o->surat_tugas_no)
                                <a href="{{ route('pengujian.jadwal.print', $o->id) }}" target="_blank" style="padding: 5px 10px; background: #16a34a; color: white; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center;">
                                    <i class="fa-solid fa-print me-1"></i> Cetak
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fa-solid fa-calendar-xmark" style="font-size: 28px; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                            Tidak ada order pengujian yang cocok dengan filter jadwal saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <!-- Hidden Batch Print Form -->
    <form action="{{ route('pengujian.jadwal.print-batch') }}" method="POST" id="printBatchForm" target="_blank" style="display: none;">
        @csrf
        <div id="printBatchInputs"></div>
    </form>

    @else
    <!-- ELSE: VISUAL MONTHLY CALENDAR VIEW (Exact Match to LIMS Kalibrasi) -->
    <div style="background: #ffffff; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">
                    <i class="fa-solid fa-calendar-days text-primary me-2"></i> Visual Kalender Bulanan: {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }}
                </h4>
                <small class="text-muted" style="font-size: 12px;">(Klik tanggal atau kartu pada kalender untuk membuka Tabel List Harian pada tanggal tersebut)</small>
            </div>

            <!-- Month & Year Navigation -->
            @php
                $prevMonth = $selectedMonth - 1;
                $prevYear = $selectedYear;
                if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

                $nextMonth = $selectedMonth + 1;
                $nextYear = $selectedYear;
                if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
            @endphp
            <div style="display: flex; gap: 8px; align-items: center;">
                <a href="{{ route('pengujian.jadwal.index', array_merge(request()->query(), ['view_mode' => 'calendar', 'month' => $prevMonth, 'year' => $prevYear])) }}" class="btn btn-sm btn-outline-secondary fw-bold" style="padding: 6px 12px; border-radius: 8px; border: 1px solid #cbd5e1; color: #334155; text-decoration: none; font-size: 12px;">
                    <i class="fa-solid fa-chevron-left me-1"></i> {{ \Carbon\Carbon::createFromDate($prevYear, $prevMonth, 1)->translatedFormat('M Y') }}
                </a>
                <span class="fw-bold text-dark px-2" style="font-size: 13px;">{{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }}</span>
                <a href="{{ route('pengujian.jadwal.index', array_merge(request()->query(), ['view_mode' => 'calendar', 'month' => $nextMonth, 'year' => $nextYear])) }}" class="btn btn-sm btn-outline-secondary fw-bold" style="padding: 6px 12px; border-radius: 8px; border: 1px solid #cbd5e1; color: #334155; text-decoration: none; font-size: 12px;">
                    {{ \Carbon\Carbon::createFromDate($nextYear, $nextMonth, 1)->translatedFormat('M Y') }} <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- 7-Column Full Month Calendar Grid -->
        <div class="calendar-grid">
            <div class="calendar-header-day">Senin</div>
            <div class="calendar-header-day">Selasa</div>
            <div class="calendar-header-day">Rabu</div>
            <div class="calendar-header-day">Kamis</div>
            <div class="calendar-header-day">Jumat</div>
            <div class="calendar-header-day">Sabtu</div>
            <div class="calendar-header-day">Minggu</div>

            <!-- Empty Lead Padding Cells -->
            @for($p = 1; $p < $firstDayOfWeek; $p++)
                <div class="calendar-day-box is-other-month"></div>
            @endfor

            <!-- Days of Month Cells -->
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDateKey = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                    $isToday = ($currentDateKey === date('Y-m-d'));
                    $dayEvents = $calendarEvents[$currentDateKey] ?? [];
                @endphp
                <div class="calendar-day-box {{ $isToday ? 'is-today' : '' }}" style="cursor: pointer;" onclick="window.location.href='{{ route('pengujian.jadwal.index', array_merge(request()->query(), ['tanggal' => $currentDateKey, 'view_mode' => 'list'])) }}'">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="calendar-day-number">{{ $day }}</span>
                        @if($isToday)
                            <span class="badge bg-primary" style="font-size: 8px; background: #0284c7; color: white; padding: 2px 6px; border-radius: 6px;">HARI INI</span>
                        @endif
                    </div>

                    <div style="flex-grow: 1; overflow-y: auto; max-height: 90px; margin-top: 4px;">
                        @if(count($dayEvents) > 0)
                            @foreach($dayEvents as $evOrder)
                                @php
                                    $titikCount = $evOrder->items->count();
                                @endphp
                                <a href="{{ route('pengujian.jadwal.index', array_merge(request()->query(), ['tanggal' => $currentDateKey, 'view_mode' => 'list'])) }}" class="calendar-event-card calendar-event-onsite" style="display: block; text-decoration: none; color: inherit;" onclick="event.stopPropagation();">
                                    <strong>{{ $evOrder->no_po }}</strong><br>
                                    <small style="color: #475569;">{{ Str::limit($evOrder->nama_pelanggan, 16) }}</small><br>
                                    <span class="badge" style="font-size: 8.5px; padding: 1px 5px; background: #fef3c7; color: #92400e; border-radius: 4px; font-weight: 700;">
                                        On-site: {{ $titikCount }} Titik
                                    </span>
                                </a>
                            @endforeach
                        @endif
                    </div>

                    @if(count($dayEvents) > 0)
                        <a href="{{ route('pengujian.jadwal.index', array_merge(request()->query(), ['tanggal' => $currentDateKey, 'view_mode' => 'list'])) }}" class="btn btn-xs btn-outline-primary mt-1 w-100 fw-bold" style="font-size: 9px; padding: 2px 0; border: 1px solid #0284c7; color: #0284c7; background: #f0f9ff; border-radius: 4px; text-decoration: none; display: block; text-align: center;" onclick="event.stopPropagation();">
                            Lihat {{ count($dayEvents) }} Order
                        </a>
                    @endif
                </div>
            @endfor
        </div>
    </div>
    @endif
</div>

<!-- Modal Plotting Jadwal & Surat Tugas Sampling -->
<div id="modalJadwal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 520px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fa-solid fa-user-gear text-primary me-2" style="color: #0284c7;"></i> Penugasan & Verifikasi Jadwal
            </h3>
            <button type="button" onclick="document.getElementById('modalJadwal').style.display='none'" style="background: transparent; border: none; font-size: 18px; color: #64748b; cursor: pointer;">✕</button>
        </div>
        <p id="labelModalJadwal" style="font-size: 0.85rem; color: #64748b; margin-bottom: 16px; font-weight: 600;"></p>

        <form id="formJadwal" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 14px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">
                    <i class="fa-solid fa-calendar-day text-primary me-1" style="color: #0284c7;"></i> Tanggal Rencana Sampling *
                </label>
                <input type="date" name="jadwal_sampling" id="inputJadwalDate" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 14px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">
                    <i class="fa-solid fa-user-tag text-primary me-1" style="color: #0284c7;"></i> Petugas Sampler yang Ditugaskan *
                </label>
                <input type="text" name="petugas_sampling" id="inputPetugas" list="datalistMasterPetugas" required placeholder="Contoh: Rian Pratama, S.T. & Tim" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 14px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Lokasi / Titik Sampling Lapangan</label>
                <input type="text" name="lokasi_sampling" id="inputLokasi" placeholder="Alamat Sampling..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 18px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Catatan / Keterangan Helper / Tim Lapangan</label>
                <textarea name="catatan" id="inputCatatan" rows="2" placeholder="Catatan peralatan, armada mobil sampling, helper..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                <button type="button" onclick="document.getElementById('modalJadwal').style.display='none'" style="padding: 8px 16px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer; color: #475569;">
                    Batal
                </button>
                <button type="submit" style="padding: 8px 18px; background: linear-gradient(135deg, #0284c7, #0369a1); color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                    Terbitkan Surat Tugas Resmi
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModalJadwal(id, nama, tgl, petugas, alamat, catatan) {
        document.getElementById('labelModalJadwal').innerText = 'Pelanggan: ' + nama;
        document.getElementById('inputJadwalDate').value = tgl || '';
        document.getElementById('inputPetugas').value = petugas || '';
        document.getElementById('inputLokasi').value = alamat || '';
        document.getElementById('inputCatatan').value = catatan || '';
        document.getElementById('formJadwal').action = '/pengujian/jadwal/' + id;
        document.getElementById('modalJadwal').style.display = 'flex';
    }

    function selectAllCheckboxes(checked) {
        var checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(function(cb) {
            cb.checked = checked;
        });
        updateSelectionSummary();
    }

    function updateSelectionSummary() {
        var checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
        var summaryEl = document.getElementById('selectionSummaryText');
        if (summaryEl) {
            summaryEl.innerText = checkedCount > 0 ? 'Terpilih: ' + checkedCount + ' order untuk dicetak' : '';
        }
    }

    function submitPrintSelectedForm() {
        var checkedCbs = document.querySelectorAll('.item-checkbox:checked');
        var form = document.getElementById('printBatchForm');
        var inputsContainer = document.getElementById('printBatchInputs');
        inputsContainer.innerHTML = '';

        if (checkedCbs.length === 0) {
            if (confirm('Belum ada order yang dicentang. Apakah Anda ingin mencetak seluruh jadwal sesuai tanggal/filter saat ini?')) {
                var hiddenDate = document.createElement('input');
                hiddenDate.type = 'hidden';
                hiddenDate.name = 'tanggal';
                hiddenDate.value = '{{ $tanggalJadwal }}';
                inputsContainer.appendChild(hiddenDate);
                form.submit();
            }
        } else {
            checkedCbs.forEach(function(cb) {
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'selected_ids[]';
                inp.value = cb.value;
                inputsContainer.appendChild(inp);
            });
            form.submit();
        }
    }
</script>
@endpush
