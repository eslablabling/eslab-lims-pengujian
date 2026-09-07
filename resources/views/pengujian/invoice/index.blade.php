@extends('layouts.app')

@section('title', 'Invoicing & Kwitansi Pengujian')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">💳 Invoicing, Kwitansi, BAST & TST Pengujian</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Penerbitan tagihan resmi, kwitansi pembayaran, berita acara serah terima, dan cetak dokumen keuangan.</p>
    </div>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="data-container">
    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route('pengujian.invoice.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Invoice, PO, Perusahaan..." style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; width: 280px; font-size: 0.85rem; outline: none;">
            <select name="status" style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.85rem; outline: none;">
                <option value="">Semua Status Invoice</option>
                <option value="belum_invoice" {{ request('status') === 'belum_invoice' ? 'selected' : '' }}>Belum Terbit Invoice</option>
                <option value="terbit_invoice" {{ request('status') === 'terbit_invoice' ? 'selected' : '' }}>Sudah Terbit Invoice</option>
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
                    <th>No. PO / Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Grand Total</th>
                    <th>Status Pembayaran</th>
                    <th>Cetak Dokumen Lengkap</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td>
                        @if($o->no_invoice)
                            <strong style="color: #0284c7;">{{ $o->no_invoice }}</strong>
                        @else
                            <span class="tag" style="background: #fff7ed; color: #c2410c;">Belum Diterbitkan</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $o->no_po }}</strong>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $o->no_order }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;">{{ $o->nama_pelanggan }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">TOP: {{ $o->top_days }} Hari</div>
                    </td>
                    <td>
                        <strong style="color: #0f172a;">Rp {{ number_format($o->grand_total, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        @if($o->status_pembayaran === 'Lunas')
                            <span class="tag tag-green">✓ Lunas</span>
                        @elseif($o->status_pembayaran === 'DP')
                            <span class="tag tag-orange">⏳ DP (Rp {{ number_format($o->nominal_dibayar, 0, ',', '.') }})</span>
                        @else
                            <span class="tag" style="background: #fee2e2; color: #dc2626;">Belum Lunas</span>
                        @endif
                    </td>
                    <td>
                        @if($o->no_invoice)
                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                            <a href="{{ route('pengujian.invoice.print.invoice', $o->id) }}" target="_blank" style="padding: 4px 8px; background: #0284c7; color: white; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-decoration: none;">INV</a>
                            <a href="{{ route('pengujian.invoice.print.kwitansi', $o->id) }}" target="_blank" style="padding: 4px 8px; background: #16a34a; color: white; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-decoration: none;">KWT</a>
                            <a href="{{ route('pengujian.invoice.print.bast', $o->id) }}" target="_blank" style="padding: 4px 8px; background: #d97706; color: white; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-decoration: none;">BAST</a>
                            <a href="{{ route('pengujian.invoice.print.tst', $o->id) }}" target="_blank" style="padding: 4px 8px; background: #9333ea; color: white; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-decoration: none;">TST</a>
                            <a href="{{ route('pengujian.invoice.print.all', $o->id) }}" target="_blank" style="padding: 4px 8px; background: #0f172a; color: white; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-decoration: none;">🖨️ Cetak Semua</a>
                        </div>
                        @else
                        <span style="font-size: 0.75rem; color: #94a3b8;">Menunggu terbit invoice</span>
                        @endif
                    </td>
                    <td>
                        @if(empty($o->no_invoice))
                        <form action="{{ route('pengujian.invoice.terbit', $o->id) }}" method="POST">
                            @csrf
                            <button type="submit" style="padding: 6px 14px; background: #0284c7; color: white; border: none; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                ⚡ Terbitkan Invoice
                            </button>
                        </form>
                        @else
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="color: #16a34a; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px;">
                                ✓ Terbit
                            </span>
                            <form action="{{ route('pengujian.invoice.batal', $o->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan invoice {{ $o->no_invoice }}? Status penagihan akan dikembalikan ke draft.')">
                                @csrf
                                <button type="submit" style="padding: 4px 10px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; font-size: 0.72rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="Batalkan Penerbitan Invoice">
                                    ❌ Batal
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Belum ada order untuk penagihan invoice.
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
@endsection
