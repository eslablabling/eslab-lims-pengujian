<?php

namespace App\Http\Middleware;

use App\Services\SharedSsoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LablingAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $ssoToken = $request->cookie(SharedSsoService::COOKIE_NAME) ?? ($_COOKIE[SharedSsoService::COOKIE_NAME] ?? null);
        $explicitLogout = $request->cookie('eslab_sso_logged_out') ?? ($_COOKIE['eslab_sso_logged_out'] ?? null);

        // If a valid SSO token exists, any lingering logout flag is obsolete and overridden
        $validSsoUser = !empty($ssoToken) ? SharedSsoService::validateToken($ssoToken) : null;
        if ($validSsoUser && $explicitLogout) {
            $explicitLogout = null;
        }

        // 1. Explicit cross-module logout detected
        if ($explicitLogout) {
            if (Auth::check()) {
                Auth::logout();
                Session::forget('kalibrasi_user');
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            $target = $request->is('*kalibrasi*') ? route('kalibrasi.login') : url('/pengujian/login');
            $response = redirect($target)->with('info', 'Sesi login telah berakhir.');
            $response->withCookie(cookie('eslab_sso_logged_out', '', -2628000, '/', null, false, false, false, 'Lax'));
            foreach (SharedSsoService::clearAllSessionCookies() as $c) {
                $response->withCookie($c);
            }
            return $response;
        }

        // 2. If authenticated in Pengujian:
        if (Auth::check()) {
            if (!empty($ssoToken)) {
                $ssoUser = SharedSsoService::validateToken($ssoToken);
                if ($ssoUser && $ssoUser->id !== Auth::id()) {
                    Auth::login($ssoUser, true);
                    SharedSsoService::syncKalibrasiSession($ssoUser);
                }
            } else {
                // If authenticated but SSO cookie is missing, re-issue it!
                // NEVER force logout or destroy session here!
                $reissuedCookie = SharedSsoService::createSsoCookie(Auth::user());
                $response = $next($request);
                $response->withCookie($reissuedCookie);
                $this->applyAntiCacheHeaders($response);
                return $response;
            }
        } else {
            // 3. If NOT logged in, check SSO token for auto-login
            if (!empty($ssoToken)) {
                $ssoUser = SharedSsoService::validateToken($ssoToken);
                if ($ssoUser) {
                    Auth::login($ssoUser, true);
                    SharedSsoService::syncKalibrasiSession($ssoUser);
                }
            } elseif (Session::has('kalibrasi_user')) {
                $kalUser = Session::get('kalibrasi_user');
                $kalUsername = $kalUser['username'] ?? '';
                if ($kalUsername) {
                    $dbUser = \App\Models\User::where('username', $kalUsername)
                        ->orWhere('email', str_contains($kalUsername, '@') ? $kalUsername : $kalUsername . '@eslab.com')
                        ->first();
                    if ($dbUser) {
                        Auth::login($dbUser, true);
                    }
                }
            }
        }

        if (!Auth::check()) {
            if ($request->is('*kalibrasi*')) {
                return redirect()->route('kalibrasi.login')->with('info', 'Silakan login terlebih dahulu untuk mengakses Modul Kalibrasi.');
            }
            return redirect(url('/pengujian/login'))->with('info', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // If accessing shared/master modules like Kelola Klien, allow all authenticated users
        if ($request->is('*kelola-klien*') || $request->is('*kelola_klien*') || $request->is('*sso*')) {
            return $next($request);
        }

        // Check if user has permission to access Pengujian
        if ($user && isset($user->can_access_pengujian) && !$user->can_access_pengujian && !$request->is('*kalibrasi*')) {
            return redirect()->route('kalibrasi.dashboard')->with('info', 'Akses Terbatas: Akun Anda (' . ($user->name ?? 'User') . ') hanya diizinkan mengakses Modul Kalibrasi.');
        }

        $response = $next($request);
        $this->applyAntiCacheHeaders($response);

        return $response;
    }

    private function applyAntiCacheHeaders(Response $response): void
    {
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }
}