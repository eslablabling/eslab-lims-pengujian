<?php

namespace App\Http\Controllers;

use App\Models\CocEmisi;
use App\Models\Sample;
use App\Models\MasterEmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SamplingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filterStatus = $request->input('status', 'aktif');
        $cocFilter = $request->input('coc_id', '');

        $query = Sample::with('coc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sample_id', 'like', "%{$search}%")
                  ->orWhere('nama_cerobong', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('coc', function ($c) use ($search) {
                      $c->where('nomor_coc', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('sampling_officer', 'like', "%{$search}%")
                        ->orWhere('alamat_perusahaan', 'like', "%{$search}%");
                  });
            });
        }

        if ($cocFilter) {
            $query->where('coc_id', $cocFilter);
        }

        if ($filterStatus === 'aktif') {
            $query->whereIn('status', ['Draft', 'Sampling', 'Pending']);
        } elseif ($filterStatus === 'selesai') {
            $query->whereIn('status', ['Received', 'Analisa', 'Verified']);
        }

        $samples = $query->orderBy('coc_id', 'desc')->orderBy('sample_id', 'asc')->paginate(15)->withQueryString();

        $cocs = CocEmisi::latest('id')->limit(100)->get(['id', 'nomor_coc', 'company_name', 'sampling_date']);
        $masterEmisi = MasterEmisi::where('is_active', true)->get();

        // Statistics count
        $countAktif = Sample::whereIn('status', ['Draft', 'Sampling', 'Pending'])->count();
        $countSelesai = Sample::whereIn('status', ['Received', 'Analisa', 'Verified'])->count();
        $countTotal = Sample::count();

        return view('sampling.index', compact(
            'samples', 'cocs', 'masterEmisi', 'search', 'filterStatus', 'cocFilter',
            'countAktif', 'countSelesai', 'countTotal'
        ));
    }

    public function show(Sample $sample)
    {
        $sample->load('coc');
        $masterEmisi = MasterEmisi::where('is_active', true)->get();
        return view('sampling.show', compact('sample', 'masterEmisi'));
    }

    public function edit(Sample $sample)
    {
        $sample->load('coc');
        $masterEmisi = MasterEmisi::where('is_active', true)->get();
        return view('sampling.edit', compact('sample', 'masterEmisi'));
    }

    public function update(Request $request, Sample $sample)
    {
        $validated = $request->validate([
            'nama_cerobong'       => 'nullable|string|max:255',
            'description'         => 'nullable|string',
            'bahan_bakar'         => 'nullable|string',
            'waktu_gas'           => 'nullable|string',
            'no_alat_gas'         => 'nullable|string',
            'temp_gas'            => 'nullable|numeric',
            'tekanan_atm'         => 'nullable|numeric',
            'catatan_cuaca'       => 'nullable|string',
            'koordinat'           => 'nullable|string',

            // Weather & Meteorology
            'temp_ambien_awal'    => 'nullable|numeric',
            'temp_ambien_akhir'   => 'nullable|numeric',
            'kelembaban_awal'     => 'nullable|numeric',
            'kelembaban_akhir'    => 'nullable|numeric',
            'kec_angin_awal'      => 'nullable|numeric',
            'kec_angin_akhir'     => 'nullable|numeric',
            'arah_angin_awal'     => 'nullable|string',
            'arah_angin_akhir'    => 'nullable|string',
            'kondisi_langit_awal' => 'nullable|string',
            'kondisi_langit_akhir'=> 'nullable|string',
            'warna_emisi_awal'    => 'nullable|string',
            'warna_emisi_akhir'   => 'nullable|string',
            'latar_asap_awal'     => 'nullable|string',
            'latar_asap_akhir'    => 'nullable|string',
            'jarak_pengamat_awal' => 'nullable|numeric',
            'jarak_pengamat_akhir'=> 'nullable|numeric',
            'arah_pengamat_awal'  => 'nullable|string',
            'arah_pengamat_akhir' => 'nullable|string',

            // Opacity
            'opasitas_mulai'      => 'nullable|string',
            'opasitas_akhir'      => 'nullable|string',
            'opasitas_matrix'     => 'nullable|array',
            'opasitas_avg'        => 'nullable|numeric',
            'opasitas_ket_1'      => 'nullable|string',
            'opasitas_ket_2'      => 'nullable|string',
            'opasitas_ket_3'      => 'nullable|string',
            'opasitas_ket_4'      => 'nullable|string',
            'opasitas_ket_5'      => 'nullable|string',
            'opasitas_ket_6'      => 'nullable|string',

            // Parameters array
            'parameters'          => 'nullable|array',
            'status'              => 'nullable|string',
        ]);

        if (empty($validated['status']) || $validated['status'] === 'Draft') {
            $validated['status'] = 'Sampling';
        }

        DB::beginTransaction();
        try {
            $sample->update($validated);

            // Update parent COC status to 'Sampling' if still draft
            if ($sample->coc && $sample->coc->status === 'Draft') {
                $sample->coc->update([
                    'status' => 'Sampling',
                    'status_sampling' => 'In Progress'
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Gagal memperbarui data sampling: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data sampling lapangan berhasil disimpan.']);
        }

        $redirectRoute = $request->routeIs('pengujian.*') ? 'pengujian.sampling.index' : 'sampling.index';
        return redirect()->route($redirectRoute)->with('success', "Data sampling untuk ID Sampel {$sample->sample_id} ({$sample->nama_cerobong}) berhasil disimpan!");
    }

    public function destroy(Sample $sample)
    {
        try {
            $sampleId = $sample->sample_id;
            $sample->delete();
            return back()->with('success', "Titik sampel {$sampleId} berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus titik sampel: ' . $e->getMessage()]);
        }
    }
}
