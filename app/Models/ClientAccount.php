<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientAccount extends Model
{
    protected $table = 'client_accounts';

    protected $fillable = ['company_name', 'username', 'password', 'default_module', 'is_active', 'last_reset'];

    protected $hidden = ['password'];

    protected $casts = [
        'last_reset' => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function checkLogin(string $password): array
    {
        if (!$this->is_active) {
            return ['success' => false, 'message' => 'Akun Anda telah dinonaktifkan oleh administrator.'];
        }

        $isValidPassword = false;
        $stored = (string) ($this->password ?? '');
        $input = (string) $password;

        // 1. Direct plaintext match
        if ($stored === $input || trim($stored) === trim($input)) {
            $isValidPassword = true;
        }

        // 2. Safe Hash check if password is a bcrypt hash
        if (!$isValidPassword && (str_starts_with($stored, '$2y$') || str_starts_with($stored, '$2a$') || str_starts_with($stored, '$argon2'))) {
            try {
                if (\Illuminate\Support\Facades\Hash::check($input, $stored)) {
                    $isValidPassword = true;
                }
            } catch (\Throwable $e) {
                // Ignore hash error and treat as mismatch
            }
        }

        if (!$isValidPassword) {
            return ['success' => false, 'message' => 'Password salah.'];
        }

        $result = [
            'success' => true,
            'company_name' => $this->company_name,
            'username' => $this->username,
            'default_module' => $this->default_module ?? 'kalibrasi',
            'password_reset_trigger' => false,
            'new_password' => $password,
        ];

        // Auto-rotate password after 30 days
        if ($this->last_reset && $this->last_reset->lt(Carbon::now()->subDays(30))) {
            $newPwd = 'ES-' . strtoupper(Str::random(5));
            $this->update(['password' => $newPwd, 'last_reset' => now()]);
            $result['password_reset_trigger'] = true;
            $result['new_password'] = $newPwd;
        }

        return $result;
    }

    public static function generateUsername(string $companyName): string
    {
        $clean = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($companyName));
        $base = 'klien_' . substr($clean, 0, 20);
        if (empty($clean)) {
            $base = 'klien_user';
        }
        $username = $base;
        $counter = 2;
        while (static::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }
        return $username;
    }

    public static function generatePassword(int $length = 10): string
    {
        $upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lower = 'abcdefghijkmnpqrstuvwxyz';
        $numbers = '23456789';
        $symbols = '@#$%!*&';

        $pwd = [];
        $pwd[] = $upper[random_int(0, strlen($upper) - 1)];
        $pwd[] = $lower[random_int(0, strlen($lower) - 1)];
        $pwd[] = $numbers[random_int(0, strlen($numbers) - 1)];
        $pwd[] = $symbols[random_int(0, strlen($symbols) - 1)];

        $all = $upper . $lower . $numbers . $symbols;
        $remaining = max(4, $length - count($pwd));

        for ($i = 0; $i < $remaining; $i++) {
            $pwd[] = $all[random_int(0, strlen($all) - 1)];
        }

        shuffle($pwd);

        return implode('', $pwd);
    }

    /**
     * Ensure a client account exists for the given company name when a PO is created/updated.
     * Prevents duplicates by company name.
     */
    public static function ensureAccountForCompany(?string $companyName, string $defaultModule = 'kalibrasi'): ?self
    {
        $companyName = trim($companyName ?? '');
        if (empty($companyName)) {
            return null;
        }

        // Search case-insensitively for existing account
        $existing = static::whereRaw('LOWER(TRIM(company_name)) = ?', [strtolower($companyName)])->first();
        if ($existing) {
            return $existing;
        }

        // Generate username & password
        $username = static::generateUsername($companyName);
        $password = static::generatePassword();

        return static::create([
            'company_name'   => $companyName,
            'username'       => $username,
            'password'       => $password,
            'default_module' => $defaultModule,
            'is_active'      => true,
            'last_reset'     => now(),
        ]);
    }
}
