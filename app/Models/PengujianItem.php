<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengujianItem extends Model
{
    use HasFactory;

    protected $table = 'pengujian_items';

    protected $fillable = [
        'pengujian_order_id', 'sample_id', 'nama_titik_uji', 'regulasi', 'kategori_uji',
        'parameter_uji_json', 'harga_satuan', 'jumlah_titik',
        'subtotal', 'coc_id'
    ];

    protected $casts = [
        'parameter_uji_json' => 'array',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'jumlah_titik' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(PengujianOrder::class, 'pengujian_order_id');
    }

    public function coc()
    {
        return $this->belongsTo(CocEmisi::class, 'coc_id');
    }
}
