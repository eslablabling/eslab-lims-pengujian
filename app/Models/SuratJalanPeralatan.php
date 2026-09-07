<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalanPeralatan extends Model
{
    protected $table = 'surat_jalan_peralatan';

    protected $fillable = [
        'no_surat_jalan', 'coc_id', 'nama_pekerjaan', 'nomor_qt', 'nomor_coc',
        'nama_pelanggan', 'lokasi_pengerjaan', 'transportasi',
        'tgl_pengerjaan_start', 'tgl_pengerjaan_end', 'items_json',
        'teknisi_lab', 'lab_manager', 'diserahkan_oleh', 'diterima_kembali_oleh', 'status'
    ];

    protected $casts = [
        'tgl_pengerjaan_start' => 'date',
        'tgl_pengerjaan_end' => 'date',
        'items_json' => 'array',
    ];

    public function coc()
    {
        return $this->belongsTo(CocEmisi::class, 'coc_id');
    }
}
