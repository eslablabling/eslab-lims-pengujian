@extends('layouts.app')
@section('title','Buat COC Baru')
@section('page-title','+ Buat COC Baru')
@section('page-subtitle','Isi data Chain of Custody pengambilan sampel')

@section('content')
<div class="card" style="max-width:860px;">
    <form method="POST" action="{{ route('coc.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div class="form-group">
                <label class="form-label">No. COC *</label>
                <input name="nomor_coc" class="form-control" value="{{ old('nomor_coc', $nomor) }}" required>
                @error('nomor_coc')<p style="color:#dc2626;font-size:0.78rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Tgl. Sampling *</label>
                <input type="date" name="sampling_date" class="form-control" value="{{ old('sampling_date', date('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Perusahaan *</label>
                <input name="company_name" class="form-control" placeholder="PT Contoh Industri" value="{{ old('company_name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">No. QT / SPK</label>
                <input name="nomor_qt" class="form-control" placeholder="QT/2026/..." value="{{ old('nomor_qt') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Contact Person</label>
                <input name="contact_person" class="form-control" value="{{ old('contact_person') }}">
            </div>
            <div class="form-group">
                <label class="form-label">No. Telepon</label>
                <input name="no_telepon" class="form-control" value="{{ old('no_telepon') }}">
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Alamat Perusahaan</label>
                <textarea name="alamat_perusahaan" class="form-control" rows="2">{{ old('alamat_perusahaan') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Jenis Usaha</label>
                <input name="jenis_usaha" class="form-control" placeholder="Pembangkit Listrik, Pabrik..." value="{{ old('jenis_usaha') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Lokasi Kota</label>
                <input name="lokasi_kota" class="form-control" placeholder="Batam, Surabaya, Jakarta..." value="{{ old('lokasi_kota') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Latitude (GIS)</label>
                <input type="number" step="any" name="latitude" class="form-control" placeholder="-6.2088" value="{{ old('latitude') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Longitude (GIS)</label>
                <input type="number" step="any" name="longitude" class="form-control" placeholder="106.8456" value="{{ old('longitude') }}">
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            </div>
        </div>
        <div style="display:flex;gap:12px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">💾 Simpan COC</button>
            <a href="{{ route('coc.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
