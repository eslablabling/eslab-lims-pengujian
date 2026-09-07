<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https' || str_contains(request()->root(), 'https') || isset($_SERVER['HTTPS'])) {
            URL::forceScheme('https');
        }

        if (isset($_SERVER['SCRIPT_NAME']) && (str_starts_with($_SERVER['SCRIPT_NAME'], '/kalibrasi') || str_starts_with($_SERVER['SCRIPT_NAME'], '/pengujian'))) {
            $_SERVER['SCRIPT_NAME'] = '/index.php';
            $_SERVER['PHP_SELF'] = '/index.php';
        }
    }
}
