<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterEmisi extends Model
{
    protected $table = 'master_emisi';
    protected $fillable = [
        'nama_parameter', 'parameter', 'satuan', 'unit',
        'baku_mutu', 'regulasi', 'metode', 'koreksi_o2', 'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'baku_mutu' => 'decimal:4',
        'koreksi_o2' => 'decimal:2',
    ];

    public function getNamaAttribute()
    {
        return $this->parameter ?? $this->nama_parameter;
    }
}
