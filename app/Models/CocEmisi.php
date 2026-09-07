<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CocEmisi extends Model
{
    use HasFactory;

    protected $table = 'coc_emisi';

    protected $fillable = [
        'nomor_coc', 'company_name', 'alamat_perusahaan', 'company_address',
        'contact_person', 'no_telepon', 'phone_no', 'email_coa',
        'sampling_date', 'tgl_selesai', 'tat_days', 'tat_requested',
        'nomor_qt', 'qt_no', 'jenis_usaha', 'keterangan',
        'status', 'status_sampling', 'sampling_officer', 'sampling_location',
        'samples_data', 'latitude', 'longitude', 'lokasi_kota',
        'scanned_coa_url', 'created_by'
    ];

    protected $casts = [
        'sampling_date' => 'date',
        'tgl_selesai' => 'date',
        'samples_data' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function samples()
    {
        return $this->hasMany(Sample::class, 'coc_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function suratJalan()
    {
        return $this->hasOne(SuratJalanPeralatan::class, 'coc_id');
    }

    public static function generateNomorCoc(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        return sprintf('COC-ES/%s%s/%04d', $year, $month, $count);
    }
}
