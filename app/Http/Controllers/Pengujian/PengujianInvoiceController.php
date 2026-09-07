<?php

namespace App\Http\Controllers\Pengujian;

use App\Http\Controllers\Controller;
use App\Models\PengujianOrder;
use App\Helpers\TerbilangHelper;
use Illuminate\Http\Request;

class PengujianInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = PengujianOrder::with('items')->whereNotNull('no_po')->where('no_po', '!=', '')->latest();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('no_order', 'like', "%{$s}%")
                  ->orWhere('no_quotation', 'like', "%{$s}%")
                  ->orWhere('no_po', 'like', "%{$s}%")
                  ->orWhere('no_invoice', 'like', "%{$s}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'belum_invoice') {
                $query->whereNull('no_invoice')->orWhere('no_invoice', '');
            } elseif ($request->status === 'terbit_invoice') {
                $query->whereNotNull('no_invoice')->where('no_invoice', '!=', '');
            }
        }

        $orders = $query->paginate(15);

        return view('pengujian.invoice.index', compact('orders'));
    }

    public function terbitInvoice(Request $request, $id)
    {
        $order = PengujianOrder::findOrFail($id);

        if (empty($order->no_invoice)) {
            $order->no_invoice = PengujianOrder::generateNoInvoice();
        }
        if (empty($order->no_kwitansi)) {
            $order->no_kwitansi = str_replace('INV-', 'KWT-', $order->no_invoice);
        }
        if (empty($order->no_bast)) {
            $order->no_bast = str_replace('INV-', 'BAST-', $order->no_invoice);
        }
        if (empty($order->no_tst)) {
            $order->no_tst = str_replace('INV-', 'TST-', $order->no_invoice);
        }

        $order->is_verified_finance = true;
        $order->verified_finance_at = now();
        $order->save();

        return redirect()->route('pengujian.invoice.index')->with('success', 'Invoice Pengujian berhasil diterbitkan: ' . $order->no_invoice);
    }

    public function batalInvoice(Request $request, $id)
    {
        $order = PengujianOrder::findOrFail($id);
        $oldInv = $order->no_invoice;

        $order->no_invoice = null;
        $order->no_kwitansi = null;
        $order->no_bast = null;
        $order->no_tst = null;
        $order->is_verified_finance = false;
        $order->verified_finance_at = null;
        $order->save();

        return redirect()->route('pengujian.invoice.index')->with('success', 'Invoice ' . ($oldInv ?? '') . ' berhasil dibatalkan dan dikembalikan ke status draft.');
    }

    public function printInvoice($id)
    {
        $order = PengujianOrder::with('items')->findOrFail($id);
        $terbilang = TerbilangHelper::convert($order->grand_total) . ' RUPIAH';
        return view('pengujian.invoice.print_invoice', compact('order', 'terbilang'));
    }

    public function printKwitansi($id)
    {
        $order = PengujianOrder::with('items')->findOrFail($id);
        $terbilang = TerbilangHelper::convert($order->grand_total) . ' RUPIAH';
        return view('pengujian.invoice.print_kwitansi', compact('order', 'terbilang'));
    }

    public function printBast($id)
    {
        $order = PengujianOrder::with('items')->findOrFail($id);
        return view('pengujian.invoice.print_bast', compact('order'));
    }

    public function printTst($id)
    {
        $order = PengujianOrder::with('items')->findOrFail($id);
        return view('pengujian.invoice.print_tst', compact('order'));
    }

    public function printAll($id)
    {
        $order = PengujianOrder::with('items')->findOrFail($id);
        $terbilang = TerbilangHelper::convert($order->grand_total) . ' RUPIAH';
        return view('pengujian.invoice.print_all', compact('order', 'terbilang'));
    }
}
