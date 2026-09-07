<?php

namespace App\Http\Controllers;

use App\Models\ClientAccount;
use App\Models\KalibrasiCustomer;
use App\Models\KalibrasiOrder;
use App\Models\PengujianOrder;
use App\Models\CocEmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolaKlienController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'all');

        $query = ClientAccount::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($filter === 'pengujian') {
            $query->where('default_module', 'pengujian');
        } elseif ($filter === 'kalibrasi') {
            $query->where('default_module', 'kalibrasi');
        } elseif ($filter === 'both') {
            $query->where('default_module', 'both');
        }

        $klien = $query->orderBy('company_name', 'asc')->paginate(20)->withQueryString();

        // Count stats
        $totalClients = ClientAccount::count();
        $totalPengujian = ClientAccount::where('default_module', 'pengujian')->count();
        $totalKalibrasi = ClientAccount::where('default_module', 'kalibrasi')->count();
        $totalBoth = ClientAccount::where('default_module', 'both')->count();
        $totalActive = ClientAccount::where('is_active', true)->count();

        // Customer company names suggestions for autocomplete dropdown
        $existingCustomers = KalibrasiCustomer::pluck('nama_pelanggan')
            ->merge(CocEmisi::pluck('company_name'))
            ->merge(PengujianOrder::pluck('nama_pelanggan'))
            ->unique()
            ->filter()
            ->values();

        // Attach order counts for each displayed client
        $klien->getCollection()->transform(function ($item) {
            $company = $item->company_name;
            $item->total_order_kalibrasi = KalibrasiOrder::where('nama_pelanggan', 'like', "%{$company}%")->count();
            $item->total_order_pengujian = PengujianOrder::where('nama_pelanggan', 'like', "%{$company}%")->count() 
                + CocEmisi::where('company_name', 'like', "%{$company}%")->count();
            return $item;
        });

        return view('kelola-klien.index', compact(
            'klien', 'search', 'filter', 'existingCustomers',
            'totalClients', 'totalPengujian', 'totalKalibrasi', 'totalBoth', 'totalActive'
        ));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'company_name'   => 'required|string|max:255',
                'username'       => 'required|string|max:100|unique:client_accounts,username',
                'password'       => 'required|string|min:4',
                'default_module' => 'required|in:pengujian,kalibrasi,both',
            ]);

            // Check if company name already has an account
            $existing = ClientAccount::whereRaw('LOWER(TRIM(company_name)) = ?', [strtolower(trim($request->company_name))])->first();
            if ($existing) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun customer untuk perusahaan "' . $existing->company_name . '" sudah ada dengan username: ' . $existing->username,
                    ], 422);
                }
                return redirect()->back()->with('error', 'Akun customer untuk perusahaan "' . $existing->company_name . '" sudah terdaftar.');
            }

            $account = ClientAccount::create([
                'company_name'   => trim($request->company_name),
                'username'       => strtolower(trim($request->username)),
                'password'       => $request->password,
                'default_module' => $request->default_module ?? 'both',
                'is_active'      => true,
                'last_reset'     => now(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun customer "' . $account->company_name . '" berhasil dibuat!',
                    'data'    => $account,
                ]);
            }

            return redirect()->back()->with('success', 'Akun customer "' . $account->company_name . '" berhasil dibuat!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => collect($e->errors())->flatten()->first() ?? 'Validasi gagal.',
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat akun: ' . $e->getMessage(),
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $klien = ClientAccount::findOrFail($id);
            $request->validate([
                'company_name'   => 'required|string|max:255',
                'username'       => 'required|string|max:100|unique:client_accounts,username,' . $klien->id,
                'default_module' => 'required|in:pengujian,kalibrasi,both',
            ]);

            $data = [
                'company_name'   => trim($request->company_name),
                'username'       => strtolower(trim($request->username)),
                'default_module' => $request->default_module,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
                $data['last_reset'] = now();
            }

            $klien->update($data);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Akun customer berhasil diperbarui.']);
            }

            return redirect()->back()->with('success', 'Akun customer "' . $klien->company_name . '" berhasil diperbarui.');
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui akun: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal memperbarui akun: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $klien = ClientAccount::findOrFail($id);
            $klien->update(['is_active' => !$klien->is_active]);
            $statusText = $klien->is_active ? 'diaktifkan' : 'dinonaktifkan';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Akun customer \"{$klien->company_name}\" berhasil {$statusText}.",
                    'is_active' => $klien->is_active,
                ]);
            }

            return redirect()->back()->with('success', "Akun customer \"{$klien->company_name}\" berhasil {$statusText}.");
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request, $id)
    {
        try {
            $klien = ClientAccount::findOrFail($id);
            $newPwd = ClientAccount::generatePassword();
            $klien->update(['password' => $newPwd, 'last_reset' => now()]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil di-reset!',
                    'new_password' => $newPwd,
                ]);
            }

            return redirect()->back()->with('success', "Password akun \"{$klien->company_name}\" berhasil di-reset ke: {$newPwd}");
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal mereset password: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $klien = ClientAccount::findOrFail($id);
            $company = $klien->company_name;
            $klien->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Akun customer \"{$company}\" berhasil dihapus.",
                ]);
            }

            return redirect()->back()->with('success', "Akun customer \"{$company}\" berhasil dihapus.");
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus akun: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}
