<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('kalibrasi_user') || \Illuminate\Support\Facades\Auth::check()) {
            return $next($request);
        }

        if (!session()->has('client_logged_in') || !session('client_logged_in')) {
            if (str_contains($request->getRequestUri(), 'kalibrasi')) {
                return redirect('/kalibrasi/klien/login');
            }
            return redirect('/pengujian/klien/login');
        }

        return $next($request);
    }
}
