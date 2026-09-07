<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\CocEmisi;
use App\Models\MasterPeralatan;
use App\Models\PengujianOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->is('kalibrasi*') || str_contains($request->getRequestUri(), 'kalibrasi')) {
            return app(\App\Http\Controllers\Kalibrasi\KalibrasiDashboardController::class)->index();
        }

        // 1. Sampel Stats & Raw List with COC
        $samplesRaw = Sample::with('coc')
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeSamples = [];
        foreach ($samplesRaw as $s) {
            $coc = $s->coc;
            $rawTerima = $s->tgl_terima_lab;
            $rawSelesai = $s->verified_at ?? ($s->is_verified ? $s->updated_at : null);

            $tatPriority = $coc ? strtoupper($coc->tat_requested ?? 'NORMAL') : 'NORMAL';
            $isUrgent = $tatPriority === 'URGENT';

            // Hitung Turnaround Time (TAT)
            $tatHari = '-';
            if ($rawTerima && $rawSelesai) {
                $d1 = Carbon::parse($rawTerima);
                $d2 = Carbon::parse($rawSelesai);
                $diffInDays = $d1->diffInDays($d2);
                $tatHari = $diffInDays <= 0 ? '1 Hari' : "{$diffInDays} Hari";
            }

            // Hitung status kepatuhan & keterlambatan TAT (hari kerja)
            $actualDuration = null;
            $isDelayed = false;
            $delayDays = 0;
            $targetDays = $isUrgent ? 5 : ($coc?->tat_days ?? 14);

            if ($rawTerima) {
                $start = Carbon::parse($rawTerima);
                $end = $rawSelesai ? Carbon::parse($rawSelesai) : Carbon::now();
                $actualDuration = $start->diffInDays($end);
                $isDelayed = $actualDuration > $targetDays;
                $delayDays = $isDelayed ? ($actualDuration - $targetDays) : 0;
            }

            // Status label
            if ($s->is_verified || $s->status === 'Verified' || $s->status_lab === 'verified') {
                $statusLabel = 'FINISH';
                $statusClass = 'tag-green';
            } elseif ($s->status === 'Analisa' || $s->status_lab === 'analyzed' || $s->tgl_terima_lab) {
                $statusLabel = 'ANALISA';
                $statusClass = 'tag-blue';
            } else {
                $statusLabel = 'SAMPLING';
                $statusClass = 'tag-orange';
            }

            $activeSamples[] = [
                'db_id'          => $coc?->id,
                'nomor_coc'      => $coc?->nomor_coc ?? '-',
                'sample_id'      => $s->sample_id ?? ('SMP-' . $s->id),
                'company_name'   => $coc?->company_name ?? ($s->description ?? '-'),
                'sampling_date'  => $coc?->sampling_date ? Carbon::parse($coc->sampling_date)->format('Y-m-d') : null,
                'tgl_terima_lab' => $rawTerima ? Carbon::parse($rawTerima)->format('Y-m-d') : null,
                'tgl_selesai'    => $rawSelesai ? Carbon::parse($rawSelesai)->format('Y-m-d') : null,
                'tglSampling'    => $coc?->sampling_date ? Carbon::parse($coc->sampling_date)->translatedFormat('d/m/Y') : '-',
                'tglTerima'      => $rawTerima ? Carbon::parse($rawTerima)->translatedFormat('d/m/Y') : '-',
                'tglSelesai'     => $rawSelesai ? Carbon::parse($rawSelesai)->translatedFormat('d/m/Y') : '-',
                'tatHari'        => $tatHari,
                'isUrgent'       => $isUrgent,
                'statusLabel'    => $statusLabel,
                'statusClass'    => $statusClass,
                'actualDuration' => $actualDuration,
                'isDelayed'      => $isDelayed,
                'delayDays'      => $delayDays,
                'targetDays'     => $targetDays,
            ];
        }

        // 2. Equipment Stats
        $totalAlat = MasterPeralatan::count();
        $alatBaik = MasterPeralatan::where('kondisi', 'Baik')->count();
        $warningKalibrasi = MasterPeralatan::whereNotNull('jadwal_kalibrasi')
            ->whereDate('jadwal_kalibrasi', '<=', now()->addDays(30))
            ->whereDate('jadwal_kalibrasi', '>=', now())->count();
        $expiredKalibrasi = MasterPeralatan::whereNotNull('jadwal_kalibrasi')
            ->whereDate('jadwal_kalibrasi', '<', now())->count();

        return view('dashboard.index', compact(
            'activeSamples', 'totalAlat', 'alatBaik', 'warningKalibrasi', 'expiredKalibrasi'
        ));
    }
}
