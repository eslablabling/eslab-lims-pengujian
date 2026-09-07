@extends('layouts.app')

@section('title', 'Master Data Parameter Baku Mutu')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">🗂️ Master Data Parameter & Baku Mutu</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Kelola master parameter uji laboratorium lingkungan, ambang batas baku mutu regulasi, dan metode standar SNI/USEPA.</p>
    </div>
    <button onclick="document.getElementById('modalTambahParam').style.display='flex'" class="btn-primary" style="background: #0284c7; color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
        ➕ Tambah Parameter
    </button>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="data-container">
    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th>Nama Parameter Uji</th>
                    <th>Satuan</th>
                    <th>Baku Mutu Maksimum</th>
                    <th>Regulasi Acuan</th>
                    <th>Metode Standar SNI/USEPA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parameters as $p)
                <tr>
                    <td><strong style="color: #0f172a;">{{ $p->nama_parameter }}</strong></td>
                    <td>{{ $p->satuan ?? '-' }}</td>
                    <td>
                        @if($p->baku_mutu)
                            <span class="tag tag-blue">{{ $p->baku_mutu }} {{ $p->satuan }}</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td><span style="font-size: 0.75rem; color: #64748b;">{{ $p->regulasi ?? '-' }}</span></td>
                    <td><span style="font-family: monospace; font-size: 0.75rem; color: #0284c7;">{{ $p->metode ?? '-' }}</span></td>
                    <td>
                        <form action="{{ route('pengujian.master-data.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus parameter ini?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding: 6px 10px; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Belum ada parameter master data yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $parameters->links() }}
    </div>
</div>

<!-- Modal Tambah Parameter -->
<div id="modalTambahParam" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 500px; padding: 30px;">
        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 20px;">➕ Tambah Parameter Master Data</h3>

        <form action="{{ route('pengujian.master-data.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Nama Parameter *</label>
                <input type="text" name="nama_parameter" required placeholder="Contoh: Sulfur Dioksida (SO2)" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Satuan</label>
                    <input type="text" name="satuan" placeholder="mg/Nm3" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Baku Mutu Maksimum</label>
                    <input type="number" step="0.01" name="baku_mutu" placeholder="150" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Regulasi Pemerintah</label>
                <input type="text" name="regulasi" placeholder="PermenLHK No. 11 Tahun 2021" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Metode Uji Standar</label>
                <input type="text" name="metode" placeholder="SNI 7117.21:2021" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('modalTambahParam').style.display='none'" style="padding: 10px 18px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 20px; background: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Simpan Parameter
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
