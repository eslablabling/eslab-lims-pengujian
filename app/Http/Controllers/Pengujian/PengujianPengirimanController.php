<?php

namespace App\Http\Controllers\Pengujian;

use App\Http\Controllers\Controller;
use App\Models\PengujianOrder;
use Illuminate\Http\Request;

class PengujianPengirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = PengujianOrder::whereNotNull('no_invoice')->where('no_invoice', '!=', '')->latest();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('no_order', 'like', "%{$s}%")
                  ->orWhere('no_invoice', 'like', "%{$s}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$s}%")
                  ->orWhere('no_resi_pengiriman', 'like', "%{$s}%");
            });
        }

        $orders = $query->paginate(15);

        return view('pengujian.pengiriman.index', compact('orders'));
    }

    public function updatePengiriman(Request $request, $id)
    {
        $request->validate([
            'kurir_pengiriman' => 'required|string',
            'no_resi_pengiriman' => 'nullable|string',
            'tgl_kirim_dokumen' => 'required|date',
            'status_pengiriman' => 'required|string',
            'foto_bukti_kirim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $order = PengujianOrder::findOrFail($id);

        $fotoPath = $order->foto_bukti_kirim;
        if ($request->hasFile('foto_bukti_kirim')) {
            $file = $request->file('foto_bukti_kirim');
            $fileName = 'RESI_ENV_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/resi_pengujian'), $fileName);
            $fotoPath = 'uploads/resi_pengujian/' . $fileName;
        }

        $order->update([
            'kurir_pengiriman' => $request->kurir_pengiriman,
            'no_resi_pengiriman' => $request->no_resi_pengiriman,
            'tgl_kirim_dokumen' => $request->tgl_kirim_dokumen,
            'status_pengiriman' => $request->status_pengiriman,
            'foto_bukti_kirim' => $fotoPath,
        ]);

        return redirect()->route('pengujian.pengiriman.index')->with('success', 'Status pengiriman dokumen berhasil diperbarui.');
    }
}
