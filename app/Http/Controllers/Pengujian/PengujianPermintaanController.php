<?php

namespace App\Http\Controllers\Pengujian;

use App\Http\Controllers\Controller;
use App\Models\PengujianOrder;
use App\Models\PengujianItem;
use App\Models\CocEmisi;
use App\Models\Sample;
use App\Models\ClientAccount;
use App\Models\MasterEmisi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PengujianPermintaanController extends Controller
{
    public function index(Request $request)
    {
        $query = PengujianOrder::with(['items', 'coc'])->latest();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('no_order', 'like', "%{$s}%")
                  ->orWhere('no_quotation', 'like', "%{$s}%")
                  ->orWhere('no_po', 'like', "%{$s}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);
        $customers = ClientAccount::select('company_name', 'username')->where('is_active', true)->get();

        // Autocomplete companies from previous orders and COC
        $companies = CocEmisi::select('company_name', 'alamat_perusahaan', 'contact_person', 'no_telepon', 'email_coa')
            ->whereNotNull('company_name')
            ->groupBy('company_name', 'alamat_perusahaan', 'contact_person', 'no_telepon', 'email_coa')
            ->latest('id')
            ->limit(50)
            ->get();

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

        $newQuotationNo = PengujianOrder::generateNoQuotation();
        $newOrderNo = PengujianOrder::generateNoOrder();

        return view('pengujian.permintaan.index', compact(
            'orders', 'customers', 'companies', 'regulations', 'masterParameters',
            'paramMethodsMap', 'newQuotationNo', 'newOrderNo'
        ));
    }

    public function store(Request $request)
    {
        // Sanitize thousand-separator formatted numbers before validation
        $rawSampling = preg_replace('/[^0-9]/', '', (string)$request->input('biaya_sampling', 0));
        $rawMop = preg_replace('/[^0-9]/', '', (string)$request->input('biaya_mop_demop', 0));
        $rawDiskon = preg_replace('/[^0-9]/', '', (string)$request->input('diskon', 0));

        $sanitizedItems = $request->input('items', []);
        if (is_array($sanitizedItems)) {
            foreach ($sanitizedItems as $k => $it) {
                if (isset($it['harga_satuan'])) {
                    $sanitizedItems[$k]['harga_satuan'] = floatval(preg_replace('/[^0-9]/', '', (string)$it['harga_satuan']));
                }
            }
        }

        $request->merge([
            'biaya_sampling'  => floatval($rawSampling ?: 0),
            'biaya_mop_demop' => floatval($rawMop ?: 0),
            'diskon'          => floatval($rawDiskon ?: 0),
            'items'           => $sanitizedItems,
        ]);

        $request->validate([
            'no_quotation'     => 'required|string',
            'nama_pelanggan'   => 'required|string',
            'alamat_pelanggan' => 'nullable|string',
            'kontak_person'    => 'nullable|string',
            'no_hp'            => 'nullable|string',
            'email'            => 'nullable|string',
            'tanggal_masuk'    => 'required|date',
            'tat_days'         => 'nullable|integer',
            'target_selesai'   => 'nullable|date',
            'top_days'         => 'nullable|integer',
            'tipe_pekerjaan'   => 'nullable|string',
            'jenis_usaha'      => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.nama_titik_uji' => 'required|string',
            'items.*.harga_satuan'   => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Pengujian Order (Quotation)
            $isPpn = $request->has('is_ppn') && ($request->input('is_ppn') == '1' || $request->input('is_ppn') === 'true' || $request->input('is_ppn') === 'on');
            $biayaSampling = floatval($request->input('biaya_sampling', 0));
            $biayaMopDemop = floatval($request->input('biaya_mop_demop', 0));
            $diskon = floatval($request->input('diskon', 0));

            $order = PengujianOrder::create([
                'no_order'          => PengujianOrder::generateNoOrder(),
                'no_quotation'      => $request->no_quotation,
                'nama_pelanggan'    => $request->nama_pelanggan,
                'alamat_pelanggan'  => $request->alamat_pelanggan,
                'kontak_person'     => $request->kontak_person,
                'no_hp'             => $request->no_hp,
                'email'             => $request->email,
                'tipe_pekerjaan'    => $request->tipe_pekerjaan ?? 'on_site',
                'jenis_usaha'       => $request->jenis_usaha,
                'status'            => 'Quotation',
                'status_verifikasi' => 'Terkirim',
                'tanggal_masuk'     => $request->tanggal_masuk ?? date('Y-m-d'),
                'target_selesai'    => $request->target_selesai,
                'top_days'          => $request->top_days ?? 30,
                'tat_days'          => $request->tat_days ?? 14,
                'catatan'           => $request->catatan,
                'is_ppn'            => $isPpn,
                'ppn_persen'        => $isPpn ? 11.00 : 0.00,
                'biaya_sampling'    => $biayaSampling,
                'biaya_mop_demop'   => $biayaMopDemop,
                'diskon'            => $diskon,
            ]);

            // 2. Automatically generate COC Emisi Document from this Quotation
            $coc = CocEmisi::create([
                'nomor_coc'         => CocEmisi::generateNomorCoc(),
                'nomor_qt'          => $order->no_quotation,
                'company_name'      => $order->nama_pelanggan,
                'alamat_perusahaan' => $order->alamat_pelanggan,
                'company_address'   => $order->alamat_pelanggan,
                'contact_person'    => $order->kontak_person,
                'no_telepon'        => $order->no_hp,
                'phone_no'          => $order->no_hp,
                'email_coa'         => $order->email,
                'sampling_date'     => $order->tanggal_masuk ?? date('Y-m-d'),
                'tgl_selesai'       => $order->target_selesai,
                'tat_days'          => $order->tat_days ?? 14,
                'sampling_officer'  => null,
                'jenis_usaha'       => $order->jenis_usaha,
                'status'            => 'Draft',
                'status_sampling'   => 'Pending',
                'created_by'        => auth()->id(),
            ]);

            $seq = substr($coc->nomor_coc, -4);

            // 3. Process each Titik / Point
            foreach ($request->items as $idx => $it) {
                $sampleId = $it['sample_id'] ?? "{$seq}." . ($idx + 1);
                $harga = floatval($it['harga_satuan']);
                $qty = intval($it['jumlah_titik'] ?? 1);
                $subtotal = $harga * $qty;

                // Process parameters & methods
                $rawParams = $it['parameters'] ?? [];
                $methodsMap = $it['methods'] ?? [];
                $processedParams = [];

                foreach ($rawParams as $pKey => $pVal) {
                    if (is_array($pVal)) {
                        $pName = $pVal['parameter'] ?? $pKey;
                        $pMet = $pVal['metode'] ?? ($methodsMap[$pName] ?? null);
                        $pSat = $pVal['satuan'] ?? 'mg/Nm3';
                    } else {
                        $pName = $pVal;
                        $pMet = $methodsMap[$pName] ?? null;
                        $pSat = 'mg/Nm3';
                    }
                    $processedParams[] = [
                        'parameter' => $pName,
                        'metode'    => $pMet,
                        'satuan'    => $pSat,
                    ];
                }

                // Save to PengujianItem
                PengujianItem::create([
                    'pengujian_order_id' => $order->id,
                    'sample_id'          => $sampleId,
                    'nama_titik_uji'     => $it['nama_titik_uji'],
                    'regulasi'           => $it['regulasi'] ?? null,
                    'kategori_uji'       => 'Emisi Sumber Tidak Bergerak',
                    'parameter_uji_json' => $processedParams,
                    'harga_satuan'       => $harga,
                    'jumlah_titik'       => $qty,
                    'subtotal'           => $subtotal,
                    'coc_id'             => $coc->id,
                ]);

                // Auto-create Sample in COC so COC is 100% complete and ready to print!
                Sample::create([
                    'coc_id'        => $coc->id,
                    'sample_id'     => $sampleId,
                    'nama_cerobong' => $it['nama_titik_uji'],
                    'description'   => $it['nama_titik_uji'],
                    'regulations'   => isset($it['regulasi']) && $it['regulasi'] ? [$it['regulasi']] : [],
                    'parameters'    => $processedParams,
                    'status'        => 'Draft',
                    'status_lab'    => 'Pending',
                    'is_verified'   => false,
                ]);
            }

            // 4. Auto-create Client Account if not exists
            if (!ClientAccount::where('company_name', $order->nama_pelanggan)->exists()) {
                ClientAccount::create([
                    'company_name'   => $order->nama_pelanggan,
                    'username'       => ClientAccount::generateUsername($order->nama_pelanggan),
                    'password'       => ClientAccount::generatePassword(),
                    'default_module' => 'pengujian',
                    'is_active'      => true,
                ]);
            }

            DB::commit();
            return redirect()->route('pengujian.permintaan.index')->with('success', "Quotation {$order->no_quotation} berhasil diterbitkan dan Dokumen COC {$coc->nomor_coc} telah siap cetak!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan Quotation: ' . $e->getMessage());
        }
    }

    public function updatePo(Request $request, $id)
    {
        $request->validate([
            'no_po'   => 'required|string',
            'file_po' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $order = PengujianOrder::findOrFail($id);

        $filePath = $order->file_po;
        if ($request->hasFile('file_po')) {
            $file = $request->file('file_po');
            $fileName = 'PO_ENV_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/po_pengujian'), $fileName);
            $filePath = 'uploads/po_pengujian/' . $fileName;
        }

        $order->update([
            'no_po'             => $request->no_po,
            'file_po'           => $filePath,
            'status'            => 'SPK_Terbit',
            'status_verifikasi' => 'Disetujui',
        ]);

        return redirect()->route('pengujian.permintaan.index')->with('success', 'PO Pelanggan berhasil diupdate: ' . $order->no_po);
    }

    public function printQuotation($id)
    {
        $order = PengujianOrder::with(['items', 'coc'])->findOrFail($id);
        return view('pengujian.permintaan.print_quotation', compact('order'));
    }

    public function destroy($id)
    {
        $order = PengujianOrder::findOrFail($id);
        // Delete related items and order
        $order->items()->delete();
        $order->delete();
        return redirect()->route('pengujian.permintaan.index')->with('success', 'Data Quotation berhasil dihapus.');
    }
}
