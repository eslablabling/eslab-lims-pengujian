<?php

namespace App\Http\Controllers;

use App\Models\MasterPeralatan;
use App\Models\SuratJalanPeralatan;
use App\Models\CocEmisi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeralatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'all');

        $query = MasterPeralatan::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_alat', 'like', "%{$search}%")
                  ->orWhere('no_inventaris', 'like', "%{$search}%")
                  ->orWhere('merek_brand', 'like', "%{$search}%")
                  ->orWhere('type_model', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $today = Carbon::today();
        $warningDate = Carbon::today()->addDays(30);

        if ($filter === 'expired') {
            $query->where('jadwal_kalibrasi', '<', $today);
        } elseif ($filter === 'warning') {
            $query->whereBetween('jadwal_kalibrasi', [$today, $warningDate]);
        } elseif ($filter === 'baik') {
            $query->where('kondisi', 'Baik');
        }

        $peralatan = $query->orderBy('no_urut')->paginate(25)->withQueryString();

        // Stats
        $totalAlat = MasterPeralatan::count();
        $baik      = MasterPeralatan::where('kondisi', 'Baik')->count();
        $warning   = MasterPeralatan::whereBetween('jadwal_kalibrasi', [$today, $warningDate])->count();
        $expired   = MasterPeralatan::where('jadwal_kalibrasi', '<', $today)->count();

        $cocs = CocEmisi::latest()->take(30)->get(['id', 'nomor_coc', 'company_name', 'sampling_officer', 'sampling_location']);

        return view('peralatan.index', compact(
            'peralatan', 'cocs', 'totalAlat', 'baik', 'warning', 'expired', 'search', 'filter'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_urut'           => 'nullable|string',
            'yymm'              => 'nullable|string',
            'no_inventaris'     => 'required|unique:master_peralatan,no_inventaris',
            'nama_alat'         => 'required|string',
            'merek_brand'       => 'nullable|string',
            'type_model'        => 'nullable|string',
            'no_seri'           => 'nullable|string',
            'rentang_akurasi'   => 'nullable|string',
            'lokasi'            => 'nullable|string',
            'tgl_kalibrasi'     => 'nullable|date',
            'periode_kalibrasi' => 'nullable|string',
            'jadwal_kalibrasi'  => 'nullable|date',
            'lembaga_kalibrasi' => 'nullable|string',
            'kondisi'           => 'nullable|string',
            'keterangan'        => 'nullable|string',
        ]);

        MasterPeralatan::create($validated);
        return redirect()->back()->with('success', "Peralatan inventaris {$validated['nama_alat']} ({$validated['no_inventaris']}) berhasil ditambahkan!");
    }

    public function update(Request $request, MasterPeralatan $peralatan)
    {
        $validated = $request->validate([
            'no_urut'           => 'nullable|string',
            'yymm'              => 'nullable|string',
            'no_inventaris'     => 'required|unique:master_peralatan,no_inventaris,' . $peralatan->id,
            'nama_alat'         => 'required|string',
            'merek_brand'       => 'nullable|string',
            'type_model'        => 'nullable|string',
            'no_seri'           => 'nullable|string',
            'rentang_akurasi'   => 'nullable|string',
            'lokasi'            => 'nullable|string',
            'tgl_kalibrasi'     => 'nullable|date',
            'periode_kalibrasi' => 'nullable|string',
            'jadwal_kalibrasi'  => 'nullable|date',
            'lembaga_kalibrasi' => 'nullable|string',
            'kondisi'           => 'nullable|string',
            'keterangan'        => 'nullable|string',
        ]);

        $peralatan->update($validated);
        return redirect()->back()->with('success', "Data peralatan {$peralatan->nama_alat} berhasil diperbarui.");
    }

    public function destroy(MasterPeralatan $peralatan)
    {
        $nama = $peralatan->nama_alat;
        $peralatan->delete();
        return redirect()->back()->with('success', "Peralatan {$nama} berhasil dihapus dari inventaris.");
    }
}
