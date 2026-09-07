<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'role_code', 'username', 'is_active',
        'can_access_pengujian', 'can_access_kalibrasi', 'can_access_hris', 'can_view_harga',
        'allowed_menus_json'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'can_access_pengujian' => 'boolean',
            'can_access_kalibrasi' => 'boolean',
            'can_access_hris' => 'boolean',
            'can_view_harga' => 'boolean',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function getRole(): string
    {
        return $this->role_code ?? $this->role ?? $this->profile?->role_code ?? $this->profile?->role ?? 'admin_master';
    }

    public function hasRole(string|array $roles): bool
    {
        $userRole = $this->getRole();
        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }
        return $userRole === $roles;
    }

    public function isMasterDev(): bool
    {
        $rCode = $this->getRole();
        return ($this->username ?? '') === 'admin_.master' 
            || ($this->email ?? '') === 'admin_.master@eslab.com'
            || $rCode === 'admin_master';
    }

    public function canAccessPengujian(): bool
    {
        if ($this->isMasterDev()) return true;
        if (isset($this->can_access_pengujian) && $this->can_access_pengujian !== null) {
            return (bool)$this->can_access_pengujian;
        }
        if (isset($this->profile?->can_access_pengujian) && $this->profile?->can_access_pengujian !== null) {
            return (bool)$this->profile->can_access_pengujian;
        }
        return true;
    }

    public function canAccessKalibrasi(): bool
    {
        if ($this->isMasterDev()) return true;
        if (isset($this->can_access_kalibrasi) && $this->can_access_kalibrasi !== null) {
            return (bool)$this->can_access_kalibrasi;
        }
        if (isset($this->profile?->can_access_kalibrasi) && $this->profile?->can_access_kalibrasi !== null) {
            return (bool)$this->profile->can_access_kalibrasi;
        }
        return true;
    }

    public function canAccessHris(): bool
    {
        if ($this->isMasterDev()) return true;
        if (isset($this->can_access_hris) && $this->can_access_hris !== null) {
            return (bool)$this->can_access_hris;
        }
        if (isset($this->profile?->can_access_hris) && $this->profile?->can_access_hris !== null) {
            return (bool)$this->profile->can_access_hris;
        }
        return true;
    }

    public function canAccessMenu(string $menuKey): bool
    {
        if ($this->isMasterDev()) return true;

        // Check Module level
        if ((str_starts_with($menuKey, 'pengujian_') || str_starts_with($menuKey, 'lims_')) && !$this->canAccessPengujian()) {
            return false;
        }
        if (str_starts_with($menuKey, 'kalibrasi_') && !$this->canAccessKalibrasi()) {
            return false;
        }
        if (str_starts_with($menuKey, 'hris_') && !$this->canAccessHris()) {
            return false;
        }

        $rawAllowed = $this->allowed_menus_json ?? $this->profile?->allowed_menus_json;
        if (!empty($rawAllowed)) {
            $allowed = is_array($rawAllowed) ? $rawAllowed : json_decode($rawAllowed, true);

            if (is_array($allowed) && !empty($allowed)) {
                $possibleKeys = [
                    $menuKey,
                    str_replace('pengujian_', 'lims_', $menuKey),
                    str_replace('lims_', 'pengujian_', $menuKey),
                ];

                foreach ($possibleKeys as $pk) {
                    if (in_array($pk, $allowed)) {
                        return true;
                    }
                }
                return false;
            }
        }

        return true;
    }
}
