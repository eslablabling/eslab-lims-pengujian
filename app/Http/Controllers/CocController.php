<?php

namespace App\Http\Controllers;

use App\Models\CocEmisi;
use App\Models\Sample;
use App\Models\ClientAccount;
use App\Models\MasterEmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CocController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $dateStart = $request->input('date_start', '');
        $dateEnd = $request->input('date_end', '');

        $query = CocEmisi::with('samples');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_coc', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('nomor_qt', 'like', "%{$search}%")
                  ->orWhere('sampling_officer', 'like', "%{$search}%");
            });
        }

        if ($dateStart) {
            $query->whereDate('sampling_date', '>=', $dateStart);
        }
        if ($dateEnd) {
            $query->whereDate('sampling_date', '<=', $dateEnd);
        }

        $cocs = $query->latest()->paginate(15)->withQueryString();

        // Autocomplete histories from MySQL
        $companies = CocEmisi::select('company_name', 'alamat_perusahaan', 'contact_person', 'no_telepon', 'email_coa')
            ->whereNotNull('company_name')
            ->groupBy('company_name', 'alamat_perusahaan', 'contact_person', 'no_telepon', 'email_coa')
            ->latest('id')
            ->limit(50)
            ->get();

        $officers = CocEmisi::select('sampling_officer')
            ->whereNotNull('sampling_officer')
            ->distinct()
            ->pluck('sampling_officer')
            ->filter()
            ->flatMap(function ($names) {
                return array_map('trim', explode(',', $names));
            })
            ->unique()
            ->values();

        // Unique regulations and parameters from MySQL
        $regulations = MasterEmisi::where('is_active', true)
            ->whereNotNull('regulasi')
            ->where('regulasi', '!=', '-')
            ->distinct()
            ->pluck('regulasi');

        $masterParameters = MasterEmisi::where('is_active', true)->get();

        // Map parameter to available methods
        $paramMethodsMap = [];
        foreach ($masterParameters as $mp) {
            $pName = $mp->nama_parameter ?? $mp->parameter;
            if (!$pName) continue;
            if (!isset($paramMethodsMap[$pName])) {
                $paramMethodsMap[$pName] = [];
            }
            if ($mp->metode && !in_array($mp->metode, $paramMethodsMap[$pName])) {
                $paramMethodsMap[$pName][] = $mp->metode;
            }
        }

        $quotations = \App\Models\PengujianOrder::with('items')->latest()->get();
        $newCocNumber = CocEmisi::generateNomorCoc();

        return view('coc.index', compact(
            'cocs', 'search', 'dateStart', 'dateEnd',
            'companies', 'officers', 'regulations', 'masterParameters', 'paramMethodsMap', 'newCocNumber', 'quotations'
        ));
    }

    public function create()
    {
        return redirect()->route('pengujian.coc.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_coc'           => 'required|string',
            'nomor_qt'            => 'nullable|string',
            'company_name'        => 'required|string',
            'alamat_perusahaan'   => 'nullable|string',
            'contact_person'      => 'nullable|string',
            'no_telepon'          => 'nullable|string',
            'email_coa'           => 'nullable|string',
            'sampling_date'       => 'required|date',
            'tgl_selesai'         => 'nullable|date',
            'tat_days'            => 'nullable|integer',
            'sampling_officer'    => 'nullable|string',
            'sampling_location'   => 'nullable|string',
            'jenis_usaha'         => 'nullable|string',
            'samples'             => 'required|array|min:1',
            'samples.*.sample_id' => 'required|string',
            'samples.*.nama_cerobong' => 'required|string',
            'samples.*.regulasi'  => 'nullable|string',
            'samples.*.parameters'=> 'nullable|array',
            'samples.*.methods'   => 'nullable|array',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'Draft';
        $validated['status_sampling'] = 'Pending';
        $validated['company_address'] = $validated['alamat_perusahaan'] ?? null;
        $validated['phone_no'] = $validated['no_telepon'] ?? null;
        $validated['qt_no'] = $validated['nomor_qt'] ?? null;

        DB::beginTransaction();
        try {
            // Check if nomor_coc already exists, if so generate new one
            if (CocEmisi::where('nomor_coc', $validated['nomor_coc'])->exists()) {
                $validated['nomor_coc'] = CocEmisi::generateNomorCoc();
            }

            $coc = CocEmisi::create($validated);

            // Store child samples into MySQL
            foreach ($request->input('samples', []) as $sampleData) {
                $paramsWithMethods = [];
                $rawParams = $sampleData['parameters'] ?? [];
                $methodsMap = $sampleData['methods'] ?? [];

                foreach ($rawParams as $pKey => $pVal) {
                    if (is_array($pVal)) {
                        $pName = $pVal['parameter'] ?? $pKey;
                        $pMet  = $pVal['metode'] ?? ($methodsMap[$pName] ?? null);
                        $pSat  = $pVal['satuan'] ?? 'mg/Nm3';
                    } else {
                        $pName = $pVal;
                        $pMet  = $methodsMap[$pName] ?? null;
                        $pSat  = 'mg/Nm3';
                    }
                    $paramsWithMethods[] = [
                        'parameter' => $pName,
                        'metode'    => $pMet,
                        'satuan'    => $pSat,
                    ];
                }

                Sample::create([
                    'coc_id'        => $coc->id,
                    'sample_id'     => $sampleData['sample_id'],
                    'nama_cerobong' => $sampleData['nama_cerobong'],
                    'description'   => $sampleData['nama_cerobong'],
                    'regulations'   => isset($sampleData['regulasi']) ? [$sampleData['regulasi']] : [],
                    'parameters'    => $paramsWithMethods,
                    'status'        => 'Draft',
                    'status_lab'    => 'Pending',
                    'is_verified'   => false,
                ]);
            }

            // Auto-create client account if not exists
            if (!ClientAccount::where('company_name', $validated['company_name'])->exists()) {
                ClientAccount::create([
                    'company_name' => $validated['company_name'],
                    'username'     => ClientAccount::generateUsername($validated['company_name']),
                    'password'     => ClientAccount::generatePassword(),
                    'email'        => $validated['email_coa'] ?? null,
                    'phone'        => $validated['no_telepon'] ?? null,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menyimpan COC: ' . $e->getMessage()])->withInput();
        }

        $redirectRoute = $request->routeIs('pengujian.*') ? 'pengujian.coc.show' : 'coc.show';
        return redirect()->route($redirectRoute, $coc->id)->with('success', 'Chain of Custody (COC) berhasil diterbitkan!');
    }

    public function show(CocEmisi $coc)
    {
        $coc->load('samples');
        return view('coc.show', compact('coc'));
    }

    public function edit(CocEmisi $coc)
    {
        $coc->load('samples');

        $companies = CocEmisi::select('company_name', 'alamat_perusahaan', 'contact_person', 'no_telepon', 'email_coa')
            ->whereNotNull('company_name')
            ->groupBy('company_name', 'alamat_perusahaan', 'contact_person', 'no_telepon', 'email_coa')
            ->latest('id')
            ->limit(50)
            ->get();

        $officers = CocEmisi::select('sampling_officer')
            ->whereNotNull('sampling_officer')
            ->distinct()
            ->pluck('sampling_officer')
            ->filter()
            ->flatMap(function ($names) {
                return array_map('trim', explode(',', $names));
            })
            ->unique()
            ->values();

        $regulations = MasterEmisi::where('is_active', true)
            ->whereNotNull('regulasi')
            ->where('regulasi', '!=', '-')
            ->distinct()
            ->pluck('regulasi');

        $masterParameters = MasterEmisi::where('is_active', true)->get();

        $paramMethodsMap = [];
        foreach ($masterParameters as $mp) {
            $pName = $mp->nama_parameter ?? $mp->parameter;
            if (!$pName) continue;
            if (!isset($paramMethodsMap[$pName])) {
                $paramMethodsMap[$pName] = [];
            }
            if ($mp->metode && !in_array($mp->metode, $paramMethodsMap[$pName])) {
                $paramMethodsMap[$pName][] = $mp->metode;
            }
        }

        return view('coc.edit', compact(
            'coc', 'companies', 'officers', 'regulations', 'masterParameters', 'paramMethodsMap'
        ));
    }

    public function update(Request $request, CocEmisi $coc)
    {
        $validated = $request->validate([
            'nomor_coc'           => 'required|string',
            'nomor_qt'            => 'nullable|string',
            'company_name'        => 'required|string',
            'alamat_perusahaan'   => 'nullable|string',
            'contact_person'      => 'nullable|string',
            'no_telepon'          => 'nullable|string',
            'email_coa'           => 'nullable|string',
            'sampling_date'       => 'required|date',
            'tgl_selesai'         => 'nullable|date',
            'tat_days'            => 'nullable|integer',
            'sampling_officer'    => 'nullable|string',
            'sampling_location'   => 'nullable|string',
            'status'              => 'nullable|string',
            'jenis_usaha'         => 'nullable|string',
            'samples'             => 'required|array|min:1',
            'samples.*.sample_id' => 'required|string',
            'samples.*.nama_cerobong' => 'required|string',
            'samples.*.regulasi'  => 'nullable|string',
            'samples.*.parameters'=> 'nullable|array',
            'samples.*.methods'   => 'nullable|array',
        ]);

        $validated['company_address'] = $validated['alamat_perusahaan'] ?? null;
        $validated['phone_no'] = $validated['no_telepon'] ?? null;
        $validated['qt_no'] = $validated['nomor_qt'] ?? null;

        DB::beginTransaction();
        try {
            $coc->update($validated);

            $existingSampleIds = [];
            foreach ($request->input('samples', []) as $sampleData) {
                $paramsWithMethods = [];
                $rawParams = $sampleData['parameters'] ?? [];
                $methodsMap = $sampleData['methods'] ?? [];

                foreach ($rawParams as $pKey => $pVal) {
                    if (is_array($pVal)) {
                        $pName = $pVal['parameter'] ?? $pKey;
                        $pMet  = $pVal['metode'] ?? ($methodsMap[$pName] ?? null);
                        $pSat  = $pVal['satuan'] ?? 'mg/Nm3';
                    } else {
                        $pName = $pVal;
                        $pMet  = $methodsMap[$pName] ?? null;
                        $pSat  = 'mg/Nm3';
                    }
                    $paramsWithMethods[] = [
                        'parameter' => $pName,
                        'metode'    => $pMet,
                        'satuan'    => $pSat,
                    ];
                }

                if (isset($sampleData['id']) && $sampleData['id']) {
                    $sample = Sample::find($sampleData['id']);
                    if ($sample && $sample->coc_id === $coc->id) {
                        $sample->update([
                            'sample_id'     => $sampleData['sample_id'],
                            'nama_cerobong' => $sampleData['nama_cerobong'],
                            'description'   => $sampleData['nama_cerobong'],
                            'regulations'   => isset($sampleData['regulasi']) ? [$sampleData['regulasi']] : [],
                            'parameters'    => $paramsWithMethods,
                        ]);
                        $existingSampleIds[] = $sample->id;
                    }
                } else {
                    $newSample = Sample::create([
                        'coc_id'        => $coc->id,
                        'sample_id'     => $sampleData['sample_id'],
                        'nama_cerobong' => $sampleData['nama_cerobong'],
                        'description'   => $sampleData['nama_cerobong'],
                        'regulations'   => isset($sampleData['regulasi']) ? [$sampleData['regulasi']] : [],
                        'parameters'    => $paramsWithMethods,
                        'status'        => 'Draft',
                        'status_lab'    => 'Pending',
                        'is_verified'   => false,
                    ]);
                    $existingSampleIds[] = $newSample->id;
                }
            }

            // Remove samples that were deleted from form
            Sample::where('coc_id', $coc->id)->whereNotIn('id', $existingSampleIds)->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal memperbarui COC: ' . $e->getMessage()])->withInput();
        }

        $redirectRoute = $request->routeIs('pengujian.*') ? 'pengujian.coc.show' : 'coc.show';
        return redirect()->route($redirectRoute, $coc->id)->with('success', 'Data Chain of Custody (COC) berhasil diperbarui!');
    }

    public function destroy(CocEmisi $coc)
    {
        DB::beginTransaction();
        try {
            $coc->samples()->delete();
            $coc->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menghapus COC: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Dokumen COC dan seluruh sampel terkait berhasil dihapus.');
    }

    public function duplicate(CocEmisi $coc)
    {
        DB::beginTransaction();
        try {
            $newCoc = $coc->replicate([
                'nomor_coc', 'status', 'status_sampling', 'tgl_selesai', 'created_at', 'updated_at'
            ]);
            $newCoc->nomor_coc = CocEmisi::generateNomorCoc();
            $newCoc->status = 'Draft';
            $newCoc->status_sampling = 'Pending';
            $newCoc->sampling_date = date('Y-m-d');
            $newCoc->save();

            $coc->load('samples');
            $seq = substr($newCoc->nomor_coc, -4);

            foreach ($coc->samples as $idx => $s) {
                $newSample = $s->replicate([
                    'coc_id', 'sample_id', 'status', 'status_lab', 'is_verified', 'verified_at', 'created_at', 'updated_at'
                ]);
                $newSample->coc_id = $newCoc->id;
                $newSample->sample_id = "{$seq}." . ($idx + 1);
                $newSample->status = 'Draft';
                $newSample->status_lab = 'Pending';
                $newSample->is_verified = false;
                $newSample->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menduplikasi COC: ' . $e->getMessage()]);
        }

        $redirectRoute = request()->routeIs('pengujian.*') ? 'pengujian.coc.edit' : 'coc.edit';
        return redirect()->route($redirectRoute, $newCoc->id)->with('success', "Dokumen COC berhasil diduplikasi menjadi {$newCoc->nomor_coc}. Silakan sesuaikan!");
    }
}
