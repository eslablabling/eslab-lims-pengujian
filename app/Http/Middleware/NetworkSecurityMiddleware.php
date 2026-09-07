<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NetworkSecurityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        // 1. Check if client IP is blocked (with safety check)
        try {
            $isBlocked = DB::table('kalibrasi_blocked_ips')->where('ip_address', $clientIp)->exists();
            if ($isBlocked) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Akses Perangkat Anda Diblokir oleh Admin Master.'], 403);
                }
                return response()->view('kalibrasi.errors.403_blocked', ['ip' => $clientIp], 403);
            }
        } catch (\Throwable $e) {
            // If table doesn't exist yet, allow request to proceed safely
        }

        // 2. Track active session and device
        try {
            $userAgent = $request->userAgent() ?? '';
            $deviceType = 'Desktop / PC';
            if (preg_match('/(android|bb\d+|meego).+mobile|avail|blackberry|emulator|iphone|ipod|palm|phone|iemobile|small|smartphone|symbian|windows phone|xda/i', $userAgent)) {
                $deviceType = 'Mobile / HP';
            } elseif (preg_match('/ipad|tablet|playbook|silk/i', $userAgent)) {
                $deviceType = 'Tablet';
            }

            $user = Session::get('kalibrasi_user');
            $username = 'Guest';
            $name = 'Tamu / Belum Login';
            $role = 'guest';

            if ($user) {
                if (is_array($user)) {
                    $username = $user['username'] ?? 'User';
                    $name = $user['name'] ?? 'User Kalibrasi';
                    $role = $user['role_label'] ?? ($user['role'] ?? 'Staff');
                } else {
                    $username = $user->username ?? 'User';
                    $name = $user->name ?? 'User Kalibrasi';
                    $role = $user->role ?? 'Staff';
                }
            } elseif (Auth::check()) {
                $authUser = Auth::user();
                $username = $authUser->username ?? $authUser->email ?? 'User';
                $name = $authUser->name ?? 'User LIMS';
                $role = $authUser->role ?? 'Staff';
            }

            $sessionId = Session::getId();
            if (!empty($sessionId)) {
                $pageTitle = $this->resolvePageTitle($request);
                $fullUrl = $request->fullUrl();

                DB::table('kalibrasi_active_sessions')->updateOrInsert(
                    ['session_id' => $sessionId],
                    [
                        'username' => $username,
                        'user_name' => $name,
                        'role' => $role,
                        'ip_address' => $clientIp,
                        'user_agent' => substr($userAgent, 0, 500),
                        'device_type' => $deviceType,
                        'current_page_title' => $pageTitle,
                        'current_url' => substr($fullUrl, 0, 255),
                        'last_activity' => now(),
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                // Auto purge sessions older than 15 minutes
                DB::table('kalibrasi_active_sessions')
                    ->where('last_activity', '<', now()->subMinutes(15))
                    ->delete();

                // Record page view activity log (debounced by 30s for same page)
                $lastLog = DB::table('kalibrasi_activity_logs')
                    ->where('username', $username)
                    ->where('ip_address', $clientIp)
                    ->orderBy('id', 'desc')
                    ->first();

                if ((!$request->ajax() && !$request->wantsJson()) && (!$lastLog || $lastLog->description !== "Membuka halaman {$pageTitle}" || (time() - strtotime($lastLog->created_at ?? 0)) > 30)) {
                    \App\Helpers\KalibrasiLogger::log('VIEW', 'Navigasi Module', "Membuka halaman {$pageTitle}", $request);
                }
            }
        } catch (\Throwable $e) {
            // Silence tracking errors to keep main app running uninterrupted
        }

        return $next($request);
    }

    private function resolvePageTitle(Request $request): string
    {
        $path = strtolower(trim($request->path(), '/'));

        if (str_contains($path, 'kalibrasi/dashboard') || $path === 'kalibrasi') {
            return '📊 Dashboard Kalibrasi';
        } elseif (str_contains($path, 'kalibrasi/permintaan')) {
            return '📝 1. Quotation (32 Kolom)';
        } elseif (str_contains($path, 'kalibrasi/lembar-proses')) {
            return '📋 2. Lembar Proses / COC';
        } elseif (str_contains($path, 'kalibrasi/penerbitan-jadwal')) {
            return '📅 3. Penerbitan Jadwal (Form 7.4.6)';
        } elseif (str_contains($path, 'kalibrasi/penerimaan')) {
            return '📦 4. Penerimaan & Pengembalian Alat';
        } elseif (str_contains($path, 'kalibrasi/input-data')) {
            if (str_contains($path, 'create')) {
                return '✍️ Isu / Edit Form Lembar Kerja';
            }
            return '🔢 5. Input Data & U95 (67 Form)';
        } elseif (str_contains($path, 'kalibrasi/test-lembar-kerja')) {
            return '🧪 Test Lembar Kerja 1:1';
        } elseif (str_contains($path, 'kalibrasi/evaluasi')) {
            return '✅ 6. Evaluasi Manager';
        } elseif (str_contains($path, 'kalibrasi/coa')) {
            return '📜 7. Sertifikat Kalibrasi (COA)';
        } elseif (str_contains($path, 'kalibrasi/invoice')) {
            return '💳 8. Pembuatan Invoice';
        } elseif (str_contains($path, 'kalibrasi/pengiriman-dokumen')) {
            return '🚚 9. Pengiriman Dokumen';
        } elseif (str_contains($path, 'kalibrasi/finance-penagihan')) {
            return '💰 10. Penagihan Finance';
        } elseif (str_contains($path, 'kalibrasi/monitoring')) {
            return '📺 Monitoring 28 Kolom';
        } elseif (str_contains($path, 'kalibrasi/network-monitor')) {
            return '🔒 Security Monitor LAN/HP';
        } elseif (str_contains($path, 'kalibrasi/master-data')) {
            return '🗄️ Master Data Pengujian';
        }

        return '🌐 ' . ($request->path() ?: 'Aplikasi LIMS');
    }
}
