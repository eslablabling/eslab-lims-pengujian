<?php

namespace App\Http\Controllers;

use App\Models\MasterEmisi;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        $parameters = MasterEmisi::latest()->paginate(25);
        return view('master-data.index', compact('parameters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_parameter' => 'required|string',
            'satuan'         => 'nullable|string',
            'baku_mutu'      => 'nullable|numeric',
            'regulasi'       => 'nullable|string',
            'metode'         => 'nullable|string',
        ]);
        MasterEmisi::create($data);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Parameter berhasil ditambahkan.']);
        }
        return redirect()->back()->with('success', 'Parameter master data berhasil ditambahkan.');
    }

    public function update(Request $request, MasterEmisi $masterData)
    {
        $masterData->update($request->all());
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Parameter master data berhasil diperbarui.');
    }

    public function destroy(Request $request, MasterEmisi $masterData)
    {
        $masterData->delete();
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Parameter master data berhasil dihapus.');
    }
}
