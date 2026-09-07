<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'coc_id', 'sample_id', 'description', 'nama_cerobong',
        'status', 'status_lab', 'is_verified', 'tgl_terima_lab',
        'analyzed_at', 'verified_at', 'rework_reason',
        'waktu_gas', 'no_alat_gas', 'temp_gas', 'tekanan_atm',
        'jarak_pengamat_awal', 'jarak_pengamat_akhir',
        'arah_pengamat_awal', 'arah_pengamat_akhir',
        'warna_emisi_awal', 'warna_emisi_akhir',
        'latar_asap_awal', 'latar_asap_akhir',
        'kondisi_langit_awal', 'kondisi_langit_akhir',
        'temp_ambien_awal', 'temp_ambien_akhir',
        'kelembaban_awal', 'kelembaban_akhir',
        'kec_angin_awal', 'kec_angin_akhir',
        'arah_angin_awal', 'arah_angin_akhir',
        'desc_emisi', 'opasitas_mulai', 'opasitas_akhir',
        'opasitas_matrix', 'opasitas_avg',
        'opasitas_ket_1', 'opasitas_ket_2', 'opasitas_ket_3',
        'opasitas_ket_4', 'opasitas_ket_5', 'opasitas_ket_6',
        'regulations', 'parameters',
        'qc_blank_weight', 'qc_dup_weight_1', 'qc_dup_weight_2', 'qc_rpd', 'qc_status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'tgl_terima_lab' => 'datetime',
        'analyzed_at' => 'datetime',
        'verified_at' => 'datetime',
        'opasitas_matrix' => 'array',
        'regulations' => 'array',
        'parameters' => 'array',
    ];

    public function coc()
    {
        return $this->belongsTo(CocEmisi::class, 'coc_id');
    }
}
