@extends('layouts.app')

@section('title', 'Pengiriman Dokumen & Resi Kurir')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📦 Pengiriman Dokumen & Tracking Resi</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Pengelolaan pengiriman fisik CoA asli, invoice & kwitansi, nomor resi kurir, dan bukti serah terima.</p>
    </div>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="data-container">
    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route('pengujian.pengiriman.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Resi, Invoice, Pelanggan..." style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; width: 280px; font-size: 0.85rem; outline: none;">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Cari
            </button>
        </form>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Nama Pelanggan & Alamat</th>
                    <th>Kurir / Ekspedisi</th>
                    <th>Nomor Resi</th>
                    <th>Tgl. Kirim</th>
                    <th>Status Pengiriman</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td>
                        <strong style="color: #0284c7;">{{ $o->no_invoice }}</strong>
                        <div style="font-size: 0.75rem; color: #64748b;">PO: {{ $o->no_po }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;">{{ $o->nama_pelanggan }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $o->alamat_pelanggan }}</div>
                    </td>
                    <td><strong>{{ $o->kurir_pengiriman ?? '-' }}</strong></td>
                    <td>
                        @if($o->no_resi_pengiriman)
                            <span style="font-family: monospace; font-weight: 700; color: #0284c7; background: #eff6ff; padding: 4px 8px; border-radius: 6px;">{{ $o->no_resi_pengiriman }}</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td>
                        {{ $o->tgl_kirim_dokumen ? \Carbon\Carbon::parse($o->tgl_kirim_dokumen)->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td>
                        @if($o->status_pengiriman === 'Terkirim')
                            <span class="tag tag-green">✓ Terkirim (Diterima)</span>
                        @elseif($o->status_pengiriman === 'Dalam Perjalanan')
                            <span class="tag tag-orange">🚚 Dalam Perjalanan</span>
                        @else
                            <span class="tag" style="background: #f1f5f9; color: #64748b;">Belum Dikirim</span>
                        @endif
                    </td>
                    <td>
                        <button onclick="openModalPengiriman({{ $o->id }}, '{{ $o->no_invoice }}', '{{ $o->kurir_pengiriman }}', '{{ $o->no_resi_pengiriman }}', '{{ $o->tgl_kirim_dokumen }}', '{{ $o->status_pengiriman }}')" style="padding: 6px 12px; background: #0284c7; color: white; border: none; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            ✏️ Update Resi
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Belum ada dokumen yang siap dikirim.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $orders->links() }}
    </div>
</div>

<!-- Modal Update Pengiriman -->
<div id="modalPengiriman" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 480px; padding: 25px;">
        <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Update Pengiriman Dokumen</h3>
        <p id="labelModalPengiriman" style="font-size: 0.8rem; color: #64748b; margin-bottom: 20px;"></p>

        <form id="formPengiriman" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Kurir / Ekspedisi *</label>
                <select name="kurir_pengiriman" id="inputKurir" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                    <option value="JNE Express">JNE Express</option>
                    <option value="J&T Express">J&T Express</option>
                    <option value="SiCepat">SiCepat</option>
                    <option value="Driver / Kurir Internal">Driver / Kurir Internal</option>
                    <option value="Diambil Sendiri (Hand Carry)">Diambil Sendiri (Hand Carry)</option>
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Nomor Resi / AWB</label>
                <input type="text" name="no_resi_pengiriman" id="inputResi" placeholder="Contoh: JNE123456789..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Tanggal Kirim *</label>
                <input type="date" name="tgl_kirim_dokumen" id="inputTglKirim" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Status Pengiriman *</label>
                <select name="status_pengiriman" id="inputStatusKirim" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                    <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                    <option value="Terkirim">Terkirim (Diterima)</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Foto Bukti Kirim / Resi</label>
                <input type="file" name="foto_bukti_kirim" accept=".jpg,.jpeg,.png,.pdf" style="width: 100%; font-size: 0.85rem;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('modalPengiriman').style.display='none'" style="padding: 8px 16px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 8px 18px; background: #0284c7; color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                    Simpan Pengiriman
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModalPengiriman(id, noInv, kurir, resi, tgl, status) {
        document.getElementById('labelModalPengiriman').innerText = 'Invoice: ' + noInv;
        document.getElementById('inputKurir').value = kurir || 'JNE Express';
        document.getElementById('inputResi').value = resi || '';
        document.getElementById('inputTglKirim').value = tgl || new Date().toISOString().split('T')[0];
        document.getElementById('inputStatusKirim').value = status || 'Dalam Perjalanan';
        document.getElementById('formPengiriman').action = '/pengujian/pengiriman/' + id;
        document.getElementById('modalPengiriman').style.display = 'flex';
    }
</script>
@endpush
