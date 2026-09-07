@extends('layouts.app')

@section('title', 'Penagihan Finance & Pelunasan Pengujian')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">💰 Penagihan Finance & Pelunasan Pengujian</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Monitoring piutang pengujian, verifikasi bukti bayar bank, dan status pembayaran.</p>
    </div>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem;">
    ✓ {{ session('success') }}
</div>
@endif

<!-- Stats Ringkasan Piutang -->
<div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
        <h4>Total Tagihan Terbit</h4>
        <div class="number">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
        <span style="font-size: 0.72rem; color: #64748b;">Akumulasi seluruh invoice</span>
    </div>
    <div class="stat-card">
        <h4>Total Penerimaan (Lunas)</h4>
        <div class="number" style="color: #16a34a;">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
        <span style="font-size: 0.72rem; color: #16a34a;">Dana berhasil diterima</span>
    </div>
    <div class="stat-card">
        <h4>Sisa Piutang (Belum Lunas)</h4>
        <div class="number" style="color: #dc2626;">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
        <span style="font-size: 0.72rem; color: #dc2626;">Outstanding piutang berjalan</span>
    </div>
</div>

<div class="data-container">
    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route('pengujian.finance.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Invoice, Pelanggan, PO..." style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; width: 280px; font-size: 0.85rem; outline: none;">
            <select name="status" style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.85rem; outline: none;">
                <option value="">Semua Status Bayar</option>
                <option value="Belum Lunas" {{ request('status') === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                <option value="DP" {{ request('status') === 'DP' ? 'selected' : '' }}>Uang Muka (DP)</option>
                <option value="Lunas" {{ request('status') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Filter
            </button>
        </form>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Nama Pelanggan</th>
                    <th>Grand Total</th>
                    <th>Nominal Dibayar</th>
                    <th>Sisa Piutang</th>
                    <th>Status</th>
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
                        <div style="font-size: 0.75rem; color: #64748b;">TOP: {{ $o->top_days }} Hari</div>
                    </td>
                    <td><strong>Rp {{ number_format($o->grand_total, 0, ',', '.') }}</strong></td>
                    <td><span style="color: #16a34a; font-weight: 700;">Rp {{ number_format($o->nominal_dibayar, 0, ',', '.') }}</span></td>
                    <td><strong style="color: #dc2626;">Rp {{ number_format($o->sisa_tagihan, 0, ',', '.') }}</strong></td>
                    <td>
                        @if($o->status_pembayaran === 'Lunas')
                            <span class="tag tag-green">✓ Lunas</span>
                        @elseif($o->status_pembayaran === 'DP')
                            <span class="tag tag-orange">⏳ DP</span>
                        @else
                            <span class="tag" style="background: #fee2e2; color: #dc2626;">Belum Lunas</span>
                        @endif
                    </td>
                    <td>
                        <button onclick="openModalBayar({{ $o->id }}, '{{ $o->no_invoice }}', '{{ $o->grand_total }}', '{{ $o->nominal_dibayar }}', '{{ $o->status_pembayaran }}')" style="padding: 6px 12px; background: #16a34a; color: white; border: none; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            💵 Input Bayar
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Belum ada data penagihan finance.
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

<!-- Modal Input Pembayaran -->
<div id="modalBayar" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 480px; padding: 25px;">
        <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Update Pembayaran & Pelunasan</h3>
        <p id="labelModalBayar" style="font-size: 0.8rem; color: #64748b; margin-bottom: 20px;"></p>

        <form id="formBayar" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Status Pembayaran *</label>
                <select name="status_pembayaran" id="inputStatusBayar" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;" onchange="onStatusBayarChange()">
                    <option value="Belum Lunas">Belum Lunas</option>
                    <option value="DP">Uang Muka (DP)</option>
                    <option value="Lunas">Lunas</option>
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Nominal Pembayaran Diterima (Rp) *</label>
                <input type="number" name="nominal_dibayar" id="inputNominalBayar" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Tanggal Bayar *</label>
                <input type="date" name="tgl_bayar" id="inputTglBayar" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Metode Pembayaran</label>
                <select name="metode_pembayaran" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                    <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                    <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                    <option value="Giro / Cek">Giro / Cek</option>
                    <option value="Tunai / Cash">Tunai / Cash</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Catatan Finance</label>
                <textarea name="catatan_pembayaran" rows="2" placeholder="Catatan bukti transfer..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('modalBayar').style.display='none'" style="padding: 8px 16px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 8px 18px; background: #16a34a; color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                    Simpan Pelunasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentGrandTotal = 0;
    function openModalBayar(id, noInv, grandTotal, nominal, status) {
        currentGrandTotal = parseFloat(grandTotal) || 0;
        document.getElementById('labelModalBayar').innerText = 'Invoice: ' + noInv + ' (Total: Rp ' + currentGrandTotal.toLocaleString('id-ID') + ')';
        document.getElementById('inputStatusBayar').value = status || 'Belum Lunas';
        document.getElementById('inputNominalBayar').value = nominal || 0;
        document.getElementById('inputTglBayar').value = new Date().toISOString().split('T')[0];
        document.getElementById('formBayar').action = '/pengujian/finance/' + id;
        document.getElementById('modalBayar').style.display = 'flex';
    }

    function onStatusBayarChange() {
        const st = document.getElementById('inputStatusBayar').value;
        if (st === 'Lunas') {
            document.getElementById('inputNominalBayar').value = currentGrandTotal;
        } else if (st === 'Belum Lunas') {
            document.getElementById('inputNominalBayar').value = 0;
        }
    }
</script>
@endpush
