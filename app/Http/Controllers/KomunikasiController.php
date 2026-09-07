<?php

namespace App\Http\Controllers;

use App\Models\ClientMessage;
use Illuminate\Http\Request;

class KomunikasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'all');
        $query = ClientMessage::query();
        if ($search) {
            $query->where('company_name', 'like', "%$search%")->orWhere('subject', 'like', "%$search%");
        }
        if ($filter === 'belum') $query->where('status', 'Belum Dibalas');
        if ($filter === 'sudah') $query->where('status', 'Sudah Dibalas');
        $messages = $query->latest()->paginate(20)->withQueryString();
        return view('komunikasi.index', compact('messages', 'search', 'filter'));
    }

    public function reply(Request $request, ClientMessage $message)
    {
        $request->validate(['reply' => 'required|string']);
        $message->update([
            'reply'      => $request->reply,
            'replied_by' => auth()->user()->profile?->full_name ?? auth()->user()->name,
            'replied_at' => now(),
            'status'     => 'Sudah Dibalas',
        ]);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Pesan berhasil dibalas.']);
        }
        return redirect()->back()->with('success', 'Balasan berhasil dikirimkan ke pelanggan.');
    }

    public function destroy(Request $request, ClientMessage $message)
    {
        $message->delete();
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Pesan komunikasi berhasil dihapus.');
    }

    // Client-facing API
    public function fetchForClient(Request $request)
    {
        $company = session('client_company');
        $messages = ClientMessage::where('company_name', $company)->latest()->get();
        return response()->json($messages);
    }

    public function sendFromClient(Request $request)
    {
        $request->validate([
            'sender_name' => 'required|string',
            'subject'     => 'required|string',
            'message'     => 'required|string',
        ]);
        $msg = ClientMessage::create([
            'company_name' => session('client_company'),
            'sender_name'  => $request->sender_name,
            'subject'      => $request->subject,
            'message'      => $request->message,
        ]);
        return response()->json(['success' => true, 'id' => $msg->id]);
    }
}
