<?php

namespace App\Http\Controllers\Pengujian;

use App\Http\Controllers\Controller;
use App\Models\PengujianOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengujianJadwalController extends Controller
{
    public function index(Request $request)
    {
        $baseQueryAll = PengujianOrder::with('items')
            ->whereNotNull('no_po')
            ->where('no_po', '!=', '');

        $baseQuery = (clone $baseQueryAll);

        // Search Query & Quotation filter
        $hasSearchFilter = $request->filled('q') || $request->filled('search') || $request->filled('no_quo_filter');

        $search = trim($request->get('q', $request->get('search', '')));
        if (!empty($search)) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('no_order', 'like', "%{$search}%")
                  ->orWhere('no_quotation', 'like', "%{$search}%")
                  ->orWhere('no_po', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('alamat_pelanggan', 'like', "%{$search}%")
                  ->orWhere('petugas_sampling', 'like', "%{$search}%")
                  ->orWhere('surat_tugas_no', 'like', "%{$search}%")
                  ->orWhereHas('items', function ($qItem) use ($search) {
                      $qItem->where('nama_sampel', 'like', "%{$search}%")
                            ->orWhere('regulasi', 'like', "%{$search}%")
                            ->orWhere('parameter_uji', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('no_quo_filter')) {
            $noQuoFilter = trim($request->no_quo_filter);
            $baseQuery->where(function ($q) use ($noQuoFilter) {
                $q->where('no_quotation', 'like', "%{$noQuoFilter}%")
                  ->orWhere('no_order', 'like', "%{$noQuoFilter}%")
                  ->orWhere('no_po', 'like', "%{$noQuoFilter}%");
            });
        }

        if ($request->filled('petugas_filter')) {
            $petugasFilter = trim($request->petugas_filter);
            if ($petugasFilter === 'unassigned') {
                $baseQuery->where(function ($qP) {
                    $qP->whereNull('petugas_sampling')->orWhere('petugas_sampling', '');
                });
            } else {
                $baseQuery->where('petugas_sampling', 'like', "%{$petugasFilter}%");
            }
        }

        if ($request->filled('lokasi')) {
            $lokasi = trim($request->lokasi);
            if ($lokasi === 'On Site') {
                $baseQuery->where('tipe_pekerjaan', 'On Site');
            } elseif ($lokasi === 'In Lab') {
                $baseQuery->where('tipe_pekerjaan', 'In Lab');
            }
        }

        $tanggalJadwal = $request->get('tanggal', '');
        $activeTab = $request->get('tab', 'date');

        // 1. Unscheduled count
        $unscheduledQuery = (clone $baseQueryAll)->where(function ($q) {
            $q->whereNull('jadwal_sampling')->orWhere('jadwal_sampling', '');
        });
        $unscheduledCount = $unscheduledQuery->count();

        $verifiedOrders = collect();
        $otherSearchOrders = collect();
        $unscheduledOrders = collect();
        $allOrders = collect();

        // 2. Date Filtering if specified
        if (!empty($tanggalJadwal)) {
            $tgl = trim($tanggalJadwal);
            $isoDate = $tgl;
            try {
                if (str_contains($tgl, '/')) {
                    $isoDate = Carbon::createFromFormat('d/m/Y', $tgl)->format('Y-m-d');
                } else {
                    $isoDate = Carbon::parse($tgl)->format('Y-m-d');
                }
            } catch (\Throwable $e) {}

            $verifiedOrders = (clone $baseQueryAll)
                ->whereDate('jadwal_sampling', $isoDate)
                ->orderBy('id', 'asc')
                ->get();

            if ($hasSearchFilter) {
                $alreadyIds = $verifiedOrders->pluck('id')->toArray();
                $otherSearchOrders = (clone $baseQuery)
                    ->whereNotIn('id', $alreadyIds)
                    ->orderBy('id', 'asc')
                    ->get();
            }
        } else {
            $allOrders = (clone $baseQuery)->orderBy('id', 'desc')->get();
        }

        if ($activeTab === 'unscheduled') {
            $unscheduledOrders = $unscheduledQuery->orderBy('id', 'desc')->get();
        }

        // Final collection for list view
        if ($activeTab === 'unscheduled') {
            $orders = $unscheduledOrders;
        } elseif ($activeTab === 'all' || empty($tanggalJadwal)) {
            $orders = ($otherSearchOrders->count() > 0) ? $verifiedOrders->merge($otherSearchOrders) : $allOrders;
        } else {
            $orders = ($verifiedOrders->count() > 0 || $otherSearchOrders->count() > 0)
                ? $verifiedOrders->merge($otherSearchOrders)
                : $allOrders;
        }

        // Master Petugas / Sampler list
        $rawDbPetugas = PengujianOrder::whereNotNull('petugas_sampling')
            ->where('petugas_sampling', '!=', '')
            ->pluck('petugas_sampling');

        $masterPetugasDb = [];
        foreach ($rawDbPetugas as $pStr) {
            $names = preg_split('/[,&\/+]|\bamp\b|\bdan\b/i', $pStr);
            foreach ($names as $n) {
                $clean = trim($n);
                if (!empty($clean)) {
                    $masterPetugasDb[] = $clean;
                }
            }
        }

        $defaultPetugas = [
            'Rian Pratama, S.T.', 'Ahmad Fauzi, S.Si.', 'Budi Santoso',
            'Dimas Saputra', 'Hendra Gunawan', 'Fajar Ramadhan'
        ];
        $masterPetugas = array_values(array_unique(array_merge($defaultPetugas, $masterPetugasDb)));

        // Master orders list for 1-by-1 Quotation autocomplete selector
        $ordersData = PengujianOrder::whereNotNull('no_po')
            ->where('no_po', '!=', '')
            ->select('id', 'no_order', 'no_quotation', 'no_po', 'nama_pelanggan', 'jadwal_sampling')
            ->orderBy('id', 'desc')
            ->get();

        // Prepare Monthly Calendar View Data
        $selectedYear = intval($request->get('year', date('Y')));
        $selectedMonth = intval($request->get('month', date('n')));

        $carbonMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
        $daysInMonth = $carbonMonth->daysInMonth;
        $firstDayOfWeek = $carbonMonth->dayOfWeekIso; // 1 (Mon) to 7 (Sun)

        $allMonthOrders = PengujianOrder::with('items')
            ->whereNotNull('no_po')
            ->where('no_po', '!=', '')
            ->whereNotNull('jadwal_sampling')
            ->whereYear('jadwal_sampling', $selectedYear)
            ->whereMonth('jadwal_sampling', $selectedMonth)
            ->get();

        $calendarEvents = [];
        $scheduledDatesMap = [];

        foreach ($allMonthOrders as $mOrder) {
            if ($mOrder->jadwal_sampling) {
                $formattedKey = Carbon::parse($mOrder->jadwal_sampling)->format('Y-m-d');
                $scheduledDatesMap[$formattedKey] = true;
                $calendarEvents[$formattedKey][] = $mOrder;
            }
        }

        // View Mode: 'calendar' (default if matching kalibrasi) or 'list'
        $viewMode = $request->get('view_mode', 'calendar');

        return view('pengujian.jadwal.index', compact(
            'orders',
            'verifiedOrders',
            'unscheduledOrders',
            'allOrders',
            'unscheduledCount',
            'activeTab',
            'hasSearchFilter',
            'tanggalJadwal',
            'masterPetugas',
            'ordersData',
            'viewMode',
            'selectedYear',
            'selectedMonth',
            'daysInMonth',
            'firstDayOfWeek',
            'calendarEvents',
            'scheduledDatesMap'
        ));
    }

    public function updateJadwal(Request $request, $id)
    {
        $request->validate([
            'jadwal_sampling' => 'required|date',
            'petugas_sampling' => 'nullable|string',
            'lokasi_sampling'  => 'nullable|string',
            'catatan'          => 'nullable|string',
        ]);

        $order = PengujianOrder::findOrFail($id);

        if (empty($order->surat_tugas_no)) {
            $order->surat_tugas_no = PengujianOrder::generateNoSuratTugas();
        }

        $order->jadwal_sampling = $request->jadwal_sampling;
        $order->petugas_sampling = $request->petugas_sampling ?? $order->petugas_sampling;
        $order->lokasi_sampling = $request->lokasi_sampling ?? $order->alamat_pelanggan;
        if ($request->filled('catatan')) {
            $order->catatan = $request->catatan;
        }
        $order->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal & Surat Tugas berhasil diperbarui: ' . $order->surat_tugas_no,
                'surat_tugas_no' => $order->surat_tugas_no
            ]);
        }

        return redirect()->back()->with('success', 'Jadwal Sampling dan Surat Tugas berhasil diterbitkan: ' . $order->surat_tugas_no);
    }

    public function bulkUpdate(Request $request)
    {
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $id => $data) {
                $order = PengujianOrder::find($id);
                if ($order) {
                    if (!empty($data['jadwal_sampling'])) {
                        $order->jadwal_sampling = $data['jadwal_sampling'];
                    }
                    if (isset($data['petugas_sampling'])) {
                        $order->petugas_sampling = $data['petugas_sampling'];
                    }
                    if (empty($order->surat_tugas_no) && !empty($order->jadwal_sampling)) {
                        $order->surat_tugas_no = PengujianOrder::generateNoSuratTugas();
                    }
                    $order->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Perubahan jadwal batch berhasil disimpan.');
    }

    public function printSuratTugas($id)
    {
        $order = PengujianOrder::with('items')->findOrFail($id);
        return view('pengujian.jadwal.print_surat_tugas', compact('order'));
    }

    public function printBatch(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        $query = PengujianOrder::with('items')->whereNotNull('no_po')->where('no_po', '!=', '');

        if (!empty($selectedIds)) {
            $query->whereIn('id', $selectedIds);
        } elseif ($request->filled('tanggal')) {
            $query->whereDate('jadwal_sampling', $request->tanggal);
        }

        $orders = $query->orderBy('jadwal_sampling', 'asc')->get();

        return view('pengujian.jadwal.print_batch', compact('orders'));
    }
}
