<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPeralatan extends Model
{
    protected $table = 'master_peralatan';

    protected $fillable = [
        'no_urut', 'yymm', 'no_inventaris', 'nama_alat', 'merek_brand',
        'type_model', 'no_seri', 'rentang_akurasi', 'lokasi',
        'tgl_kalibrasi', 'periode_kalibrasi', 'jadwal_kalibrasi',
        'lembaga_kalibrasi', 'kondisi', 'sertifikat_url',
        'box_pengaman_default', 'keterangan'
    ];

    protected $casts = [
        'tgl_kalibrasi' => 'date',
        'jadwal_kalibrasi' => 'date',
        'box_pengaman_default' => 'boolean',
    ];

    public function isKalibrasiExpired(): bool
    {
        return $this->jadwal_kalibrasi && $this->jadwal_kalibrasi->isPast();
    }

    public function isKalibrasiWarning(): bool
    {
        if (!$this->jadwal_kalibrasi) return false;
        return $this->jadwal_kalibrasi->isFuture() && $this->jadwal_kalibrasi->diffInDays(now()) <= 30;
    }
}
