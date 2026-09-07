<?php

namespace App\Http\Controllers\Pengujian;

use App\Http\Controllers\Controller;
use App\Models\PengujianOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengujianFinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = PengujianOrder::whereNotNull('no_invoice')->where('no_invoice', '!=', '')->latest();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('no_invoice', 'like', "%{$s}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$s}%")
                  ->orWhere('no_po', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $orders = $query->paginate(15);

        // Stats
        $totalTagihan = PengujianOrder::whereNotNull('no_invoice')->get()->sum('grand_total');
        $totalLunas = PengujianOrder::where('status_pembayaran', 'Lunas')->get()->sum('grand_total');
        $totalPiutang = PengujianOrder::whereNotNull('no_invoice')->where('status_pembayaran', '!=', 'Lunas')->get()->sum('sisa_tagihan');

        return view('pengujian.finance.index', compact('orders', 'totalTagihan', 'totalLunas', 'totalPiutang'));
    }

    public function updatePembayaran(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:Belum Lunas,DP,Lunas',
            'nominal_dibayar' => 'required|numeric|min:0',
            'tgl_bayar' => 'required|date',
            'metode_pembayaran' => 'nullable|string',
            'catatan_pembayaran' => 'nullable|string',
        ]);

        $order = PengujianOrder::findOrFail($id);

        $order->update([
            'status_pembayaran' => $request->status_pembayaran,
            'nominal_dibayar' => $request->nominal_dibayar,
            'tgl_bayar' => $request->tgl_bayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan_pembayaran' => $request->catatan_pembayaran,
        ]);

        return redirect()->route('pengujian.finance.index')->with('success', 'Status pelunasan pembayaran berhasil disimpan.');
    }
}
