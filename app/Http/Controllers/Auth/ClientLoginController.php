<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClientAccount;
use Illuminate\Http\Request;

class ClientLoginController extends Controller
{
    public function showLogin()
    {
        if (session('client_logged_in')) {
            $module = session('client_default_module', 'kalibrasi');
            if ($module === 'pengujian') {
                return redirect('/pengujian/klien/portal');
            }
            return redirect('/kalibrasi/klien/portal');
        }
        return response()
            ->view('auth.login-klien')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $account = ClientAccount::where('username', $request->username)->first();

        if (!$account) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.'])->withInput($request->only('username'));
        }

        $result = $account->checkLogin($request->password);

        if (!$result['success']) {
            return back()->withErrors(['password' => $result['message'] ?? 'Password salah.'])->withInput($request->only('username'));
        }

        session([
            'client_logged_in' => true,
            'client_company' => $result['company_name'],
            'client_username' => $result['username'],
            'client_default_module' => $result['default_module'] ?? 'kalibrasi',
        ]);

        if ($result['password_reset_trigger']) {
            session()->flash('new_password', $result['new_password']);
            session()->flash('password_changed', true);
        }

        if ($request->is('*pengujian*') || ($result['default_module'] ?? '') === 'pengujian') {
            return redirect('/pengujian/klien/portal');
        }

        return redirect('/kalibrasi/klien/portal');
    }

    public function logout(Request $request)
    {
        session()->forget(['client_logged_in', 'client_company', 'client_username', 'client_default_module']);
        if ($request->is('*pengujian*')) {
            return redirect('/pengujian/klien/login');
        }
        return redirect('/kalibrasi/klien/login');
    }
}
