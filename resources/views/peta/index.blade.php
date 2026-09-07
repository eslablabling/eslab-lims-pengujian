@extends('layouts.app')

@section('title', 'Peta GIS Sebaran Titik Sampling')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
@endpush

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">🗺️ Peta GIS Sebaran Titik Sampling Lingkungan</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Visualisasi geografis sebaran lokasi pengambilan sampel pelanggan di seluruh wilayah Indonesia.</p>
    </div>
</div>

<div class="data-container">
    <div id="gisMap" style="height: 550px; width: 100%; border-radius: 16px; border: 1px solid #e2e8f0;"></div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const locations = {!! json_encode($locations) !!};
        const map = L.map('gisMap').setView([-6.2088, 106.8456], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap PT Envirotama Solusindo'
        }).addTo(map);

        if (locations && locations.length > 0) {
            const markers = [];
            locations.forEach(loc => {
                if (loc.latitude && loc.longitude) {
                    const marker = L.marker([loc.latitude, loc.longitude]).addTo(map);
                    marker.bindPopup(`
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px;">
                            <strong style="color: #0284c7; font-size: 14px;">${loc.company_name}</strong><br>
                            <b>No. COC:</b> ${loc.nomor_coc}<br>
                            <b>Lokasi:</b> ${loc.lokasi_kota || '-'}<br>
                            <b>Status:</b> ${loc.status || 'Draft'}
                        </div>
                    `);
                    markers.push([loc.latitude, loc.longitude]);
                }
            });

            if (markers.length > 0) {
                map.fitBounds(markers, { padding: [50, 50] });
            }
        }
    });
</script>
@endpush
