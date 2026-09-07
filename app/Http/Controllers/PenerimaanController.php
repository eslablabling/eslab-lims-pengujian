<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\CocEmisi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $tab = $request->input('tab', 'belum_diterima');

        $query = Sample::with('coc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sample_id', 'like', "%{$search}%")
                  ->orWhere('nama_cerobong', 'like', "%{$search}%")
                  ->orWhereHas('coc', function ($c) use ($search) {
                      $c->where('nomor_coc', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('sampling_officer', 'like', "%{$search}%");
                  });
            });
        }

        if ($tab === 'belum_diterima') {
            $query->whereNull('tgl_terima_lab');
        } else {
            $query->whereNotNull('tgl_terima_lab');
        }

        $samples = $query->latest('id')->paginate(15)->withQueryString();

        $countBelumDiterima = Sample::whereNull('tgl_terima_lab')->count();
        $countSudahDiterima = Sample::whereNotNull('tgl_terima_lab')->count();

        return view('penerimaan.index', compact(
            'samples', 'search', 'tab', 'countBelumDiterima', 'countSudahDiterima'
        ));
    }

    public function receive(Request $request, Sample $sample)
    {
        $validated = $request->validate([
            'tgl_terima_lab' => 'required|date',
            'kondisi_wadah'  => 'nullable|string',
            'suhu_terima'    => 'nullable|numeric',
            'catatan_terima' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $sample->update([
                'status'         => 'Sample Received',
                'status_lab'     => 'In Analysis',
                'tgl_terima_lab' => Carbon::parse($validated['tgl_terima_lab']),
            ]);

            // Update parent COC status to 'Sample Received' if needed
            $coc = $sample->coc;
            if ($coc && in_array($coc->status, ['Draft', 'Sampling'])) {
                $coc->update(['status' => 'Sample Received']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menerima sampel: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', "Sampel {$sample->sample_id} ({$sample->nama_cerobong}) berhasil diterima di laboratorium dan siap diuji!");
    }

    public function batchReceive(Request $request)
    {
        $validated = $request->validate([
            'sample_ids'     => 'required|array|min:1',
            'tgl_terima_lab' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            Sample::whereIn('id', $validated['sample_ids'])->update([
                'status'         => 'Sample Received',
                'status_lab'     => 'In Analysis',
                'tgl_terima_lab' => Carbon::parse($validated['tgl_terima_lab']),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menerima sampel secara batch: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', count($validated['sample_ids']) . " sampel berhasil dikonfirmasi diterima di laboratorium.");
    }
}
