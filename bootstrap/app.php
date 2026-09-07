<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->encryptCookies(except: [
            'eslab_shared_sso',
        ]);
        $middleware->validateCsrfTokens(except: [
            '_api/*',
            'logout',
            '*/logout',
            'klien/logout',
            '*/klien/logout',
            'kalibrasi/web-excel/save',
            'kalibrasi/web_excel/save',
            'kalibrasi/web-excel-preview/save',
            '*/web-excel/save',
            '*/web_excel/save',
        ]);
        $middleware->alias([
            'role'        => \App\Http\Middleware\RoleMiddleware::class,
            'client.auth' => \App\Http\Middleware\ClientAuthMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->is('logout') || $request->is('*/logout') || $request->routeIs('logout')) {
                return redirect()->route('login')->with('info', 'Anda telah keluar dari akun.');
            }
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi telah berakhir (CSRF Token Expired). Silakan refresh halaman dan coba lagi.',
                ], 419);
            }
            return back()
                ->withInput($request->except('_token', 'password'))
                ->withErrors(['username' => 'Sesi keamanan baru saja diperbarui. Silakan masukkan password dan klik Masuk lagi.']);
        });

        // Diagnostic detailed error rendering for Laravel 12
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return redirect()->guest(route('login'));
            }
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return null;
            }
            if ($e instanceof TokenMismatchException) {
                return null;
            }

            \Log::error('LIMS Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(), [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            // If APP_DEBUG is enabled or admin request
            if (config('app.debug') || env('APP_DEBUG', true)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => true,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'exception' => get_class($e)
                    ], 500);
                }

                $msg = htmlspecialchars($e->getMessage());
                $file = htmlspecialchars($e->getFile());
                $line = $e->getLine();
                $class = htmlspecialchars(get_class($e));
                $url = htmlspecialchars($request->fullUrl());
                $method = htmlspecialchars($request->method());

                $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic Error Detection - ESLab LIMS</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0b132b; color: #e0e6ed; padding: 24px; margin: 0; line-height: 1.6; }
        .error-card { max-width: 850px; margin: 30px auto; background: #1c2541; border: 1.5px solid #ef4444; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #3a506b; padding-bottom: 16px; }
        .badge { background: #ef4444; color: #fff; padding: 4px 12px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; }
        h2 { margin: 0; font-size: 1.4rem; color: #f87171; }
        .info-group { margin-bottom: 16px; }
        .label { font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
        .value-box { background: #0b132b; border: 1px solid #3a506b; border-radius: 8px; padding: 12px 14px; font-family: monospace; font-size: 0.92rem; color: #38bdf8; word-break: break-all; }
        .btn-back { display: inline-block; background: #3b82f6; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; margin-top: 20px; transition: 0.2s; }
        .btn-back:hover { background: #2563eb; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="header">
            <span class="badge">LIMS Error Detected</span>
            <h2>Detail Diagnostik Error Sistem LIMS</h2>
        </div>
        <div class="info-group">
            <div class="label">Pesan Error:</div>
            <div class="value-box" style="color: #fca5a5; font-weight: bold; font-size: 1rem;">{$msg}</div>
        </div>
        <div class="info-group">
            <div class="label">Tipe Exception:</div>
            <div class="value-box">{$class}</div>
        </div>
        <div class="info-group">
            <div class="label">Lokasi File & Baris:</div>
            <div class="value-box" style="color: #fde047;">{$file} : Baris {$line}</div>
        </div>
        <div class="info-group">
            <div class="label">Request URL & Method:</div>
            <div class="value-box">[{$method}] {$url}</div>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="javascript:history.back()" class="btn-back">⬅️ Kembali & Coba Lagi</a>
            <a href="/pengujian/dashboard" class="btn-back" style="background: #475569;">🏠 Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
HTML;
                return response($html, 500)->header('Content-Type', 'text/html');
            }

            if (view()->exists('errors.500')) {
                return response()->view('errors.500', [], 500);
            }

            return response('Internal Server Error', 500);
        });
    })->create();
