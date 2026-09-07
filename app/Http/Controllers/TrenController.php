<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\CocEmisi;
use App\Models\MasterEmisi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrenController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $trendMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthName = Carbon::createFromDate($year, $m, 1)->translatedFormat('F');
            $total = CocEmisi::whereYear('sampling_date', $year)->whereMonth('sampling_date', $m)->count();
            $verified = CocEmisi::whereYear('sampling_date', $year)->whereMonth('sampling_date', $m)->where('status', 'Verified')->count();
            $trendMonthly[] = [
                'bulan' => $monthName,
                'total' => $total,
                'verified' => $verified
            ];
        }

        // TAT analysis
        $tatData = CocEmisi::whereNotNull('tgl_selesai')->whereNotNull('sampling_date')
            ->selectRaw('AVG(DATEDIFF(tgl_selesai, sampling_date)) as avg_tat, COUNT(*) as total,
                          SUM(CASE WHEN DATEDIFF(tgl_selesai, sampling_date) > 14 THEN 1 ELSE 0 END) as terlambat')
            ->first();

        $years = CocEmisi::selectRaw('YEAR(sampling_date) as y')->distinct()->whereNotNull('sampling_date')->orderByDesc('y')->pluck('y');
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('tren.index', compact('trendMonthly', 'tatData', 'years', 'year'));
    }

    public function getData(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $year   = $request->input('year', date('Y'));

        $query = CocEmisi::query();

        if ($period === 'monthly') {
            $data = $query->whereYear('sampling_date', $year)
                ->selectRaw('MONTH(sampling_date) as bulan, COUNT(*) as total, COUNT(CASE WHEN status = "Verified" THEN 1 END) as verified')
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();
        } elseif ($period === 'yearly') {
            $data = $query->selectRaw('YEAR(sampling_date) as tahun, COUNT(*) as total, COUNT(CASE WHEN status = "Verified" THEN 1 END) as verified')
                ->groupBy('tahun')
                ->orderBy('tahun')
                ->get();
        } else {
            $data = $query->whereYear('sampling_date', $year)
                ->selectRaw('DATE(sampling_date) as tgl, COUNT(*) as total')
                ->groupBy('tgl')
                ->orderBy('tgl')
                ->get();
        }

        $tatData = CocEmisi::whereNotNull('tgl_selesai')->whereNotNull('sampling_date')
            ->selectRaw('AVG(DATEDIFF(tgl_selesai, sampling_date)) as avg_tat, COUNT(*) as total,
                          SUM(CASE WHEN DATEDIFF(tgl_selesai, sampling_date) > 14 THEN 1 ELSE 0 END) as terlambat')
            ->first();

        $years = CocEmisi::selectRaw('YEAR(sampling_date) as y')->distinct()->whereNotNull('sampling_date')->orderByDesc('y')->pluck('y');

        return response()->json([
            'chart_data' => $data,
            'tat'        => $tatData,
            'years'      => $years,
        ]);
    }
}
