@extends('layouts.app')

@section('title', 'Tren & Grafik Mutu Analisa')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📈 Tren Analisa & Mutu Laboratorium</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Visualisasi statistik tren sampel masuk, kepatuhan turnaround time (TAT), dan efisiensi analisa lab.</p>
    </div>
    <form method="GET" action="{{ route('pengujian.tren.index') }}" style="display: flex; gap: 10px; align-items: center;">
        <select name="year" style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-weight: 700; font-size: 0.85rem; outline: none;" onchange="this.form.submit()">
            @foreach($years as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
        <h4>Total Sampel Tahun Ini</h4>
        <div class="number">{{ array_sum(array_column($trendMonthly, 'total')) }}</div>
        <span style="font-size: 0.72rem; color: #64748b;">Sampel terdaftar di {{ $year }}</span>
    </div>
    <div class="stat-card">
        <h4>Selesai Terverifikasi</h4>
        <div class="number" style="color: #16a34a;">{{ array_sum(array_column($trendMonthly, 'verified')) }}</div>
        <span style="font-size: 0.72rem; color: #16a34a;">Sertifikat CoA terbit</span>
    </div>
    <div class="stat-card">
        <h4>Rata-Rata Turnaround Time</h4>
        <div class="number" style="color: #0284c7;">{{ round($tatData->avg_tat ?? 0, 1) }} Hari</div>
        <span style="font-size: 0.72rem; color: #0284c7;">Durasi sampling ke CoA</span>
    </div>
</div>

<div class="data-container">
    <div class="table-header" style="margin-bottom: 20px;">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">📊 Grafik Tren Sampel Bulanan (Tahun {{ $year }})</h3>
    </div>
    <div style="position: relative; height: 350px; width: 100%;">
        <canvas id="canvasTrenBulanan"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('canvasTrenBulanan');
        if (ctx) {
            const labels = {!! json_encode(array_column($trendMonthly, 'bulan')) !!};
            const dataTotal = {!! json_encode(array_column($trendMonthly, 'total')) !!};
            const dataVerified = {!! json_encode(array_column($trendMonthly, 'verified')) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Sampel Terdaftar',
                            data: dataTotal,
                            backgroundColor: '#0284c7',
                            borderRadius: 6
                        },
                        {
                            label: 'CoA Selesai (Verified)',
                            data: dataVerified,
                            backgroundColor: '#16a34a',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { precision: 0 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { font: { weight: 'bold' } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
