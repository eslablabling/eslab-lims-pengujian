<?php

namespace App\Http\Controllers;

use App\Models\ClientMessage;
use App\Models\KalibrasiCustomer;
use App\Models\KalibrasiOrder;
use App\Models\KalibrasiAlat;
use App\Models\KalibrasiSertifikat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PortalKlienController extends Controller
{
    public function index(Request $request)
    {
        $uri = $request->getRequestUri();
        if (str_contains($uri, 'pengujian')) {
            return $this->indexPengujian();
        }
        if (str_contains($uri, 'kalibrasi')) {
            return $this->indexKalibrasi();
        }

        $module = session('client_default_module', 'kalibrasi');
        if ($module === 'pengujian') {
            return $this->indexPengujian();
        }
        return $this->indexKalibrasi();
    }

    public function indexPengujian()
    {
        $company = session('client_company');

        $cocs = \App\Models\CocEmisi::where('company_name', 'LIKE', '%' . $company . '%')->with('samples')->latest()->get();
        $cocIds = $cocs->pluck('id');
        $samples = \App\Models\Sample::whereIn('coc_id', $cocIds)->with('coc')->latest()->get();
        $coas = \App\Models\Sample::whereIn('coc_id', $cocIds)->where('is_verified', 1)->with('coc')->latest()->get();
        
        $samplingRequests = \DB::table('sampling_requests')->where('company_name', 'LIKE', '%' . $company . '%')->latest()->get();
        $orders = \App\Models\PengujianOrder::where('nama_pelanggan', 'LIKE', '%' . $company . '%')->with('items')->latest()->get();
        $invoices = $orders->filter(function($ord) {
            return !empty($ord->no_invoice) || in_array($ord->status_tahap, ['Invoiced', 'Lunas']);
        });
        $messages = ClientMessage::where('company_name', 'LIKE', '%' . $company . '%')->latest()->take(15)->get();

        // Cross-module Kalibrasi count for switcher badge
        $kalibrasiCount = KalibrasiOrder::where('nama_pelanggan', 'LIKE', '%' . $company . '%')->count();
        $kalibrasiCertCount = KalibrasiAlat::whereHas('order', function ($q) use ($company) {
            $q->where('nama_pelanggan', 'LIKE', '%' . $company . '%');
        })->whereHas('sertifikat')->count();

        $stats = [
            'total_samples'    => $samples->count(),
            'total_coas'       => $coas->count(),
            'in_analysis'      => $samples->where('is_verified', 0)->count(),
            'unpaid_invoices'  => $invoices->where('status_pembayaran', 'Belum Lunas')->count(),
            'kalibrasi_orders' => $kalibrasiCount,
            'kalibrasi_certs'  => $kalibrasiCertCount,
        ];

        $lingkupCmcList = $this->getLingkupCmcList();
        $lingkupPengujianList = $this->getLingkupPengujianList();

        return view('klien.pengujian.index', compact('samples', 'coas', 'samplingRequests', 'orders', 'invoices', 'messages', 'stats', 'cocs', 'lingkupCmcList', 'lingkupPengujianList'));
    }

    public function indexKalibrasi()
    {
        $company = session('client_company');

        // 1. Data Profil Perusahaan Pelanggan Kalibrasi
        $customerInfo = KalibrasiCustomer::where('nama_pelanggan', 'LIKE', '%' . $company . '%')
            ->orWhere('nama_di_sertifikat', 'LIKE', '%' . $company . '%')
            ->first();

        // 2. Data Order Kalibrasi (Permintaan, PO, Pengerjaan)
        $kalibrasiOrders = KalibrasiOrder::where('nama_pelanggan', 'LIKE', '%' . $company . '%')
            ->with(['alats.sertifikat', 'alats.input.evaluasi'])
            ->latest()
            ->get();

        // 3. Asset Management (Daftar Semua Alat Pelanggan yang Pernah Dikalibrasi)
        $kalibrasiAlats = KalibrasiAlat::whereHas('order', function ($q) use ($company) {
            $q->where('nama_pelanggan', 'LIKE', '%' . $company . '%');
        })->with(['order', 'sertifikat', 'input'])->latest()->get();

        // 4. Peringatan Jatuh Tempo Kalibrasi Ulang (Recalibration Alerts)
        $recalibrationAlerts = $kalibrasiAlats->filter(function ($alt) {
            if (!$alt->sertifikat || !$alt->sertifikat->tgl_sertifikat) {
                return false;
            }
            $certDate = Carbon::parse($alt->sertifikat->tgl_sertifikat);
            $monthsOld = $certDate->diffInMonths(Carbon::now());
            return $monthsOld >= 10;
        });

        // 5. Data Tagihan & Invoice Digital Kalibrasi
        $invoices = $kalibrasiOrders->filter(function ($order) {
            $hasIssuedInvoice = !empty($order->no_invoice)
                || !empty($order->tgl_invoice)
                || $order->is_verified_finance
                || in_array($order->status_invoice, ['Inv/DP Terbit', 'Lunas', 'Cetak']);

            $isCoaPublished = $order->status_keseluruhan === 'Sertifikat Terbit'
                || !empty($order->tgl_terbit_sertifikat)
                || strtolower($order->status_coa ?? '') === 'terbit'
                || $order->alats->contains(function ($alt) {
                    return ($alt->sertifikat && strtolower($alt->sertifikat->status ?? '') === 'terbit')
                        || $alt->status_proses === 'Sertifikat Terbit'
                        || strtolower($alt->status_sertifikat ?? '') === 'terbit';
                });

            return $hasIssuedInvoice || $isCoaPublished;
        });

        // 6. Communication Messages History
        $messages = ClientMessage::where('company_name', 'LIKE', '%' . $company . '%')
            ->latest()
            ->take(15)
            ->get();

        // Cross-module Pengujian counts
        $pengujianCocCount = \App\Models\CocEmisi::where('company_name', 'LIKE', '%' . $company . '%')->count();
        $pengujianOrderCount = \App\Models\PengujianOrder::where('nama_pelanggan', 'LIKE', '%' . $company . '%')->count();
        $pengujianSampleCount = \App\Models\Sample::whereHas('coc', function($q) use ($company) {
            $q->where('company_name', 'LIKE', '%' . $company . '%');
        })->count();

        // 7. KPI Summary Statistics
        $stats = [
            'total_order'              => $kalibrasiOrders->count(),
            'total_alat'               => $kalibrasiAlats->count(),
            'sertifikat_terbit'        => $kalibrasiAlats->filter(fn($a) => $a->sertifikat !== null)->count(),
            'alat_proses'              => $kalibrasiAlats->filter(fn($a) => $a->status_proses !== 'Selesai' && $a->sertifikat === null)->count(),
            'recalibration_due'        => $recalibrationAlerts->count(),
            'unpaid_invoices'          => $invoices->filter(fn($i) => $i->computed_status_pembayaran !== 'Sudah Lunas')->count(),
            'total_piutang'            => $invoices->sum('sisa_tagihan'),
            'pengujian_orders'         => $pengujianOrderCount + $pengujianCocCount,
            'pengujian_samples'        => $pengujianSampleCount,
        ];

        // 8. Scope & CMC Lists
        $lingkupCmcList = $this->getLingkupCmcList();
        $lingkupPengujianList = $this->getLingkupPengujianList();

        // 9. Master Parameters & Master Hargas Data (Grouped & Deduplicated for autofill Nama Alat, Method, & Cal Point)
        $rawParams = \App\Models\KalibrasiParameter::select('nama_parameter', 'rentang_ukur', 'metode')
            ->whereNotNull('nama_parameter')
            ->orderBy('nama_parameter', 'asc')
            ->get();

        $groupedParams = [];
        foreach ($rawParams as $p) {
            $name = trim($p->nama_parameter);
            if (empty($name)) continue;
            if (!isset($groupedParams[$name])) {
                $groupedParams[$name] = [
                    'nama_parameter' => $name,
                    'metode'         => $p->metode ?? '',
                    'rentang_list'   => [],
                ];
            }
            if (!empty($p->rentang_ukur) && !in_array($p->rentang_ukur, $groupedParams[$name]['rentang_list'])) {
                $groupedParams[$name]['rentang_list'][] = $p->rentang_ukur;
            }
            if (empty($groupedParams[$name]['metode']) && !empty($p->metode)) {
                $groupedParams[$name]['metode'] = $p->metode;
            }
        }

        $masterParametersData = [];
        foreach ($groupedParams as $gp) {
            $gp['rentang_ukur'] = implode('; ', $gp['rentang_list']);
            unset($gp['rentang_list']);
            $masterParametersData[] = $gp;
        }

        $rawHargas = \App\Models\KalibrasiMasterHarga::select('nama_alat', 'satuan', 'tipe_perhitungan', 'harga_jual', 'kategori_kelompok', 'keterangan')
            ->orderBy('nama_alat', 'asc')
            ->get();

        $groupedHargas = [];
        foreach ($rawHargas as $h) {
            $name = trim($h->nama_alat);
            if (empty($name) || isset($groupedHargas[$name])) continue;
            $groupedHargas[$name] = $h->toArray();
        }
        $masterHargasData = array_values($groupedHargas);



        // Split orders into Requests/Quotations vs Confirmed PO Orders
        $requestOrders = $kalibrasiOrders->filter(function($order) {
            return empty($order->no_po);
        });

        $poOrders = $kalibrasiOrders->filter(function($order) {
            return !empty($order->no_po);
        });

        $viewName = view()->exists('klien.kalibrasi.index') ? 'klien.kalibrasi.index' : 'portal-klien.index';

        return view($viewName, compact(
            'customerInfo',
            'kalibrasiOrders',
            'requestOrders',
            'poOrders',
            'kalibrasiAlats',
            'recalibrationAlerts',
            'invoices',
            'messages',
            'lingkupCmcList',
            'lingkupPengujianList',
            'stats',
            'masterParametersData',
            'masterHargasData'
        ));
    }

    public function submitKalibrasiRequest(Request $request)
    {
        $request->validate([
            'contact_person'  => 'required|string',
            'no_hp_email'     => 'required|string',
            'tipe_pekerjaan'  => 'required|in:in_lab,on_site',
            'daftar_alat'     => 'nullable|string',
            'items'           => 'nullable|array',
            'catatan'         => 'nullable|string',
        ]);

        $company = session('client_company');
        $orderCount = KalibrasiOrder::count() + 1;
        $noOrder = 'ORD-REQ-' . date('Ym') . '-' . sprintf('%04d', $orderCount);

        $items = $request->input('items', []);
        $formattedList = [];

        if (!empty($items) && is_array($items)) {
            foreach ($items as $idx => $item) {
                $nama = $item['nama_alat'] ?? '';
                if (empty($nama)) continue;
                $merk = $item['merk'] ?? '';
                $tipe = $item['tipe'] ?? '';
                $noseri = $item['no_seri'] ?? '';
                $calPoint = $item['cal_point'] ?? ($item['rentang_ukur'] ?? '');
                $metode = $item['metode'] ?? '';
                $calLoc = $item['cal_loc'] ?? ($request->tipe_pekerjaan ?? 'in_lab');
                $qty = max(1, (int)($item['qty'] ?? 1));

                $desc = ($idx + 1) . ". " . $nama . ($merk ? " (Merk: {$merk})" : "") . ($tipe ? " (Tipe: {$tipe})" : "") . ($noseri ? " (S/N: {$noseri})" : "") . ($calPoint ? " [Cal Point: {$calPoint}]" : "") . ($metode ? " [Metode: {$metode}]" : "") . " [Loc: " . strtoupper(str_replace('_', ' ', $calLoc)) . "] - {$qty} Unit";
                $formattedList[] = $desc;
            }
        }

        $daftarAlatSummary = !empty($formattedList) ? implode("\n", $formattedList) : ($request->daftar_alat ?? 'Daftar Alat Kalibrasi Baru');

        $order = KalibrasiOrder::create([
            'no_order'          => $noOrder,
            'nama_pelanggan'    => $company,
            'kontak_person'     => $request->contact_person,
            'no_hp_email'       => $request->no_hp_email,
            'tipe_pekerjaan'    => $request->tipe_pekerjaan,
            'status'            => 'Quotation',
            'status_verifikasi' => 'Menunggu Verifikasi',
            'alasan_verifikasi' => null,
            'tanggal_masuk'     => now()->toDateString(),
            'catatan'           => "Permintaan Kalibrasi dari Portal Customer.\nDaftar Alat:\n" . $daftarAlatSummary . ($request->catatan ? "\nCatatan Tambahan: " . $request->catatan : ""),
        ]);

        // Save individual KalibrasiAlat records
        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                $nama = $item['nama_alat'] ?? '';
                if (empty($nama)) continue;
                $merk = $item['merk'] ?? '';
                $tipe = $item['tipe'] ?? '';
                $noseri = $item['no_seri'] ?? '';
                $calPoint = $item['cal_point'] ?? ($item['rentang_ukur'] ?? '');
                $metode = $item['metode'] ?? '';
                $calLoc = $item['cal_loc'] ?? ($request->tipe_pekerjaan ?? 'in_lab');
                $qty = max(1, (int)($item['qty'] ?? 1));

                for ($i = 0; $i < $qty; $i++) {
                    KalibrasiAlat::create([
                        'order_id'           => $order->id,
                        'kode_alat_item'     => 'ALT-REQ-' . date('Ym') . '-' . rand(1000, 9999),
                        'nama_alat'          => $nama,
                        'merk'               => $merk,
                        'tipe_model'         => $tipe,
                        'nomor_seri'         => $noseri ?: '-',
                        'kategori_kan'       => 'Lain-lain',
                        'rentang_ukur'       => $calPoint,
                        'cal_poin_parameter' => $calPoint,
                        'metode'             => $metode,
                        'lokasi_pekerjaan'   => $calLoc,
                        'qty'                => 1,
                        'status_proses'      => 'Quotation',
                    ]);
                }



            }
        }


        ClientMessage::create([
            'company_name' => $company,
            'sender'       => 'klien',
            'sender_name'  => $request->contact_person ?? $company,
            'subject'      => 'Permintaan Kalibrasi Baru',
            'message'      => "Permintaan Kalibrasi Baru (#" . $noOrder . ") diajukan oleh " . $request->contact_person . " (" . $request->no_hp_email . "). Tipe: " . strtoupper($request->tipe_pekerjaan),
        ]);



        return response()->json([
            'success' => true,
            'message' => 'Permohonan kalibrasi berhasil diajukan dengan Nomor Permintaan: ' . $noOrder,
        ]);
    }


    public function uploadBuktiBayar(Request $request)
    {
        $request->validate([
            'order_id'       => 'required|exists:kalibrasi_orders,id',
            'nomor_referensi'=> 'required|string',
            'catatan_bayar'  => 'nullable|string',
        ]);

        $company = session('client_company');
        $order = KalibrasiOrder::where('id', $request->order_id)
            ->where('nama_pelanggan', 'LIKE', '%' . $company . '%')
            ->firstOrFail();

        $catatanAwal = $order->catatan ?? '';
        $catatanBaru = trim($catatanAwal . "\n[KONFIRMASI BAYAR KLIEN]: Ref #" . $request->nomor_referensi . " | Tgl: " . now()->format('Y-m-d H:i') . ($request->catatan_bayar ? " | Info: " . $request->catatan_bayar : ""));

        $order->update([
            'catatan' => $catatanBaru,
        ]);

        ClientMessage::create([
            'company_name' => $company,
            'sender'       => 'klien',
            'message'      => "Konfirmasi Pembayaran untuk Order #" . $order->no_order . " / Inv #" . ($order->no_quotation ?? '-') . " (No. Ref: " . $request->nomor_referensi . ")",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konfirmasi pembayaran berhasil dikirimkan ke tim Finance Kalibrasi LIMS.',
        ]);
    }

    public function deleteKalibrasiRequest($id)
    {
        $company = session('client_company');
        $order = KalibrasiOrder::where('id', $id)
            ->where('nama_pelanggan', 'LIKE', '%' . $company . '%')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Data permintaan tidak ditemukan.'], 404);
        }

        // Delete associated KalibrasiAlat records and order
        KalibrasiAlat::where('order_id', $order->id)->delete();
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permintaan kalibrasi (' . $order->no_order . ') berhasil dihapus.',
        ]);
    }

    public function updateKalibrasiRequest(Request $request, $id)
    {
        $request->validate([
            'contact_person' => 'required|string',
            'no_hp_email'    => 'required|string',
            'tipe_pekerjaan' => 'required|string',
        ]);

        $company = session('client_company');
        $order = KalibrasiOrder::where('id', $id)
            ->where('nama_pelanggan', 'LIKE', '%' . $company . '%')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Data permintaan tidak ditemukan.'], 404);
        }

        $items = $request->input('items', []);
        $formattedList = [];

        if (!empty($items) && is_array($items)) {
            foreach ($items as $idx => $item) {
                $nama = $item['nama_alat'] ?? '';
                if (empty($nama)) continue;
                $merk = $item['merk'] ?? '';
                $tipe = $item['tipe'] ?? '';
                $noseri = $item['no_seri'] ?? '';
                $calPoint = $item['cal_point'] ?? ($item['rentang_ukur'] ?? '');
                $metode = $item['metode'] ?? '';
                $calLoc = $item['cal_loc'] ?? ($request->tipe_pekerjaan ?? 'in_lab');
                $qty = max(1, (int)($item['qty'] ?? 1));

                $desc = ($idx + 1) . ". " . $nama . ($merk ? " (Merk: {$merk})" : "") . ($tipe ? " (Tipe: {$tipe})" : "") . ($noseri ? " (S/N: {$noseri})" : "") . ($calPoint ? " [Cal Point: {$calPoint}]" : "") . ($metode ? " [Metode: {$metode}]" : "") . " [Loc: " . strtoupper(str_replace('_', ' ', $calLoc)) . "] - {$qty} Unit";
                $formattedList[] = $desc;
            }
        }

        $daftarAlatSummary = !empty($formattedList) ? implode("\n", $formattedList) : 'Daftar Alat Kalibrasi Updated';

        $order->update([
            'kontak_person'     => $request->contact_person,
            'no_hp_email'       => $request->no_hp_email,
            'tipe_pekerjaan'    => $request->tipe_pekerjaan,
            'status_verifikasi' => 'Menunggu Verifikasi',
            'alasan_verifikasi' => null,
            'catatan'           => "Permintaan Kalibrasi (Updated) dari Portal Customer.\nDaftar Alat:\n" . $daftarAlatSummary . ($request->catatan ? "\nCatatan Tambahan: " . $request->catatan : ""),
        ]);

        // Replace old KalibrasiAlat records with updated items
        KalibrasiAlat::where('order_id', $order->id)->delete();

        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                $nama = $item['nama_alat'] ?? '';
                if (empty($nama)) continue;
                $merk = $item['merk'] ?? '';
                $tipe = $item['tipe'] ?? '';
                $noseri = $item['no_seri'] ?? '';
                $calPoint = $item['cal_point'] ?? ($item['rentang_ukur'] ?? '');
                $metode = $item['metode'] ?? '';
                $calLoc = $item['cal_loc'] ?? ($request->tipe_pekerjaan ?? 'in_lab');
                $qty = max(1, (int)($item['qty'] ?? 1));

                for ($i = 0; $i < $qty; $i++) {
                    KalibrasiAlat::create([
                        'order_id'           => $order->id,
                        'kode_alat_item'     => 'ALT-REQ-' . date('Ym') . '-' . rand(1000, 9999),
                        'nama_alat'          => $nama,
                        'merk'               => $merk,
                        'tipe_model'         => $tipe,
                        'nomor_seri'         => $noseri ?: '-',
                        'kategori_kan'       => 'Lain-lain',
                        'rentang_ukur'       => $calPoint,
                        'cal_poin_parameter' => $calPoint,
                        'metode'             => $metode,
                        'lokasi_pekerjaan'   => $calLoc,
                        'qty'                => 1,
                        'status_proses'      => 'Quotation',
                    ]);
                }
            }
        }

        $isQuotationExist = !empty($order->no_quotation) || !empty($order->no_qt);
        if ($isQuotationExist) {
            $order->status = 'Revisi Quotation';
            $order->save();

            ClientMessage::create([
                'company_name' => $company,
                'sender'       => 'klien',
                'sender_name'  => $request->contact_person ?? $company,
                'subject'      => 'Revisi Quotation Diajukan Klien',
                'message'      => "Klien mengajukan Revisi Rincian Alat pada Quotation #" . ($order->no_quotation ?? $order->no_order) . ". Silakan verifikasi dan perbarui penawaran harga.",
            ]);

            $msgText = 'Revisi rincian alat pada Quotation (' . ($order->no_quotation ?? $order->no_order) . ') berhasil dikirimkan ke Admin Marketing untuk diverifikasi.';
        } else {
            $msgText = 'Permintaan kalibrasi (' . $order->no_order . ') berhasil diperbarui.';
        }

        return response()->json([
            'success' => true,
            'message' => $msgText,
        ]);
    }

    public function verifyDocument(Request $request)
    {
        $query = trim($request->input('query', ''));
        $clientCompany = session('client_company', '');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan Nomor Sertifikat, Nomor Barcode / QR, atau Nomor Quotation.'
            ]);
        }

        // 1. Search in Kalibrasi Sertifikat
        $sertifikat = KalibrasiSertifikat::with(['alat.order', 'alat.input'])
            ->where('no_sertifikat', 'LIKE', "%{$query}%")
            ->orWhere('qr_code_token', $query)
            ->orWhere('id', $query)
            ->orWhereHas('alat.order', function($q) use ($query) {
                $q->where('no_order', 'LIKE', "%{$query}%")
                  ->orWhere('no_quotation', 'LIKE', "%{$query}%");
            })
            ->first();

        if ($sertifikat) {
            $docCompany = $sertifikat->alat?->order?->nama_pelanggan 
                ?? $sertifikat->alat?->order?->nama_customer 
                ?? '';
            
            // Check company match for security and privacy
            if ($clientCompany && !str_contains(strtolower($docCompany), strtolower(trim($clientCompany))) && !str_contains(strtolower(trim($clientCompany)), strtolower(trim($docCompany)))) {
                return response()->json([
                    'success' => false,
                    'owner_mismatch' => true,
                    'message' => "Akses Ditolak: Dokumen #{$query} terdaftar atas nama perusahaan lain. Sesuai kebijakan kerahasiaan data (ISO/IEC 17025:2017), Anda hanya dapat memverifikasi dokumen milik {$clientCompany}."
                ]);
            }

            return response()->json([
                'success' => true,
                'type' => 'kalibrasi',
                'badge_title' => 'Sertifikat Kalibrasi Terakreditasi KAN LK-361-IDN',
                'data' => [
                    'no_dokumen' => $sertifikat->no_sertifikat ?? 'Sertifikat #' . $sertifikat->id,
                    'no_order' => $sertifikat->alat?->order?->no_order ?? '-',
                    'no_quotation' => $sertifikat->alat?->order?->no_quotation ?? '-',
                    'nama_item' => $sertifikat->alat?->nama_alat ?? 'Alat Kalibrasi',
                    'merk_tipe' => ($sertifikat->alat?->merk ?? '-') . ' / ' . ($sertifikat->alat?->tipe_model ?? $sertifikat->alat?->model ?? '-'),
                    'no_seri' => $sertifikat->alat?->nomor_seri ?? $sertifikat->alat?->no_seri ?? '-',
                    'customer' => $docCompany,
                    'tgl_pelaksanaan' => $sertifikat->tgl_kalibrasi ? date('d F Y', strtotime($sertifikat->tgl_kalibrasi)) : '-',
                    'status_keaslian' => 'TERVERIFIKASI ASLI & RESMI KAN (LK-361-IDN)',
                    'verifikator' => $sertifikat->verified_by_qms ?? 'Tim Penjaminan Mutu (QMS)',
                    'link_view' => url('/kalibrasi/coa/' . $sertifikat->id . '/print'),
                ]
            ]);
        }

        // 2. Search in Pengujian Sample / COA
        $sample = \App\Models\Sample::with('coc')
            ->where('sample_id', 'LIKE', "%{$query}%")
            ->orWhere('id', is_numeric($query) ? $query : 0)
            ->orWhereHas('coc', function($q) use ($query) {
                $q->where('nomor_coc', 'LIKE', "%{$query}%")
                  ->orWhere('company_name', 'LIKE', "%{$query}%");
            })
            ->first();

        if ($sample) {
            $docCompany = $sample->coc?->company_name ?? '';
            if ($clientCompany && !str_contains(strtolower($docCompany), strtolower(trim($clientCompany))) && !str_contains(strtolower(trim($clientCompany)), strtolower(trim($docCompany)))) {
                return response()->json([
                    'success' => false,
                    'owner_mismatch' => true,
                    'message' => "Akses Ditolak: Dokumen pengujian #{$query} terdaftar atas nama perusahaan lain. Sesuai kebijakan kerahasiaan data (ISO/IEC 17025:2017), Anda hanya dapat memverifikasi dokumen milik {$clientCompany}."
                ]);
            }

            return response()->json([
                'success' => true,
                'type' => 'pengujian',
                'badge_title' => 'Laporan Hasil Pengujian (CoA) Terakreditasi KAN LP-1813-IDN',
                'data' => [
                    'no_dokumen' => $sample->sample_id ?? ('SMP-' . str_pad($sample->id, 5, '0', STR_PAD_LEFT)),
                    'no_order' => $sample->coc?->nomor_coc ?? '-',
                    'no_quotation' => '-',
                    'nama_item' => $sample->description ?? $sample->nama_cerobong ?? 'Sampel Pengujian Lingkungan',
                    'merk_tipe' => 'Status Lab: ' . ($sample->status_lab ?? ($sample->is_verified ? 'Verified' : 'Dalam Analisis')),
                    'no_seri' => 'Matriks: ' . ($sample->coc?->matriks ?? 'Air / Udara'),
                    'customer' => $docCompany,
                    'tgl_pelaksanaan' => $sample->tgl_sampling ? date('d F Y', strtotime($sample->tgl_sampling)) : '-',
                    'status_keaslian' => 'TERVERIFIKASI ASLI & RESMI KAN (LP-1813-IDN)',
                    'verifikator' => $sample->is_verified ? 'Penyelia Lab Pengujian Lingkungan' : 'Dalam Proses Analisis',
                    'link_view' => url('/pengujian/klien/portal'),
                ]
            ]);
        }

        // 3. Search in Pengujian Order / Quotation
        $pengOrder = \App\Models\PengujianOrder::where('no_quotation', 'LIKE', "%{$query}%")
            ->orWhere('no_order', 'LIKE', "%{$query}%")
            ->first();

        if ($pengOrder) {
            $docCompany = $pengOrder->nama_pelanggan ?? '';
            if ($clientCompany && !str_contains(strtolower($docCompany), strtolower(trim($clientCompany))) && !str_contains(strtolower(trim($clientCompany)), strtolower(trim($docCompany)))) {
                return response()->json([
                    'success' => false,
                    'owner_mismatch' => true,
                    'message' => "Akses Ditolak: Dokumen quotation #{$query} terdaftar atas nama perusahaan lain. Anda hanya dapat memverifikasi dokumen milik {$clientCompany}."
                ]);
            }

            return response()->json([
                'success' => true,
                'type' => 'pengujian_order',
                'badge_title' => 'Dokumen Quotation / Order Pengujian Lingkungan',
                'data' => [
                    'no_dokumen' => $pengOrder->no_quotation ?? $pengOrder->no_order,
                    'no_order' => $pengOrder->no_order ?? '-',
                    'no_quotation' => $pengOrder->no_quotation ?? '-',
                    'nama_item' => $pengOrder->perihal ?? 'Layanan Pengujian Lingkungan',
                    'merk_tipe' => 'Tahap: ' . ($pengOrder->status_tahap ?? '-'),
                    'no_seri' => 'Total: Rp ' . number_format($pengOrder->grand_total, 0, ',', '.'),
                    'customer' => $docCompany,
                    'tgl_pelaksanaan' => $pengOrder->tgl_surat ? date('d F Y', strtotime($pengOrder->tgl_surat)) : '-',
                    'status_keaslian' => 'DOKUMEN RESMI PT ENVIROTAMA SOLUSINDO',
                    'verifikator' => 'Bagian Komersial & Teknis',
                    'link_view' => url('/pengujian/permintaan'),
                ]
            ]);
        }

        // 4. Search in Kalibrasi Order / Quotation
        $kalOrder = KalibrasiOrder::where('no_quotation', 'LIKE', "%{$query}%")
            ->orWhere('no_order', 'LIKE', "%{$query}%")
            ->first();

        if ($kalOrder) {
            $docCompany = $kalOrder->nama_pelanggan ?? $kalOrder->nama_customer ?? '';
            if ($clientCompany && !str_contains(strtolower($docCompany), strtolower(trim($clientCompany))) && !str_contains(strtolower(trim($clientCompany)), strtolower(trim($docCompany)))) {
                return response()->json([
                    'success' => false,
                    'owner_mismatch' => true,
                    'message' => "Akses Ditolak: Dokumen order/quotation #{$query} terdaftar atas nama perusahaan lain. Anda hanya dapat memverifikasi dokumen milik {$clientCompany}."
                ]);
            }

            return response()->json([
                'success' => true,
                'type' => 'kalibrasi_order',
                'badge_title' => 'Dokumen Order / Quotation Kalibrasi Terakreditasi',
                'data' => [
                    'no_dokumen' => $kalOrder->no_quotation ?? $kalOrder->no_order,
                    'no_order' => $kalOrder->no_order ?? '-',
                    'no_quotation' => $kalOrder->no_quotation ?? '-',
                    'nama_item' => 'Order Kalibrasi ' . ($kalOrder->alats?->count() ?? 0) . ' Alat',
                    'merk_tipe' => 'Status: ' . ($kalOrder->status_keseluruhan ?? $kalOrder->status ?? '-'),
                    'no_seri' => 'PO: ' . ($kalOrder->no_po ?? 'Belum terbit PO'),
                    'customer' => $docCompany,
                    'tgl_pelaksanaan' => $kalOrder->created_at ? $kalOrder->created_at->format('d F Y') : '-',
                    'status_keaslian' => 'DOKUMEN RESMI PT ENVIROTAMA SOLUSINDO (LK-361-IDN)',
                    'verifikator' => 'Bagian Administrasi Kalibrasi',
                    'link_view' => url('/kalibrasi/klien/portal'),
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Dokumen dengan nomor atau barcode '{$query}' tidak ditemukan dalam database resmi. Pastikan format nomor yang Anda masukkan sudah benar."
        ]);
    }

    public function getLingkupCmcList(): array
    {
        return [
            // 1. Suhu & Kelembapan
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Inkubator', 'rentang' => 'Ambient ~ 50 °C', 'cmc' => '0.50 °C', 'metode' => 'KAN Pd-02.04:2019'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Oven', 'rentang' => 'Ambient ~ 250 °C', 'cmc' => '0.72 °C', 'metode' => 'KAN Pd-02.04:2019'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Waterbath', 'rentang' => 'Ambient ~ 100 °C', 'cmc' => '0.55 °C', 'metode' => 'KAN Pd-02.04:2019'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Climatic Chamber (Suhu)', 'rentang' => 'Ambient ~ 150 °C', 'cmc' => '0.86 °C', 'metode' => 'DKD-R 5-7:2004'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Climatic Chamber (Kelembapan)', 'rentang' => '40 %RH ~ 90 %RH', 'cmc' => '2.8 %RH', 'metode' => 'DKD-R 5-7:2004'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Chiller / Refrigerator / Freezer', 'rentang' => '-20 °C ~ 20 °C', 'cmc' => '0.50 °C ~ 0.64 °C', 'metode' => 'KAN Pd-02.04'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Autoclave (Suhu)', 'rentang' => '105 °C ~ 135 °C', 'cmc' => '0.21 °C', 'metode' => 'IKM-ES-7.2.35'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Autoclave (Tekanan)', 'rentang' => '0.1 MPa ~ 0.25 MPa', 'cmc' => '0.0058 MPa', 'metode' => 'IKM-ES-7.2.35'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'COD Reaktor', 'rentang' => 'Ambient ~ 400 °C', 'cmc' => '0.23 °C', 'metode' => 'IKM-ES-7.2.36'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Hot Plate', 'rentang' => '50 °C ~ 400 °C', 'cmc' => '0.63 °C', 'metode' => 'IKM-ES-7.2.50'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Furnace', 'rentang' => 'Ambient ~ 1000 °C', 'cmc' => '0.93 °C ~ 4.8 °C', 'metode' => 'IKM-ES-7.2.36'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Thermohygrometer (Suhu)', 'rentang' => '0 °C ~ 50 °C', 'cmc' => '1.3 °C', 'metode' => 'IKM-ES-7.2.24'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Thermohygrometer (Kelembapan)', 'rentang' => '30 %RH ~ 90 %RH', 'cmc' => '3.0 %RH', 'metode' => 'IKM-ES-7.2.24'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Termometer Gelas', 'rentang' => '-20 °C ~ 100 °C', 'cmc' => '0.72 °C', 'metode' => 'SNSU PK.S-01:2020'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Thermometer Sensor with Display', 'rentang' => '-25 °C ~ 400 °C', 'cmc' => '0.096 °C ~ 0.22 °C', 'metode' => 'JIS Z 8710:1993 / ASTM E3186'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Thermometer Display (Type J, K, T)', 'rentang' => '-200 °C ~ 1300 °C', 'cmc' => '0.28 °C', 'metode' => 'EURAMET/cg-11/v.2.0'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Thermometer Infrared', 'rentang' => '0 °C ~ 500 °C', 'cmc' => '1.4 °C ~ 2.4 °C', 'metode' => 'ASTM E 2847-14'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Micro Oil Bath / Dry Block', 'rentang' => '30 °C ~ 400 °C', 'cmc' => '0.50 °C ~ 1.7 °C', 'metode' => 'KAN Pd-02.04:2019'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Temperature Sensor / RTD', 'rentang' => '-200 °C ~ 800 °C', 'cmc' => '0.26 °C', 'metode' => 'SNSU PK.S-04:2023'],
            ['kategori' => 'Suhu & Kelembapan', 'alat' => 'Temperature Transmitter / Transducer', 'rentang' => '-25 °C ~ 400 °C', 'cmc' => '0.060 °C ~ 0.20 °C', 'metode' => 'IKM-ES-7.2.62'],

            // 2. Massa & Timbangan
            ['kategori' => 'Massa & Timbangan', 'alat' => 'Anak Timbangan (Class E2, F1, M1)', 'rentang' => '1 mg ~ 20 kg', 'cmc' => '0.0083 mg ~ 86 mg', 'metode' => 'CSIRO 2010'],
            ['kategori' => 'Massa & Timbangan', 'alat' => 'Timbangan Elektronik & Analitik', 'rentang' => '0 g ~ 2000 kg', 'cmc' => '0.11 mg ~ 1.4 kg', 'metode' => 'CSIRO 2010 / IKM-ES-7.2.22'],
            ['kategori' => 'Massa & Timbangan', 'alat' => 'Timbangan Mekanik (Platform / Spring)', 'rentang' => '0 kg ~ 500 kg', 'cmc' => '1.2 g ~ 25 g', 'metode' => 'IKM-ES-7.2.22'],

            // 3. Volume & Volumetrik
            ['kategori' => 'Volume & Volumetrik', 'alat' => 'Mikropipet / Makropipet', 'rentang' => '50 µL ~ 10000 µL', 'cmc' => '0.35 µL ~ 19 µL', 'metode' => 'SNSU PK.M-01:2020'],
            ['kategori' => 'Volume & Volumetrik', 'alat' => 'Dispenser', 'rentang' => '1 mL ~ 100 mL', 'cmc' => '0.0032 mL ~ 0.10 mL', 'metode' => 'ISO 8655-5:2002'],
            ['kategori' => 'Volume & Volumetrik', 'alat' => 'Pipet Volume', 'rentang' => '2 mL ~ 100 mL', 'cmc' => '0.0039 mL ~ 0.031 mL', 'metode' => 'KAN Pd-02.08:2019'],
            ['kategori' => 'Volume & Volumetrik', 'alat' => 'Labu Ukur', 'rentang' => '10 mL ~ 1000 mL', 'cmc' => '0.01 mL ~ 0.2 mL', 'metode' => 'KAN Pd-02.08:2019'],
            ['kategori' => 'Volume & Volumetrik', 'alat' => 'Buret', 'rentang' => '10 mL ~ 100 mL', 'cmc' => '0.0089 mL ~ 0.039 mL', 'metode' => 'KAN Pd-02.08:2019'],
            ['kategori' => 'Volume & Volumetrik', 'alat' => 'Dry/Wet Gas Meter', 'rentang' => '10 L', 'cmc' => '0.80 L', 'metode' => 'IKM-ES-7.2.2'],

            // 4. Tekanan & Pneumatik
            ['kategori' => 'Tekanan & Pneumatik', 'alat' => 'Pressure Gauge', 'rentang' => '0 mbar ~ 700 bar', 'cmc' => '0.076 bar ~ 2.9 bar', 'metode' => 'IKM-ES-7.2.15'],
            ['kategori' => 'Tekanan & Pneumatik', 'alat' => 'Vacuum Gauge', 'rentang' => '-0.90 bar ~ 0 bar', 'cmc' => '0.020 bar', 'metode' => 'EURAMET Guide No. 17'],
            ['kategori' => 'Tekanan & Pneumatik', 'alat' => 'Pressure Transmitter / Transducer', 'rentang' => '0 MPa ~ 70 MPa', 'cmc' => '0.028 MPa', 'metode' => 'EURAMET Guide No. 17'],

            // 5. Instrumen Aliran (Flow)
            ['kategori' => 'Instrumen Aliran (Flow)', 'alat' => 'Low Gas Flowmeter', 'rentang' => '0.1 L/min ~ 2 L/min', 'cmc' => '0.057 L/min', 'metode' => 'IKM-ES-7.2.2'],
            ['kategori' => 'Instrumen Aliran (Flow)', 'alat' => 'High Flowmeter', 'rentang' => '40 L/min ~ 200 L/min', 'cmc' => '6.2 L/min', 'metode' => 'IKM-ES-7.2.27'],
            ['kategori' => 'Instrumen Aliran (Flow)', 'alat' => 'Gas Flowmeter', 'rentang' => '1.5 L/min ~ 25 L/min', 'cmc' => '0.28 L/min ~ 0.62 L/min', 'metode' => 'IKM-ES-7.2.2'],
            ['kategori' => 'Instrumen Aliran (Flow)', 'alat' => 'Flowmeter Air', 'rentang' => '10 L/min ~ 210 L/min', 'cmc' => '1.5 L/min ~ 3.1 L/min', 'metode' => 'IKM-ES-7.2.48'],
            ['kategori' => 'Instrumen Aliran (Flow)', 'alat' => 'Anemometer', 'rentang' => '0.15 m/s ~ 10 m/s', 'cmc' => '0.14 m/s ~ 0.29 m/s', 'metode' => 'IKM-ES-7.2.25'],
            ['kategori' => 'Instrumen Aliran (Flow)', 'alat' => 'High Volume Air Sampler (HVAS)', 'rentang' => '0.7 m³/min ~ 1.4 m³/min', 'cmc' => '0.058 m³/min', 'metode' => 'IKM-ES-7.2.3'],
            ['kategori' => 'Instrumen Aliran (Flow)', 'alat' => 'Totalizer', 'rentang' => '0 Liter ~ 2000 Liter', 'cmc' => '0.11 Liter ~ 34 Liter', 'metode' => 'ISO 4185:1980'],

            // 6. Kimia, Analitik & CEMS
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'pH Meter', 'rentang' => '4 pH, 7 pH, 10 pH', 'cmc' => '0.021 pH ~ 0.031 pH', 'metode' => 'ASTM E70-19'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Conductivity Meter', 'rentang' => '10 µS/cm ~ 12880 µS/cm', 'cmc' => '0.28 µS/cm ~ 76 µS/cm', 'metode' => 'IKM-ES-7.2.14'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Viscometer', 'rentang' => '50.08 cP ~ 29363 cP', 'cmc' => '0.47% ~ 1.6% cP', 'metode' => 'JIS Z 8803:2011'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Viscometer Cup (Zahn Cup)', 'rentang' => '53.42 cSt ~ 1051 cSt', 'cmc' => '0.46% ~ 0.51% cSt', 'metode' => 'ASTM D 4212-99'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Spektrofotometer (Absorban & Panjang Gelombang)', 'rentang' => '0.3 Abs ~ 1.5 Abs | 399.6 nm ~ 782.8 nm', 'cmc' => '0.0033 Abs | 0.97 nm', 'metode' => 'SNSU PK.F-01:2020'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Flue Gas Analyzer / Emission Analyzer', 'rentang' => 'O₂ (3-20.9%), CO (10-500ppm), CO₂ (0.5-16.8%), NO/NO₂/SO₂', 'cmc' => '0.15% O₂ | 2.1 ppm CO | 0.083% CO₂', 'metode' => 'IKM-ES-7.2.1'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Gas Detector', 'rentang' => 'O₂, CO, CO₂, NH₃, CH₄, H₂S, SO₂', 'cmc' => '0.15% O₂ | 2.1 ppm CO | 5.0 ppm NH₃', 'metode' => 'IKM-ES-7.2.1'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Continuous Emission Monitoring (CEMS)', 'rentang' => 'O₂ (12-20.9%), CO (4.7%-250ppm), CO₂ (0.5-16.8%), NO/NO₂/SO₂', 'cmc' => '0.43% O₂ | 2.1 ppm CO | 0.083% CO₂', 'metode' => 'IKM-ES-7.2.1'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'DO Meter (Dissolved Oxygen)', 'rentang' => '7.78 mg/L & 0 %O₂', 'cmc' => '0.34 mg/L & 0.14 %O₂', 'metode' => 'IKM-ES-7.2.44'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Chlorine Meter', 'rentang' => '1 mg/L', 'cmc' => '0.086 mg/L', 'metode' => 'IKM-ES-7.2.45'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'TDS Meter', 'rentang' => '500 mg/L ~ 2000 mg/L', 'cmc' => '1.7 mg/L ~ 5.5 mg/L', 'metode' => 'IKM-ES-7.2.46'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Refraktometer', 'rentang' => '40 %brix', 'cmc' => '0.06 %brix', 'metode' => 'IKM-ES-7.2.43'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Turbiditymeter', 'rentang' => '15 NTU ~ 4000 NTU', 'cmc' => '0.12 NTU ~ 38 NTU', 'metode' => 'IKM-ES-7.2.21'],
            ['kategori' => 'Kimia, Analitik & CEMS', 'alat' => 'Opacity Smoke Meter', 'rentang' => '14 % ~ 79.7 %', 'cmc' => '0.39 % ~ 0.60 %', 'metode' => 'Manual Qrotech QDO-6000'],

            // 7. Kelistrikan & Fotometri
            ['kategori' => 'Kelistrikan & Fotometri', 'alat' => 'Digital Multimeter (DCV, DCI, ACV, ACI, R, F)', 'rentang' => '2 mV ~ 1000 V | 0.02 mA ~ 3 A | 2 Ω ~ 2 MΩ', 'cmc' => '0.0057 mV | 0.0012 A | 3.8 Ω', 'metode' => 'SNSU PK.E-01:2021'],
            ['kategori' => 'Kelistrikan & Fotometri', 'alat' => 'Tang Ampere Meter / Clamp Meter', 'rentang' => 'DC/AC Current 2 A ~ 950 A', 'cmc' => '0.14 A ~ 11 A', 'metode' => 'SNSU PK.E-01:2021'],
            ['kategori' => 'Kelistrikan & Fotometri', 'alat' => 'Decade Resistance Box', 'rentang' => '10 Ω ~ 100 MΩ', 'cmc' => '0.055 Ω ~ 0.020 MΩ', 'metode' => 'SNSU PK.E-01:2021'],
            ['kategori' => 'Kelistrikan & Fotometri', 'alat' => 'Holmium Oxide Glass Filter', 'rentang' => '240 nm ~ 650 nm', 'cmc' => '0.38 nm', 'metode' => 'IKM-ES-7.2.39'],
            ['kategori' => 'Kelistrikan & Fotometri', 'alat' => 'Neutral Density Filter', 'rentang' => '10 %T ~ 100 %T (0 Abs ~ 1 Abs)', 'cmc' => '1.1 %T (0.054 Abs)', 'metode' => 'IKM-ES-7.2.39'],
            ['kategori' => 'Kelistrikan & Fotometri', 'alat' => 'Lux Meter', 'rentang' => '50 lx ~ 1860 lx', 'cmc' => '2.3% ~ 6.9% of reading', 'metode' => 'SNSU PK.F-02:2021'],

            // 8. Akustik, Vibrasi & Waktu
            ['kategori' => 'Akustik, Vibrasi & Waktu', 'alat' => 'Sound Level Meter (SLM)', 'rentang' => '94 dB (63 Hz ~ 8 kHz)', 'cmc' => '0.52 dB', 'metode' => 'IKM-ES-7.2.32'],
            ['kategori' => 'Akustik, Vibrasi & Waktu', 'alat' => 'Vibration Meter', 'rentang' => '3 m/s² ~ 51 m/s² (10 Hz ~ 1000 Hz)', 'cmc' => '1.6 %', 'metode' => 'SNSU PK.A-02:2023'],
            ['kategori' => 'Akustik, Vibrasi & Waktu', 'alat' => 'Stopwatch / Timer', 'rentang' => '1 s ~ 3600 s', 'cmc' => '0.14 s', 'metode' => 'IKM-ES-7.2.20'],
            ['kategori' => 'Akustik, Vibrasi & Waktu', 'alat' => 'Centrifuge / RPM Meter', 'rentang' => '3 rpm ~ 15000 rpm', 'cmc' => '0.13 rpm ~ 1.2 rpm', 'metode' => 'HK.02.02.V.0412.2020'],
        ];
    }

    public function getLingkupPengujianList(): array
    {
        return [
            // 1. Pengujian Mekanik (BSC, LAF, Fume Hood)
            ['kategori' => 'Biosafety Cabinet (BSC)', 'parameter' => 'Uji Kecepatan Aliran Udara Inflow & Downflow (Airflow Velocity)', 'acuan' => 'NSF/ANSI 49:2022 & EN 12469:2000', 'metode' => 'Anemometer / Thermal Anemometer Grid Testing'],
            ['kategori' => 'Biosafety Cabinet (BSC)', 'parameter' => 'Uji Kebocoran & Integritas Filter HEPA/ULPA (Aerosol PAO/DOP)', 'acuan' => 'IEST-RP-CC002.4 & ISO 14644-3', 'metode' => 'Aerosol Generator & Photometer Leak Test'],
            ['kategori' => 'Biosafety Cabinet (BSC)', 'parameter' => 'Uji Visualisasi Pola Aliran Udara (Airflow Smoke Pattern Test)', 'acuan' => 'NSF/ANSI 49 (Class II Type A2/B2)', 'metode' => 'Smoke Generator / Fog Test (Containment Verification)'],
            ['kategori' => 'Biosafety Cabinet (BSC)', 'parameter' => 'Uji Jumlah Partikel Udara (Particle Count ISO Class 5)', 'acuan' => 'ISO 14644-1:2015 & US FED STD 209E', 'metode' => 'Airborne Optical Particle Counter (0.5 µm & 5.0 µm)'],
            ['kategori' => 'Biosafety Cabinet (BSC)', 'parameter' => 'Uji Intensitas Cahaya, Kebisingan & Vibrasi Ruang Kerja BSC', 'acuan' => 'NSF/ANSI 49 Standard Criteria', 'metode' => 'Lux Meter, SLM Class 1, & Vibration Sensor'],

            ['kategori' => 'Laminar Air Flow (LAF)', 'parameter' => 'Uji Kecepatan Aliran Udara Laminar (Airflow Velocity 0.36 - 0.54 m/s)', 'acuan' => 'IEST-RP-CC002.4 & ISO 14644-3', 'metode' => 'Multi-point Anemometer Grid Profiling'],
            ['kategori' => 'Laminar Air Flow (LAF)', 'parameter' => 'Uji Integritas Filter HEPA / ULPA Scan Test', 'acuan' => 'ISO 14644-3:2019 Section B.6', 'metode' => 'Aerosol Photometer PAO Challenge Test'],
            ['kategori' => 'Laminar Air Flow (LAF)', 'parameter' => 'Uji Kebersihan Partikel Ruang Kerja (ISO Class 5 / Class 100)', 'acuan' => 'ISO 14644-1:2015 Classification', 'metode' => 'Discrete Particle Counter Sampling'],
            ['kategori' => 'Laminar Air Flow (LAF)', 'parameter' => 'Uji Pola Aliran Udara Laminar (Airflow Smoke Visualization)', 'acuan' => 'ISO 14644-3 Section B.4', 'metode' => 'Non-contaminating Fog Generator'],

            ['kategori' => 'Fume Hood (Lemari Asam)', 'parameter' => 'Uji Kecepatan Muka Udara (Face Velocity Test: 0.40 - 0.60 m/s)', 'acuan' => 'ASHRAE 110-2016 & SEFA 1-2010', 'metode' => 'Grid Face Velocity Measurement (Open Sash Test)'],
            ['kategori' => 'Fume Hood (Lemari Asam)', 'parameter' => 'Uji Profiling & Keseragaman Kecepatan Aliran Muka (Face Velocity Profiling)', 'acuan' => 'ASHRAE 110-2016 Section 6', 'metode' => 'Anemometer Traverse Testing'],
            ['kategori' => 'Fume Hood (Lemari Asam)', 'parameter' => 'Uji Visualisasi Asap & Kontainmen Lokal/Total (Smoke Containment)', 'acuan' => 'ASHRAE 110-2016 Section 7', 'metode' => 'Local & Large Volume Smoke Generator Challenge'],
            ['kategori' => 'Fume Hood (Lemari Asam)', 'parameter' => 'Uji Gas Tracer SF6 (Tracer Gas Containment Performance)', 'acuan' => 'ASHRAE 110-2016 Section 8', 'metode' => 'SF6 Gas Injection & Quantitative Detector'],

            // 2. Udara Emisi Sumber Tidak Bergerak (Proses Akreditasi)
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Partikulat Isokinetik (Particulate)', 'acuan' => 'Permen LHK No. 11/2021 & US EPA Method 5', 'metode' => 'SNI 7117.13 s.d SNI 7117.17 (Isokinetic Sampling)'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Opasitas Gas Buang Cerobong (Smoke Opacity)', 'acuan' => 'Permen LHK No. 11/2021 & SNI 19-7117.11-2005', 'metode' => 'Skala Ringelmann / Transmissometer Opacity'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Sulfur Dioksida (SO₂)', 'acuan' => 'Permen LHK No. 11/2021 & Permen LH No. 07/2007', 'metode' => 'SNI 7117.18:2009 / Electrochemical Flue Gas Analyzer'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Nitrogen Dioksida (NO₂)', 'acuan' => 'Permen LHK No. 11/2021 & Permen LHK No. 15/2019', 'metode' => 'SNI 7117.18:2009 / Electrochemical Sensor'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Total Nitrogen Oksida (NOₓ / NO + NO₂)', 'acuan' => 'Permen LHK No. 11/2021 & Permen LHK No. 15/2019', 'metode' => 'SNI 7117.18:2009 / US EPA Method 7E'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Karbon Monoksida (CO)', 'acuan' => 'Permen LHK No. 11/2021 & Permen LH No. 07/2007', 'metode' => 'SNI 7117.18:2009 / NDIR / Electrochemical Sensor'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Karbon Dioksida (CO₂)', 'acuan' => 'Permen LHK No. 11/2021 & SNI 7117.15:2009', 'metode' => 'NDIR Gas Analyzer / Orsat Method'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Oksigen (O₂) Gas Buang Cerobong', 'acuan' => 'Permen LHK No. 11/2021 & SNI 7117.15:2009', 'metode' => 'Electrochemical / Paramagnetic Oxygen Sensor'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Suhu Gas Buang Cerobong (Flue Gas Temperature)', 'acuan' => 'SNI 7117.14:2009 & US EPA Method 2', 'metode' => 'Thermocouple Type K Pyrometer Digital'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Tekanan Statis, Dinamis & Kecepatan Alir Gas Buang (Velocity & Debit)', 'acuan' => 'SNI 7117.14:2009 & US EPA Method 2', 'metode' => 'Pitot Tube Tipe S & Digital Differential Manometer'],
            ['kategori' => 'Udara Emisi Cerobong', 'parameter' => 'Kadar Air Gas Buang (Moisture Content) & Efisiensi Pembakaran', 'acuan' => 'SNI 7117.16:2009 & US EPA Method 4', 'metode' => 'Kondensasi / Gravimetri & Combustion Efficiency Calculation'],
        ];
    }
}



