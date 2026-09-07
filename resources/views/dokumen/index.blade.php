@extends('layouts.app')

@section('title', 'Dokumen Mutu & Standar Operasional')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📁 Dokumen Mutu & Standard Operating Procedure (SOP)</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Pusat arsip dokumen ISO/IEC 17025:2017, formulir sampling, IK laboratorium, dan sertifikasi KAN.</p>
    </div>
</div>

<div class="data-container">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px;">
            <div style="font-size: 2rem; margin-bottom: 10px;">📜</div>
            <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Sertifikat Akreditasi KAN LP-1813-IDN</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 15px;">Ruang lingkup pengujian kualitas udara emisi cerobong & udara ambien terakreditasi.</p>
            <span class="tag tag-green">✓ Terverifikasi Aktif</span>
        </div>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px;">
            <div style="font-size: 2rem; margin-bottom: 10px;">📑</div>
            <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">SOP Sampling Lapangan (IK-ENV-01)</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 15px;">Instruksi kerja penentuan titik lintas cerobong dan pengukuran gas cerobong langsung.</p>
            <span class="tag tag-blue">Revisi 2026</span>
        </div>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px;">
            <div style="font-size: 2rem; margin-bottom: 10px;">🧪</div>
            <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Manual Mutu ISO/IEC 17025:2017</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 15px;">Pedoman sistem manajemen mutu pengujian dan kalibrasi PT Envirotama Solusindo.</p>
            <span class="tag tag-blue">Dokumen Terkendali</span>
        </div>
    </div>
</div>
@endsection
