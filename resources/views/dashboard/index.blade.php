@extends('layouts.app')

@section('title', 'LIMS Dashboard | PT Envirotama Solusindo')

@section('content')
<!-- Include SheetJS for Excel Export -->
<script src="{{ asset('vendor/xlsx/xlsx.full.min.js') }}"></script>

<style>
    /* --- DASHBOARD STYLES MATCHING DASHBOARD.HTML --- */
    .dashboard-top-row {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) {
        .dashboard-top-row {
            grid-template-columns: 1fr;
        }
    }

    .weather-card {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
        padding: 24px;
        border-radius: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
    }
    .weather-info h3 { font-size: 1.7rem; font-weight: 800; margin: 6px 0; }
    .weather-info p { font-size: 0.8rem; opacity: 0.9; font-weight: 600; }

    .chart-placeholder {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        border-radius: 18px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .stat-card:hover { 
        transform: translateY(-3px); 
        border-color: #cbd5e1; 
        box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.06);
    }
    .stat-card h4 { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px; font-weight: 800; letter-spacing: 0.5px; }
    .stat-card .number { font-size: 1.8rem; font-weight: 800; color: #0f172a; }

    /* Data Table Container */
    .data-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03);
        margin-bottom: 25px;
    }

    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { padding: 14px 12px; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 800; border-bottom: 2px solid #f1f5f9; }
    td { padding: 14px 12px; font-size: 0.85rem; border-bottom: 1px solid #f1f5f9; color: #334155; }
    tbody tr { transition: background-color 0.15s ease-in-out; }
    tbody tr:hover { background-color: #f8fafc; }

    /* Status Tags */
    .tag {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }
    .tag-orange { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; } /* SAMPLING */
    .tag-blue { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }   /* ANALISA */
    .tag-green { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }  /* FINISH */

    /* Pagination Buttons */
    .page-btn {
        padding: 7px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        font-weight: 700;
        cursor: pointer;
        color: #64748b;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    .page-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }
    .page-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    .page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-period-opt {
        padding: 8px 14px;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        font-size: 0.8rem;
        border: 1px solid #cbd5e1;
        background: white;
        color: #64748b;
        transition: all 0.2s;
    }
    .btn-period-opt.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
</style>

@if(auth()->check() && auth()->user()->hasRole('admin_master'))
<!-- Banner Master Dashboard Terpadu -->
<div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 20px; padding: 24px; color: white; margin-bottom: 24px; border: 1px solid #334155; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
        <div>
            <span style="background: #38bdf8; color: #0f172a; font-size: 0.65rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Panel Terpadu Admin Master</span>
            <h3 style="font-size: 1.4rem; font-weight: 800; margin-top: 6px; color: #ffffff;">Executive Portal LIMS Envirotama Solusindo</h3>
            <p style="font-size: 0.82rem; color: #94a3b8; margin-top: 2px;">Satu sesi login terpadu untuk mengelola seluruh laboratorium pengujian dan kalibrasi KAN.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('kalibrasi.dashboard') }}" style="background: #0284c7; color: white; padding: 10px 18px; border-radius: 12px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                ⚖️ Buka LIMS Kalibrasi LK-361
            </a>
            <a href="{{ route('kalibrasi.network-monitor.index') }}" style="background: #dc2626; color: white; padding: 10px 18px; border-radius: 12px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                🔒 Security Monitor
            </a>
        </div>
    </div>
</div>
@endif

<!-- Top Row: Lab Overview Card & Status Doughnut Chart -->
<div class="dashboard-top-row">
    <!-- Lab Status Widget -->
    <div class="weather-card">
        <div class="weather-info">
            <p style="text-transform: uppercase; letter-spacing: 1px; font-size: 0.65rem; font-weight: 800; opacity: 0.9;">Laboratory Information Management System</p>
            <h3 style="font-size: 1.6rem; font-weight: 800; margin: 5px 0;">PT Envirotama Solusindo</h3>
            <div style="display: flex; gap: 15px; margin-top: 15px; flex-wrap: wrap;">
                <div style="background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 8px;">
                    <span style="font-size: 0.65rem; opacity: 0.95; font-weight: 700; text-transform: uppercase;">Laboratorium Lingkungan Terakreditasi KAN LP-1813-IDN</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Chart Widget -->
    <div class="chart-placeholder" style="height: auto; min-height: 170px;">
        <h4 style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px; font-weight: 800; letter-spacing: 0.5px;">Status Sampel</h4>
        <div style="position: relative; height: 110px; width: 100%; display: flex; align-items: center; justify-content: center;">
            <canvas id="statusChart" style="max-height: 100px; max-width: 100%;"></canvas>
        </div>
    </div>
</div>

<!-- Ringkasan Statistik Sampel with Month & Year Filters -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0;">
        📊 Ringkasan Statistik Sampel
    </h3>
    <div style="display: flex; gap: 10px; align-items: center;">
        <select id="filterStatsMonth" style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.85rem; font-weight: 700; color: #1e293b; background: white; cursor: pointer; outline: none;">
            <option value="all">Semua Bulan</option>
            <option value="0">Januari</option>
            <option value="1">Februari</option>
            <option value="2">Maret</option>
            <option value="3">April</option>
            <option value="4">Mei</option>
            <option value="5">Juni</option>
            <option value="6">Juli</option>
            <option value="7">Agustus</option>
            <option value="8">September</option>
            <option value="9">Oktober</option>
            <option value="10">November</option>
            <option value="11">Desember</option>
        </select>
        <select id="filterStatsYear" style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.85rem; font-weight: 700; color: #1e293b; background: white; cursor: pointer; outline: none;">
            <option value="all">Semua Tahun</option>
        </select>
    </div>
</div>

<div id="statsContainer" class="stats-grid" style="margin-top: 10px; margin-bottom: 25px;">
    <!-- Rendered dynamically by JavaScript -->
</div>

<!-- Ringkasan Status Peralatan Inventaris -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; margin-bottom: 12px; flex-wrap: wrap; gap: 15px;">
    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0;">
        🔧 Ringkasan Status Peralatan Inventaris
    </h3>
    <a href="{{ route('pengujian.peralatan.index') }}" style="font-size: 0.8rem; font-weight: 700; color: #2563eb; text-decoration: none;" title="Kelola Inventaris">
        Lihat Detail Inventaris ➔
    </a>
</div>

<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 30px;">
    <div class="stat-card" style="background: white; border: 1px solid #e2e8f0; padding: 16px 20px; border-radius: 16px; display: flex; align-items: center; gap: 16px;">
        <div style="background: #eff6ff; color: #2563eb; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">🔧</div>
        <div>
            <h4 style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 800; margin: 0 0 4px 0;">Total Inventaris</h4>
            <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ number_format($totalAlat) }}</div>
        </div>
    </div>
    <div class="stat-card" style="background: white; border: 1px solid #e2e8f0; padding: 16px 20px; border-radius: 16px; display: flex; align-items: center; gap: 16px;">
        <div style="background: #dcfce7; color: #16a34a; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">✅</div>
        <div>
            <h4 style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 800; margin: 0 0 4px 0;">Siap Pakai (Baik)</h4>
            <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ number_format($alatBaik) }}</div>
        </div>
    </div>
    <div class="stat-card" style="background: white; border: 1px solid #e2e8f0; padding: 16px 20px; border-radius: 16px; display: flex; align-items: center; gap: 16px;">
        <div style="background: #fef9c3; color: #ca8a04; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">⚠️</div>
        <div>
            <h4 style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 800; margin: 0 0 4px 0;">Warning Kalibrasi</h4>
            <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ number_format($warningKalibrasi) }}</div>
        </div>
    </div>
    <div class="stat-card" style="background: white; border: 1px solid #e2e8f0; padding: 16px 20px; border-radius: 16px; display: flex; align-items: center; gap: 16px;">
        <div style="background: #fee2e2; color: #dc2626; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">🚨</div>
        <div>
            <h4 style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 800; margin: 0 0 4px 0;">Expired / Perlu Kalibrasi</h4>
            <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ number_format($expiredKalibrasi) }}</div>
        </div>
    </div>
</div>

<!-- Log Sampel COC Emisi (Data Table Container) -->
<div class="data-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">📋 Log Sampel COC Emisi</h3>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- Filter Rentang Tanggal Sampling -->
            <div style="display: flex; align-items: center; gap: 6px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 8px 14px;">
                <span style="font-size: 0.72rem; font-weight: 800; color: #64748b; white-space: nowrap;">📅 Dari</span>
                <input type="date" id="filterDashDateStart" style="border: none; background: transparent; outline: none; font-size: 0.8rem; font-family: inherit; color: #1e293b;">
                <span style="font-size: 0.72rem; font-weight: 800; color: #64748b;">s/d</span>
                <input type="date" id="filterDashDateEnd" style="border: none; background: transparent; outline: none; font-size: 0.8rem; font-family: inherit; color: #1e293b;">
                <button id="btnResetDashDate" title="Reset filter tanggal" style="background: none; border: none; cursor: pointer; font-size: 0.9rem; color: #94a3b8; padding: 0; line-height: 1;">✕</button>
            </div>
            <input type="text" id="searchLog" placeholder="Cari No. COC, ID Sampel, Perusahaan, atau Status..." 
                   style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; width: 280px; font-size: 0.85rem; outline: none;">
            <button onclick="resetTableSort()" style="padding: 10px 16px; border-radius: 12px; font-weight: 700; cursor: pointer; font-size: 0.85rem; display: flex; align-items: center; gap: 5px; border: 1px solid #cbd5e1; background: white;" title="Reset Pengurutan ke Default">
                🔄 Reset Sort
            </button>
            <button id="btnExportDashboard" style="padding: 10px 16px; background: #16a34a; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-size: 0.85rem; display: flex; align-items: center; gap: 5px;">
                📥 Ekspor Excel
            </button>
        </div>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th onclick="handleHeaderSort('nomor_coc')" style="cursor: pointer; user-select: none;" id="hdrCoc">No. COC ⇅</th>
                    <th onclick="handleHeaderSort('sample_id')" style="cursor: pointer; user-select: none;" id="hdrSample">ID Sampel ⇅</th>
                    <th onclick="handleHeaderSort('company_name')" style="cursor: pointer; user-select: none;" id="hdrCompany">Nama Perusahaan ⇅</th>
                    <th onclick="handleHeaderSort('sampling_date')" style="cursor: pointer; user-select: none;" id="hdrTglSampling">Tgl. Sampling ⇅</th>
                    <th onclick="handleHeaderSort('tgl_terima_lab')" style="cursor: pointer; user-select: none;" id="hdrTglTerima">Tgl. Terima ⇅</th>
                    <th onclick="handleHeaderSort('tgl_selesai')" style="cursor: pointer; user-select: none;" id="hdrTglSelesai">Tgl. Analisa Selesai ⇅</th> 
                    <th onclick="handleHeaderSort('tat')" style="cursor: pointer; user-select: none;" id="hdrTat">TAT ⇅</th> 
                    <th onclick="handleHeaderSort('status')" style="cursor: pointer; user-select: none;" id="statusHeader">Status ⇅</th>
                </tr>
            </thead>
            <tbody id="logTableBody">
                <!-- Rendered dynamically by JavaScript -->
            </tbody>
        </table>
    </div>
    
    <!-- Panel Paginasi -->
    <div id="paginationControls" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 0.85rem; flex-wrap: wrap; gap: 10px;">
        <div id="paginationInfo" style="color: #64748b; font-weight: 600;">
            Menampilkan 0 - 0 dari 0 data
        </div>
        <div style="display: flex; gap: 5px; align-items: center;">
            <button id="btnPrevPage" class="page-btn">
                Sebelumnya
            </button>
            <div id="pageNumbers" style="display: flex; gap: 5px;">
                <!-- Halaman akan di-render di sini -->
            </div>
            <button id="btnNextPage" class="page-btn">
                Berikutnya
            </button>
        </div>
    </div>
</div>

<!-- Analisis TAT & Kepatuhan Lateness Widget -->
<div class="data-container" style="min-height: 400px; display: flex; flex-direction: column;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">📊 Analisis Turnaround Time (TAT) & Keterlambatan</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">Pantau tingkat kepatuhan durasi pengerjaan sampel lab berdasarkan tenggat waktu.</p>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <button onclick="changeTatPeriod('daily')" id="btnTatDaily" class="btn-period-opt active">Perhari</button>
            <button onclick="changeTatPeriod('monthly')" id="btnTatMonthly" class="btn-period-opt">Perbulan</button>
            <button onclick="changeTatPeriod('yearly')" id="btnTatYearly" class="btn-period-opt">Pertahun</button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; flex-grow: 1; flex-wrap: wrap;">
        <!-- Area Grafik -->
        <div style="position: relative; height: 320px; background: #f8fafc; border-radius: 16px; padding: 15px; border: 1px solid #e2e8f0; width: 100%;">
            <canvas id="tatTrendChart" style="width: 100%; height: 100%;"></canvas>
        </div>
        
        <!-- Area Summary Info -->
        <div style="display: flex; flex-direction: column; gap: 10px; justify-content: center;">
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 12px 16px; border-radius: 14px; display: flex; flex-direction: column; gap: 2px;">
                <span style="font-size: 0.7rem; color: #065f46; font-weight: 800; text-transform: uppercase;">Persentase Tepat Waktu</span>
                <strong id="tatOnTimeRate" style="font-size: 1.3rem; color: #047857; font-weight: 800;">0%</strong>
                <span style="font-size: 0.65rem; color: #047857; font-weight: 600;">Menyelesaikan pengujian di bawah batas target</span>
            </div>
            <div style="background: #fff5f5; border: 1px solid #fee2e2; padding: 12px 16px; border-radius: 14px; display: flex; flex-direction: column; gap: 2px;">
                <span style="font-size: 0.7rem; color: #991b1b; font-weight: 800; text-transform: uppercase;">Total Keterlambatan</span>
                <strong id="tatDelayCount" style="font-size: 1.3rem; color: #b91c1c; font-weight: 800;">0 Sampel</strong>
                <span style="font-size: 0.65rem; color: #b91c1c; font-weight: 600;">Jumlah sampel yang melewati batas toleransi pengerjaan</span>
            </div>
            <div style="background: #fff7ed; border: 1px solid #ffedd5; padding: 12px 16px; border-radius: 14px; display: flex; flex-direction: column; gap: 2px;">
                <span style="font-size: 0.7rem; color: #9a3412; font-weight: 800; text-transform: uppercase;">Rata-Rata Durasi Pengerjaan</span>
                <strong id="tatAverageDuration" style="font-size: 1.3rem; color: #c2410c; font-weight: 800;">0 Hari</strong>
                <span style="font-size: 0.65rem; color: #c2410c; font-weight: 600;">Rata-rata waktu penyelesaian keseluruhan sampel</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // State Halaman Dashboard
    let allSamplesList = @json($activeSamples);
    let filteredSamplesList = [...allSamplesList];
    let currentPage = 1;
    const pageSize = 10;
    let sortState = { col: 'default', dir: 'none' };
    let statusChartInstance = null;
    let tatChartInstance = null;
    let currentTatPeriod = 'daily';

    document.addEventListener('DOMContentLoaded', () => {
        populateYearFilter();
        applyDashboardFilters();
        updateTatAnalysis(allSamplesList);

        // Setup event listeners
        const searchLog = document.getElementById('searchLog');
        if (searchLog) {
            searchLog.addEventListener('input', (e) => {
                handleSearch(e.target.value);
            });
        }

        const filterStatsMonth = document.getElementById('filterStatsMonth');
        if (filterStatsMonth) {
            filterStatsMonth.addEventListener('change', applyDashboardFilters);
        }

        const filterStatsYear = document.getElementById('filterStatsYear');
        if (filterStatsYear) {
            filterStatsYear.addEventListener('change', applyDashboardFilters);
        }

        const btnPrevPage = document.getElementById('btnPrevPage');
        if (btnPrevPage) {
            btnPrevPage.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTableRows();
                    renderPaginationControls();
                }
            });
        }

        const btnNextPage = document.getElementById('btnNextPage');
        if (btnNextPage) {
            btnNextPage.addEventListener('click', () => {
                const totalPages = Math.ceil(filteredSamplesList.length / pageSize);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTableRows();
                    renderPaginationControls();
                }
            });
        }

        const btnExportDashboard = document.getElementById('btnExportDashboard');
        if (btnExportDashboard) {
            btnExportDashboard.addEventListener('click', exportDashboardToExcel);
        }

        const filterDashDateStart = document.getElementById('filterDashDateStart');
        const filterDashDateEnd = document.getElementById('filterDashDateEnd');
        const btnResetDashDate = document.getElementById('btnResetDashDate');
        if (filterDashDateStart) filterDashDateStart.addEventListener('change', applyDashboardFilters);
        if (filterDashDateEnd) filterDashDateEnd.addEventListener('change', applyDashboardFilters);
        if (btnResetDashDate) {
            btnResetDashDate.addEventListener('click', () => {
                if (filterDashDateStart) filterDashDateStart.value = '';
                if (filterDashDateEnd) filterDashDateEnd.value = '';
                applyDashboardFilters();
            });
        }
    });

    // Populate dynamic years into Year filter
    function populateYearFilter() {
        const yearSelect = document.getElementById('filterStatsYear');
        if (!yearSelect) return;

        const currentYear = new Date().getFullYear();
        const years = new Set([currentYear, currentYear - 1, currentYear + 1]);

        allSamplesList.forEach(s => {
            if (s.sampling_date) {
                const y = new Date(s.sampling_date).getFullYear();
                if (!isNaN(y)) years.add(y);
            }
        });

        const sortedYears = Array.from(years).sort((a, b) => b - a);
        yearSelect.innerHTML = `<option value="all">Semua Tahun</option>`;
        sortedYears.forEach(y => {
            yearSelect.innerHTML += `<option value="${y}">${y}</option>`;
        });
    }

    // Apply dashboard filters
    function applyDashboardFilters() {
        const monthVal = document.getElementById('filterStatsMonth') ? document.getElementById('filterStatsMonth').value : 'all';
        const yearVal = document.getElementById('filterStatsYear') ? document.getElementById('filterStatsYear').value : 'all';
        const dateStartVal = document.getElementById('filterDashDateStart') ? document.getElementById('filterDashDateStart').value : '';
        const dateEndVal = document.getElementById('filterDashDateEnd') ? document.getElementById('filterDashDateEnd').value : '';
        const searchVal = document.getElementById('searchLog') ? document.getElementById('searchLog').value.toLowerCase().trim() : '';

        filteredSamplesList = allSamplesList.filter(s => {
            // Month filter
            if (monthVal !== 'all' && s.sampling_date) {
                const sMonth = new Date(s.sampling_date).getMonth();
                if (sMonth !== parseInt(monthVal)) return false;
            }

            // Year filter
            if (yearVal !== 'all' && s.sampling_date) {
                const sYear = new Date(s.sampling_date).getFullYear();
                if (sYear !== parseInt(yearVal)) return false;
            }

            // Date Range
            if (dateStartVal && s.sampling_date && s.sampling_date < dateStartVal) return false;
            if (dateEndVal && s.sampling_date && s.sampling_date > dateEndVal) return false;

            // Search Filter
            if (searchVal) {
                const matchStr = `${s.nomor_coc} ${s.sample_id} ${s.company_name} ${s.statusLabel}`.toLowerCase();
                if (!matchStr.includes(searchVal)) return false;
            }

            return true;
        });

        currentPage = 1;
        updateStats(filteredSamplesList);
        renderStatusChart(filteredSamplesList);
        renderTableRows();
        renderPaginationControls();
        updateTatAnalysis(filteredSamplesList);
    }

    function handleSearch(val) {
        applyDashboardFilters();
    }

    // Update 6 Stat Cards
    function updateStats(samplesList = allSamplesList) {
        const statsContainer = document.getElementById('statsContainer');
        if (!statsContainer) return;

        const totalSamples = samplesList.length;
        const samplingCount = samplesList.filter(s => s.statusLabel === 'SAMPLING').length;
        const analisaCount = samplesList.filter(s => s.statusLabel === 'ANALISA').length;
        const finishCount = samplesList.filter(s => s.statusLabel === 'FINISH').length;
        const uniqueCompanies = new Set(samplesList.map(s => s.company_name).filter(name => name && name !== '-')).size;
        const uniqueCocs = new Set(samplesList.map(s => s.nomor_coc).filter(coc => coc && coc !== '-')).size;

        statsContainer.innerHTML = `
            <div class="stat-card" style="border-left: 4px solid #64748b;" onclick="window.location.href='{{ route('pengujian.sampling.index') }}'">
                <h4>Total Sampel</h4>
                <div class="number" style="color: #1e293b;">${totalSamples}</div>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 4px; font-weight: 600;">Terdaftar di sistem LIMS</p>
            </div>
            <div class="stat-card" style="border-left: 4px solid #f97316;" onclick="window.location.href='{{ route('pengujian.sampling.index') }}'">
                <h4>Proses Sampling</h4>
                <div class="number" style="color: #ea580c;">${samplingCount}</div>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 4px; font-weight: 600;">Persiapan & Data Lapangan</p>
            </div>
            <div class="stat-card" style="border-left: 4px solid #2563eb;" onclick="window.location.href='{{ route('pengujian.analisa.index') }}'">
                <h4>Proses Analisa</h4>
                <div class="number" style="color: #1d4ed8;">${analisaCount}</div>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 4px; font-weight: 600;">Tahap pengujian lab</p>
            </div>
            <div class="stat-card" style="border-left: 4px solid #16a34a;" onclick="window.location.href='{{ route('pengujian.coa.index') }}'">
                <h4>Verifikasi & COA</h4>
                <div class="number" style="color: #15803d;">${finishCount}</div>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 4px; font-weight: 600;">Tervalidasi & COA terbit</p>
            </div>
            <div class="stat-card" style="border-left: 4px solid #8b5cf6;" onclick="window.location.href='{{ route('pengujian.kelola-klien.index') }}'">
                <h4>Jumlah Perusahaan</h4>
                <div class="number" style="color: #6d28d9;">${uniqueCompanies}</div>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 4px; font-weight: 600;">Perusahaan terlayani</p>
            </div>
            <div class="stat-card" style="border-left: 4px solid #06b6d4;" onclick="window.location.href='{{ route('pengujian.coc.index') }}'">
                <h4>Jumlah COC</h4>
                <div class="number" style="color: #0e7490;">${uniqueCocs}</div>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 4px; font-weight: 600;">Chain of Custody terbit</p>
            </div>
        `;
    }

    // Render Status Donut Chart
    function renderStatusChart(samplesList = allSamplesList) {
        const ctx = document.getElementById('statusChart');
        if (!ctx) return;

        const samplingCount = samplesList.filter(s => s.statusLabel === 'SAMPLING').length;
        const analisaCount = samplesList.filter(s => s.statusLabel === 'ANALISA').length;
        const finishCount = samplesList.filter(s => s.statusLabel === 'FINISH').length;

        if (statusChartInstance) {
            statusChartInstance.destroy();
        }

        const hasData = (samplingCount + analisaCount + finishCount) > 0;
        const dataValues = hasData ? [samplingCount, analisaCount, finishCount] : [1, 1, 1];

        statusChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Sampling', 'Analisa', 'Selesai'],
                datasets: [{
                    data: dataValues,
                    backgroundColor: hasData ? ['#ea580c', '#2563eb', '#16a34a'] : ['#e2e8f0', '#cbd5e1', '#94a3b8'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 10,
                            padding: 8,
                            font: { family: 'Plus Jakarta Sans', size: 9, weight: '700' }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    // Render Table Rows with Pagination & Sorting
    function renderTableRows() {
        const tbody = document.getElementById('logTableBody');
        if (!tbody) return;

        if (filteredSamplesList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:30px; color:#94a3b8;">Tidak ada data sampel yang cocok dengan filter.</td></tr>`;
            return;
        }

        // Sort data
        let sorted = [...filteredSamplesList];
        if (sortState.dir !== 'none') {
            sorted.sort((a, b) => {
                let vA = a[sortState.col] || '';
                let vB = b[sortState.col] || '';
                if (typeof vA === 'string') vA = vA.toLowerCase();
                if (typeof vB === 'string') vB = vB.toLowerCase();

                if (vA < vB) return sortState.dir === 'asc' ? -1 : 1;
                if (vA > vB) return sortState.dir === 'asc' ? 1 : -1;
                return 0;
            });
        }

        const startIdx = (currentPage - 1) * pageSize;
        const pageRows = sorted.slice(startIdx, startIdx + pageSize);

        let html = '';
        pageRows.forEach(s => {
            html += `
                <tr>
                    <td><strong style="color: #0284c7; font-family: monospace;">${escapeHtml(s.nomor_coc)}</strong></td>
                    <td><span style="font-family: monospace; font-weight: 700; color: #334155;">${escapeHtml(s.sample_id)}</span></td>
                    <td><div style="font-weight: 700;">${escapeHtml(s.company_name)}</div></td>
                    <td>${escapeHtml(s.tglSampling)}</td>
                    <td>${escapeHtml(s.tglTerima)}</td>
                    <td>${escapeHtml(s.tglSelesai)}</td>
                    <td>
                        <span style="font-weight: 700; ${s.isDelayed ? 'color:#dc2626;' : 'color:#16a34a;'}">${escapeHtml(s.tatHari)}</span>
                        ${s.isUrgent ? '<span style="font-size:0.65rem; background:#fee2e2; color:#dc2626; padding:2px 4px; border-radius:4px; margin-left:4px; font-weight:800;">URGENT</span>' : ''}
                    </td>
                    <td>
                        <span class="tag ${s.statusClass}">
                            ${s.statusLabel}
                        </span>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function renderPaginationControls() {
        const info = document.getElementById('paginationInfo');
        const numbers = document.getElementById('pageNumbers');
        const btnPrev = document.getElementById('btnPrevPage');
        const btnNext = document.getElementById('btnNextPage');
        if (!info || !numbers) return;

        const totalItems = filteredSamplesList.length;
        const totalPages = Math.ceil(totalItems / pageSize) || 1;
        const startItem = totalItems === 0 ? 0 : (currentPage - 1) * pageSize + 1;
        const endItem = Math.min(currentPage * pageSize, totalItems);

        info.textContent = `Menampilkan ${startItem} - ${endItem} dari ${totalItems} data`;

        btnPrev.disabled = currentPage <= 1;
        btnNext.disabled = currentPage >= totalPages;

        let numsHtml = '';
        for (let p = 1; p <= totalPages; p++) {
            if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                numsHtml += `<button class="page-btn ${p === currentPage ? 'active' : ''}" onclick="goToPage(${p})">${p}</button>`;
            } else if (p === currentPage - 2 || p === currentPage + 2) {
                numsHtml += `<span style="padding:4px 8px; color:#94a3b8;">...</span>`;
            }
        }
        numbers.innerHTML = numsHtml;
    }

    function goToPage(p) {
        currentPage = p;
        renderTableRows();
        renderPaginationControls();
    }

    function handleHeaderSort(colKey) {
        if (sortState.col === colKey) {
            sortState.dir = sortState.dir === 'asc' ? 'desc' : (sortState.dir === 'desc' ? 'none' : 'asc');
        } else {
            sortState.col = colKey;
            sortState.dir = 'asc';
        }
        renderTableRows();
    }

    function resetTableSort() {
        sortState = { col: 'default', dir: 'none' };
        renderTableRows();
    }

    // Ekspor Excel via SheetJS
    function exportDashboardToExcel() {
        if (filteredSamplesList.length === 0) {
            alert('Tidak ada data sampel untuk diekspor.');
            return;
        }

        const exportData = filteredSamplesList.map(s => ({
            'No. COC': s.nomor_coc,
            'ID Sampel': s.sample_id,
            'Nama Perusahaan': s.company_name,
            'Tgl. Sampling': s.tglSampling,
            'Tgl. Terima Lab': s.tglTerima,
            'Tgl. Selesai Analisa': s.tglSelesai,
            'Turnaround Time (TAT)': s.tatHari,
            'Status': s.statusLabel
        }));

        const ws = XLSX.utils.json_to_sheet(exportData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Log Sampel COC");
        XLSX.writeFile(wb, `Log_Sampel_LIMS_Envirotama_${new Date().toISOString().slice(0,10)}.xlsx`);
    }

    // TAT Analysis & Trend Chart
    function changeTatPeriod(period) {
        currentTatPeriod = period;
        document.querySelectorAll('.btn-period-opt').forEach(btn => btn.classList.remove('active'));
        if (period === 'daily') document.getElementById('btnTatDaily').classList.add('active');
        if (period === 'monthly') document.getElementById('btnTatMonthly').classList.add('active');
        if (period === 'yearly') document.getElementById('btnTatYearly').classList.add('active');

        updateTatAnalysis(filteredSamplesList);
    }

    function updateTatAnalysis(samplesList) {
        const verified = samplesList.filter(s => s.statusLabel === 'FINISH' && s.actualDuration !== null);
        const total = verified.length;
        const onTime = verified.filter(s => !s.isDelayed).length;
        const delayed = verified.filter(s => s.isDelayed).length;
        const totalDur = verified.reduce((acc, curr) => acc + (curr.actualDuration || 0), 0);

        const onTimeRate = total > 0 ? ((onTime / total) * 100).toFixed(1) : '100.0';
        const avgDur = total > 0 ? (totalDur / total).toFixed(1) : '0.0';

        document.getElementById('tatOnTimeRate').textContent = `${onTimeRate}%`;
        document.getElementById('tatDelayCount').textContent = `${delayed} Sampel`;
        document.getElementById('tatAverageDuration').textContent = `${avgDur} Hari`;

        renderTatTrendChart(samplesList, currentTatPeriod);
    }

    function renderTatTrendChart(samplesList, period) {
        const ctx = document.getElementById('tatTrendChart');
        if (!ctx) return;

        if (tatChartInstance) {
            tatChartInstance.destroy();
        }

        // Group data by period
        const groups = {};
        samplesList.forEach(s => {
            if (!s.sampling_date) return;
            const d = new Date(s.sampling_date);
            let key = '';
            if (period === 'daily') {
                key = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            } else if (period === 'yearly') {
                key = d.getFullYear().toString();
            } else {
                key = d.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
            }

            if (!groups[key]) {
                groups[key] = { total: 0, onTime: 0, delayed: 0 };
            }
            groups[key].total++;
            if (s.isDelayed) groups[key].delayed++;
            else groups[key].onTime++;
        });

        const labels = Object.keys(groups);
        const onTimeData = labels.map(k => groups[k].onTime);
        const delayData = labels.map(k => groups[k].delayed);

        tatChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.length > 0 ? labels : ['Belum Ada Data'],
                datasets: [
                    {
                        label: 'Tepat Waktu',
                        data: onTimeData.length > 0 ? onTimeData : [0],
                        backgroundColor: '#16a34a',
                        borderRadius: 6
                    },
                    {
                        label: 'Keterlambatan',
                        data: delayData.length > 0 ? delayData : [0],
                        backgroundColor: '#ef4444',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true, grid: { display: false } },
                    y: { stacked: true, beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } }
                },
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { weight: 'bold', family: 'Plus Jakarta Sans' } } }
                }
            }
        });
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
@endpush
