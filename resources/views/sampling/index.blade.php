@extends('layouts.app')

@section('title', 'Monitoring Sampling Lapangan')

@section('content')
<style>
    .status-tab-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .status-tab-btn {
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        color: #64748b;
        transition: all 0.2s;
    }
    .status-tab-btn.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .sampling-box {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .filter-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .sample-badge-insitu {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .btn-input-sampling {
        padding: 8px 14px;
        background: #2563eb;
        color: #ffffff;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
    }
    .btn-input-sampling:hover {
        background: #1d4ed8;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📍 Monitoring Sampling Lapangan</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Input dan monitoring data teknis lapangan, meteorologi, gas direct reading, dan opasitas cerobong.</p>
    </div>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; font-size: 0.88rem; display: flex; align-items: center; gap: 10px;">
    <span style="font-size: 1.2rem;">✓</span>
    <div>{{ session('success') }}</div>
</div>
@endif

<!-- Status Filter Tabs -->
<div class="status-tab-nav">
    <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.index' : 'sampling.index', ['status' => 'aktif', 'search' => $search, 'coc_id' => $cocFilter]) }}" 
       class="status-tab-btn {{ $filterStatus === 'aktif' ? 'active' : '' }}">
        ⏳ Menunggu / Proses Sampling ({{ $countAktif }})
    </a>
    <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.index' : 'sampling.index', ['status' => 'selesai', 'search' => $search, 'coc_id' => $cocFilter]) }}" 
       class="status-tab-btn {{ $filterStatus === 'selesai' ? 'active' : '' }}">
        ✅ Selesai Sampling / Di Lab ({{ $countSelesai }})
    </a>
    <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.index' : 'sampling.index', ['status' => 'semua', 'search' => $search, 'coc_id' => $cocFilter]) }}" 
       class="status-tab-btn {{ $filterStatus === 'semua' ? 'active' : '' }}">
        📋 Semua Sampel ({{ $countTotal }})
    </a>
</div>

<div class="sampling-box">
    <!-- Filter Search Form -->
    <form method="GET" action="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.index' : 'sampling.index') }}" class="filter-bar">
        <input type="hidden" name="status" value="{{ $filterStatus }}">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID Sampel, No. COC, Perusahaan, Cerobong..." class="form-control" style="flex: 2; min-width: 220px; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.88rem;">
        
        <select name="coc_id" class="form-control" style="flex: 1.5; min-width: 200px; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.88rem;">
            <option value="">-- Semua Dokumen COC --</option>
            @foreach($cocs as $coc)
                <option value="{{ $coc->id }}" {{ (string)$cocFilter === (string)$coc->id ? 'selected' : '' }}>
                    {{ $coc->nomor_coc }} - {{ Str::limit($coc->company_name, 25) }}
                </option>
            @endforeach
        </select>

        <button type="submit" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
            🔍 Filter
        </button>

        @if($search || $cocFilter)
        <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.index' : 'sampling.index', ['status' => $filterStatus]) }}" style="padding: 10px 16px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center;">
            Reset
        </a>
        @endif
    </form>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th style="width: 140px;">Sample ID</th>
                    <th style="width: 150px;">No. COC</th>
                    <th>Nama Perusahaan & Lokasi</th>
                    <th>Titik Cerobong</th>
                    <th>Tgl. Sampling</th>
                    <th>Parameter Insitu</th>
                    <th>Status</th>
                    <th style="text-align: center; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($samples as $s)
                <tr>
                    <td>
                        <strong style="color: #0284c7; font-family: monospace; font-size: 0.95rem;">{{ $s->sample_id }}</strong>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: #334155;">{{ $s->coc?->nomor_coc ?? '-' }}</span>
                        <div style="font-size: 0.72rem; color: #64748b;">Petugas: {{ $s->coc?->sampling_officer ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #0f172a;">{{ $s->coc?->company_name ?? '-' }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $s->coc?->sampling_location ?? $s->coc?->alamat_perusahaan ?? '-' }}</div>
                    </td>
                    <td>
                        <strong style="color: #0f172a;">{{ $s->nama_cerobong ?? $s->description ?? '-' }}</strong>
                        @if($s->bahan_bakar)
                        <div style="font-size: 0.72rem; color: #64748b;">Bahan Bakar: {{ $s->bahan_bakar }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 700;">{{ $s->coc?->sampling_date ? \Carbon\Carbon::parse($s->coc->sampling_date)->translatedFormat('d M Y') : '-' }}</div>
                        @if($s->waktu_gas)
                        <div style="font-size: 0.72rem; color: #64748b;">Waktu: {{ $s->waktu_gas }}</div>
                        @endif
                    </td>
                    <td>
                        @php
                            $params = is_array($s->parameters) ? $s->parameters : [];
                            $hasInsitu = false;
                            $countParam = count($params);
                        @endphp
                        <span class="sample-badge-insitu">
                            🧪 {{ $countParam }} Parameter
                        </span>
                        @if($s->temp_gas || $s->temp_ambien_awal)
                        <span style="font-size: 0.72rem; color: #166534; font-weight: 700; display: block; margin-top: 3px;">
                            ✓ Data Cuaca & Gas Terisi
                        </span>
                        @endif
                    </td>
                    <td>
                        @if($s->is_verified || $s->status === 'Verified')
                            <span class="tag tag-green">✓ Verified</span>
                        @elseif($s->status === 'Analisa')
                            <span class="tag tag-orange">Analisa Lab</span>
                        @elseif($s->status === 'Received')
                            <span class="tag tag-blue">Diterima Lab</span>
                        @elseif($s->status === 'Sampling')
                            <span class="tag" style="background: #eff6ff; color: #1d4ed8; font-weight: 700;">Sedang Sampling</span>
                        @else
                            <span class="tag" style="background: #f1f5f9; color: #64748b;">{{ $s->status ?? 'Draft' }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route(request()->routeIs('pengujian.*') ? 'pengujian.sampling.edit' : 'sampling.edit', $s->id) }}" class="btn-input-sampling" title="Input Hasil Sampling">
                            ✏️ Input Data
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                        Tidak ada data sampling lapangan yang sesuai dengan filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $samples->links() }}
    </div>
</div>
@endsection
