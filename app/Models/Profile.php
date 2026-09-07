<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = [
        'user_id', 'full_name', 'username', 'role', 'company_name',
        'phone', 'notes', 'plain_password', 'is_active', 'status_karyawan',
        'last_password_reset', 'avatar_url'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_password_reset' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
