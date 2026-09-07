<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SharedSsoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SsoController extends Controller
{
    public function redirectToHris(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $token = SharedSsoService::generateToken($user);
        $ssoCookie = SharedSsoService::createSsoCookie($user);

        $host = $request->getSchemeAndHttpHost();
        $hrisUrl = $host . '/eslabhris/sso-login?token=' . urlencode($token);
        return redirect()->away($hrisUrl)->withCookie($ssoCookie);
    }

    public function handleSsoLogin(Request $request)
    {
        $token = $request->query('token');
        $user = null;

        if ($token) {
            $user = SharedSsoService::validateToken($token);
        }

        if (!$user) {
            $user = SharedSsoService::checkAndAutoLogin($request);
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'Sesi login tidak ditemukan atau telah kedaluwarsa.');
        }

        Auth::login($user, true);
        $request->session()->regenerate();
        SharedSsoService::syncKalibrasiSession($user);

        $ssoCookie = SharedSsoService::createSsoCookie($user);

        $target = $request->query('target', 'dashboard');
        if ($target === 'admin-master' || $target === 'admin_master') {
            return redirect('/pengujian/admin-master/dashboard')
                ->withCookie($ssoCookie)
                ->withCookie(cookie('eslab_sso_logged_out', '', -2628000, '/', null, false, false, false, 'Lax'))
                ->with('success', 'Selamat datang di Master Admin Hub, ' . $user->name);
        }
        if ($target === 'kalibrasi') {
            return redirect()->route('kalibrasi.dashboard')
                ->withCookie($ssoCookie)
                ->withCookie(cookie('eslab_sso_logged_out', '', -2628000, '/', null, false, false, false, 'Lax'))
                ->with('success', 'Selamat datang di LIMS Kalibrasi, ' . $user->name);
        }
        return redirect()->route('pengujian.dashboard')
            ->withCookie($ssoCookie)
            ->withCookie(cookie('eslab_sso_logged_out', '', -2628000, '/', null, false, false, false, 'Lax'))
            ->with('success', 'Selamat datang di LIMS Pengujian, ' . $user->name);
    }
}
