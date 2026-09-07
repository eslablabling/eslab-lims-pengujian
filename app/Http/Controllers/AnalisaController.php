<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\CocEmisi;
use App\Models\MasterEmisi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalisaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $tab = $request->input('tab', 'antrean');

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

        if ($tab === 'antrean') {
            $query->where('is_verified', false);
        } else {
            $query->where('is_verified', true);
        }

        $samples = $query->latest('id')->paginate(15)->withQueryString();
        $masterEmisi = MasterEmisi::where('is_active', true)->get();

        $countAntrean = Sample::where('is_verified', false)->count();
        $countSelesai = Sample::where('is_verified', true)->count();

        return view('analisa.index', compact(
            'samples', 'masterEmisi', 'search', 'tab', 'countAntrean', 'countSelesai'
        ));
    }

    public function update(Request $request, Sample $sample)
    {
        $validated = $request->validate([
            'parameters'      => 'nullable|array',
            'qc_blank_weight' => 'nullable|numeric',
            'qc_dup_weight_1' => 'nullable|numeric',
            'qc_dup_weight_2' => 'nullable|numeric',
            'qc_rpd'          => 'nullable|numeric',
            'qc_status'       => 'nullable|string',
            'status_lab'      => 'nullable|string',
        ]);

        $validated['status_lab'] = $validated['status_lab'] ?? 'In Analysis';
        if (!$sample->analyzed_at) {
            $validated['analyzed_at'] = Carbon::now();
        }

        DB::beginTransaction();
        try {
            $sample->update($validated);

            // Update parent COC status
            $coc = $sample->coc;
            if ($coc && in_array($coc->status, ['Sample Received', 'Draft', 'Sampling'])) {
                $coc->update(['status' => 'In Analysis']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menyimpan data analisa: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', "Hasil analisa untuk sampel {$sample->sample_id} ({$sample->nama_cerobong}) berhasil disimpan!");
    }

    public function submitForVerification(Request $request, Sample $sample)
    {
        DB::beginTransaction();
        try {
            $sample->update([
                'status_lab'   => 'Pending Verification',
                'analyzed_at'  => $sample->analyzed_at ?? Carbon::now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal mengajukan verifikasi: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', "Sampel {$sample->sample_id} berhasil diajukan ke Manajer Lab untuk verifikasi CoA!");
    }
}
