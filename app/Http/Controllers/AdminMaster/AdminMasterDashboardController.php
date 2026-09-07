<?php

namespace App\Http\Controllers\AdminMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminMasterDashboardController extends Controller
{
    private function getDashboardRedirectUrl(Request $request): string
    {
        $referer = $request->header('referer');
        if ($referer && !str_contains($referer, '/users/')) {
            return $referer;
        }
        if (str_contains($request->path(), 'pengujian') || str_contains($request->path(), 'admin_master')) {
            return '/pengujian/admin-master/dashboard';
        }
        return '/admin-master/dashboard';
    }

    public function index(Request $request)
    {
        $users = User::with('profile')->latest()->get();
        $clients = Schema::hasTable('client_accounts') ? DB::table('client_accounts')->latest()->get() : collect([]);
        
        $totalUsers = User::count();
        $totalClients = Schema::hasTable('client_accounts') ? DB::table('client_accounts')->count() : 0;
        $activeSessions = Schema::hasTable('sessions') ? DB::table('sessions')->count() : 0;
        $blockedIps = Schema::hasTable('kalibrasi_blocked_ips') ? DB::table('kalibrasi_blocked_ips')->count() : 0;

        $securityLogs = Schema::hasTable('kalibrasi_activity_logs') 
            ? DB::table('kalibrasi_activity_logs')->latest()->take(10)->get() 
            : (Schema::hasTable('audit_logs') ? DB::table('audit_logs')->latest()->take(10)->get() : collect([]));

        return view('admin-master.dashboard', compact(
            'users', 'clients', 'totalUsers', 'totalClients', 
            'activeSessions', 'blockedIps', 'securityLogs'
        ));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $allowedMenus = $request->input('allowed_menus', []);
        $allowedMenusJson = !empty($allowedMenus) ? json_encode($allowedMenus) : null;
        $isActive = $request->has('is_active');
        $canViewHarga = $request->has('can_view_harga');
        $roleCode = $request->input('role_code') ?: ($request->input('role') ?: 'staff');

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'visible_pass' => $request->password,
            'role' => $request->role ?? 'admin_ts',
            'role_code' => $roleCode,
            'can_access_kalibrasi' => $request->has('can_access_kalibrasi'),
            'can_access_pengujian' => $request->has('can_access_pengujian'),
            'can_access_hris' => $request->has('can_access_hris'),
            'can_view_harga' => $canViewHarga,
            'allowed_menus_json' => $allowedMenusJson,
            'is_active' => $isActive,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'full_name' => $request->name,
            'username' => $request->username,
            'role' => $request->role ?? 'admin_ts',
            'role_code' => $roleCode,
            'can_access_kalibrasi' => $request->has('can_access_kalibrasi'),
            'can_access_pengujian' => $request->has('can_access_pengujian'),
            'can_access_hris' => $request->has('can_access_hris'),
            'can_view_harga' => $canViewHarga,
            'allowed_menus_json' => $allowedMenusJson,
            'plain_password' => $request->password,
            'is_active' => $isActive,
            'status_karyawan' => $isActive ? 'aktif' : 'non-aktif',
        ]);

        // Cross-DB Sync to HRIS
        try {
            $hrisDb = env('HRIS_DB_DATABASE', 'eslab_hris');
            DB::statement("INSERT INTO {$hrisDb}.users (employee_code, name, email, password, visible_pass, role, can_access_pengujian, can_access_kalibrasi, can_access_hris, allowed_menus_json, can_view_harga, is_active, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    name = VALUES(name),
                    can_access_pengujian = VALUES(can_access_pengujian),
                    can_access_kalibrasi = VALUES(can_access_kalibrasi),
                    can_access_hris = VALUES(can_access_hris),
                    allowed_menus_json = VALUES(allowed_menus_json),
                    can_view_harga = VALUES(can_view_harga),
                    is_active = VALUES(is_active)", [
                $request->username,
                $request->name,
                $request->email,
                Hash::make($request->password),
                $request->password,
                'employee',
                $request->has('can_access_pengujian') ? 1 : 0,
                $request->has('can_access_kalibrasi') ? 1 : 0,
                $request->has('can_access_hris') ? 1 : 0,
                $allowedMenusJson,
                $canViewHarga ? 1 : 0,
                $isActive ? 1 : 0,
            ]);
        } catch (\Throwable $e) {}

        return redirect($this->getDashboardRedirectUrl($request))->with('success', 'Pengguna ' . $request->name . ' berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $allowedMenus = $request->input('allowed_menus', []);
        $allowedMenusJson = !empty($allowedMenus) ? json_encode($allowedMenus) : null;
        $isActive = $request->has('is_active');
        $canViewHarga = $request->has('can_view_harga');

        $name = $request->input('name') ?: $user->name;
        $roleCode = $request->input('role_code') ?: ($user->role_code ?: ($user->profile?->role_code ?: ($user->role ?: 'staff')));

        $data = [
            'name' => $name,
            'role_code' => $roleCode,
            'can_access_kalibrasi' => $request->has('can_access_kalibrasi'),
            'can_access_pengujian' => $request->has('can_access_pengujian'),
            'can_access_hris' => $request->has('can_access_hris'),
            'can_view_harga' => $canViewHarga,
            'allowed_menus_json' => $allowedMenusJson,
            'is_active' => $isActive,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $data['visible_pass'] = $request->password;
        }

        $user->update($data);

        if ($user->profile) {
            $profileData = [
                'full_name' => $name,
                'role_code' => $roleCode,
                'can_access_kalibrasi' => $request->has('can_access_kalibrasi'),
                'can_access_pengujian' => $request->has('can_access_pengujian'),
                'can_access_hris' => $request->has('can_access_hris'),
                'can_view_harga' => $canViewHarga,
                'allowed_menus_json' => $allowedMenusJson,
                'is_active' => $isActive,
                'status_karyawan' => $isActive ? 'aktif' : 'non-aktif',
            ];
            if ($request->filled('password')) {
                $profileData['plain_password'] = $request->password;
            }
            $user->profile->update($profileData);
        }

        // Cross-DB Realtime Sync to HRIS Database (eslab_hris.users)
        $possibleHrisDbs = array_unique([
            env('HRIS_DB_DATABASE', 'eslab_hris_db'),
            'eslab_hris_db',
            'eslab_hris',
            'eslabhris',
            'hris_eslab',
            'hris'
        ]);

        foreach ($possibleHrisDbs as $hDb) {
            try {
                DB::statement("UPDATE `{$hDb}`.users SET 
                    can_access_pengujian = ?, 
                    can_access_kalibrasi = ?, 
                    can_access_hris = ?, 
                    allowed_menus_json = ?, 
                    can_view_harga = ?, 
                    is_active = ? 
                    WHERE email = ? OR employee_code = ? OR name = ?", [
                        $request->has('can_access_pengujian') ? 1 : 0,
                        $request->has('can_access_kalibrasi') ? 1 : 0,
                        $request->has('can_access_hris') ? 1 : 0,
                        $allowedMenusJson,
                        $canViewHarga ? 1 : 0,
                        $isActive ? 1 : 0,
                        $user->email,
                        $user->username ?? $user->email,
                        $user->name
                    ]);

                if ($request->filled('password')) {
                    DB::statement("UPDATE `{$hDb}`.users SET password = ?, visible_pass = ? WHERE email = ? OR employee_code = ? OR name = ?", [
                        Hash::make($request->password),
                        $request->password,
                        $user->email,
                        $user->username ?? $user->email,
                        $user->name
                    ]);
                }
            } catch (\Throwable $e) {}
        }

        return redirect($this->getDashboardRedirectUrl($request))->with('success', 'Data akun & hak akses ' . $user->name . ' berhasil diperbarui.');
    }

    public function toggleUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->username === 'admin_.master') {
            return redirect($this->getDashboardRedirectUrl($request))->with('error', 'Status Akun Master Admin utama tidak dapat dinonaktifkan!');
        }

        $currentStatus = (bool)($user->is_active ?? $user->profile?->is_active ?? true);
        $newStatus = !$currentStatus;

        $user->update(['is_active' => $newStatus]);
        if ($user->profile) {
            $user->profile->update(['is_active' => $newStatus]);
        }

        // Cross-DB Sync status to HRIS
        $possibleHrisDbs = array_unique([
            env('HRIS_DB_DATABASE', 'eslab_hris_db'),
            'eslab_hris_db',
            'eslab_hris',
            'eslabhris',
            'hris_eslab',
            'hris'
        ]);

        foreach ($possibleHrisDbs as $hDb) {
            try {
                DB::statement("UPDATE `{$hDb}`.users SET is_active = ? WHERE email = ? OR employee_code = ? OR name = ?", [
                    $newStatus ? 1 : 0,
                    $user->email,
                    $user->username ?? $user->email,
                    $user->name
                ]);
            } catch (\Throwable $e) {}
        }

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        return redirect($this->getDashboardRedirectUrl($request))->with('success', 'Akun ' . $user->name . ' berhasil ' . $statusText . '.');
    }

    public function resetUserPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $newPassword = $request->input('new_password');
        if (!$newPassword) {
            $newPassword = Str::random(8) . rand(10, 99);
        }

        $user->update(['password' => Hash::make($newPassword), 'visible_pass' => $newPassword]);
        if ($user->profile) {
            $user->profile->update([
                'plain_password' => $newPassword,
                'last_password_reset' => now()
            ]);
        }

        // Cross-DB Sync password to HRIS (checks all possible db aliases)
        $possibleHrisDbs = array_unique([
            env('HRIS_DB_DATABASE', 'eslab_hris_db'),
            'eslab_hris_db',
            'eslab_hris',
            'eslabhris',
            'hris_eslab',
            'hris'
        ]);

        foreach ($possibleHrisDbs as $hDb) {
            try {
                DB::statement("UPDATE `{$hDb}`.users SET password = ?, visible_pass = ? WHERE email = ? OR employee_code = ? OR name = ?", [
                    Hash::make($newPassword),
                    $newPassword,
                    $user->email,
                    $user->username ?? $user->email,
                    $user->name
                ]);
            } catch (\Throwable $e) {}
        }

        return redirect($this->getDashboardRedirectUrl($request))->with('success', 'Kata sandi user ' . $user->name . ' berhasil direset menjadi: ' . $newPassword);
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->username === 'admin_.master' || $user->email === 'admin_.master@eslab.com') {
            return redirect($this->getDashboardRedirectUrl($request))->with('error', 'Akun Master Admin utama tidak dapat dihapus!');
        }

        if ($user->profile) {
            $user->profile->delete();
        }
        $user->delete();

        // Cross-DB delete from HRIS
        try {
            $hrisDb = env('HRIS_DB_DATABASE', 'eslab_hris');
            DB::statement("DELETE FROM {$hrisDb}.users WHERE email = ? OR employee_code = ?", [$user->email, $user->username ?? $user->email]);
        } catch (\Throwable $e) {}

        return redirect($this->getDashboardRedirectUrl($request))->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
