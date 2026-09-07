<?php

namespace App\Http\Controllers;

use App\Models\CocEmisi;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        $locations = CocEmisi::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'nomor_coc', 'company_name', 'lokasi_kota', 'sampling_date', 'status', 'latitude', 'longitude')
            ->latest()
            ->get();

        return view('peta.index', compact('locations'));
    }

    public function getData()
    {
        $locations = CocEmisi::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'nomor_coc', 'company_name', 'lokasi_kota', 'sampling_date', 'status', 'latitude', 'longitude')
            ->latest()
            ->get();
        return response()->json($locations);
    }
}
