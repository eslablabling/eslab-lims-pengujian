<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengujianOrder extends Model
{
    use HasFactory;

    protected $table = 'pengujian_orders';

    protected $fillable = [
        'no_order', 'no_quotation', 'no_po', 'file_po', 'nama_pelanggan',
        'alamat_pelanggan', 'kontak_person', 'no_hp', 'email', 'tipe_pekerjaan',
        'jenis_usaha', 'status', 'status_verifikasi', 'alasan_verifikasi', 'tanggal_masuk',
        'target_selesai', 'catatan', 'jadwal_sampling', 'petugas_sampling',
        'surat_tugas_no', 'lokasi_sampling', 'no_invoice', 'status_pembayaran',
        'nominal_dibayar', 'top_days', 'tat_days', 'metode_pembayaran', 'catatan_pembayaran',
        'tgl_peringatan_terakhir', 'is_ppn', 'ppn_persen', 'biaya_sampling', 'biaya_mop_demop',
        'diskon', 'is_dp', 'dp_persen', 'dp_amount', 'no_kwitansi', 'no_bast', 'no_tst',
        'faktur_pajak_no', 'direktur_name', 'lab_manager_name', 'catatan_invoice',
        'is_verified_finance', 'verified_finance_at', 'tgl_bayar',
        'no_resi_pengiriman', 'kurir_pengiriman', 'tgl_kirim_dokumen',
        'foto_bukti_kirim', 'status_pengiriman'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'target_selesai' => 'date',
        'jadwal_sampling' => 'date',
        'tgl_bayar' => 'date',
        'tgl_kirim_dokumen' => 'date',
        'tgl_peringatan_terakhir' => 'datetime',
        'verified_finance_at' => 'datetime',
        'is_ppn' => 'boolean',
        'is_dp' => 'boolean',
        'is_verified_finance' => 'boolean',
        'nominal_dibayar' => 'decimal:2',
        'ppn_persen' => 'decimal:2',
        'biaya_sampling' => 'decimal:2',
        'biaya_mop_demop' => 'decimal:2',
        'diskon' => 'decimal:2',
        'dp_persen' => 'decimal:2',
        'dp_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(PengujianItem::class, 'pengujian_order_id');
    }

    public function coc()
    {
        return $this->hasOne(CocEmisi::class, 'nomor_qt', 'no_quotation');
    }

    public function cocs()
    {
        return $this->hasMany(CocEmisi::class, 'nomor_qt', 'no_quotation');
    }

    public function getTotalHargaTitikAttribute(): float
    {
        return (float) $this->items->sum('subtotal');
    }

    public function getSubtotalDppAttribute(): float
    {
        $titik = $this->total_harga_titik;
        $sampling = (float) ($this->biaya_sampling ?? 0);
        $mopDemop = (float) ($this->biaya_mop_demop ?? 0);
        $disc = (float) ($this->diskon ?? 0);
        return max(0, ($titik + $sampling + $mopDemop) - $disc);
    }

    public function getTotalHargaAttribute(): float
    {
        return $this->subtotal_dpp;
    }

    public function getPpnAmountAttribute(): float
    {
        if (!$this->is_ppn) return 0.0;
        return $this->subtotal_dpp * (($this->ppn_persen ?? 11) / 100);
    }

    public function getGrandTotalAttribute(): float
    {
        return $this->subtotal_dpp + $this->ppn_amount;
    }

    public function getSisaTagihanAttribute(): float
    {
        return max(0, $this->grand_total - $this->nominal_dibayar);
    }

    public static function generateNoOrder(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        return sprintf('ORD-ENV/%s%s/%04d', $year, $month, $count);
    }

    public static function generateNoQuotation(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = self::whereNotNull('no_quotation')->whereYear('created_at', $year)->count() + 1;
        return sprintf('QT-ENV/%s/%04d', $year, $count);
    }

    public static function generateNoInvoice(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = self::whereNotNull('no_invoice')->whereYear('created_at', $year)->count() + 1;
        return sprintf('INV-ENV/%s%s/%04d', $year, $month, $count);
    }
}
