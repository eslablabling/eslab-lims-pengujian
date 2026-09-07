<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\CocEmisi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $query  = Sample::with('coc')->whereIn('status_lab', ['Pending Verification', 'Verified']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('sample_id', 'like', "%$search%")
                  ->orWhereHas('coc', fn($c) => $c->where('nomor_coc', 'like', "%$search%")->orWhere('company_name', 'like', "%$search%"));
            });
        }

        $samples = $query->latest()->paginate(20)->withQueryString();
        return view('coa.index', compact('samples', 'search'));
    }

    public function verify(Request $request, Sample $sample)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rework_reason' => 'nullable|string',
        ]);

        if ($request->action === 'approve') {
            $sample->update([
                'is_verified'  => true,
                'status_lab'   => 'Verified',
                'status'       => 'COA Ready',
                'verified_at'  => Carbon::now(),
                'rework_reason'=> null,
            ]);

            // Check if all samples in COC are verified
            $coc = $sample->coc;
            $allVerified = $coc->samples()->where('is_verified', false)->count() === 0;
            if ($allVerified) {
                $coc->update([
                    'status'     => 'Verified',
                    'tgl_selesai'=> Carbon::today(),
                    'tat_days'   => $coc->sampling_date ? $coc->sampling_date->diffInDays(Carbon::today()) : null,
                ]);
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Sampel berhasil diverifikasi.']);
            }
            return redirect()->back()->with('success', 'Sampel ' . ($sample->sample_id ?? $sample->id) . ' berhasil diverifikasi & diterbitkan CoA.');
        } else {
            // Reject - send back for rework
            $sample->update([
                'status_lab'   => 'In Analysis',
                'is_verified'  => false,
                'rework_reason'=> $request->rework_reason,
            ]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Sampel dikembalikan untuk analisa ulang.']);
            }
            return redirect()->back()->with('success', 'Sampel dikembalikan untuk analisa ulang.');
        }
    }

    public function preview(CocEmisi $coc)
    {
        $coc->load('samples');
        return view('coa.preview', compact('coc'));
    }

    public function updateScannedUrl(Request $request, CocEmisi $coc)
    {
        $request->validate(['scanned_coa_url' => 'required|url']);
        $coc->update(['scanned_coa_url' => $request->scanned_coa_url]);
        return response()->json(['success' => true]);
    }
}
