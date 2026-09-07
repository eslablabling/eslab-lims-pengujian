<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamplingRequest extends Model
{
    protected $table = 'sampling_requests';

    protected $fillable = [
        'client_account_id', 'company_name', 'contact_person',
        'tgl_rencana', 'jumlah_cerobong', 'parameters', 'lain_lain', 'status'
    ];

    protected $casts = [
        'tgl_rencana' => 'date',
        'parameters' => 'array',
    ];

    public function clientAccount()
    {
        return $this->belongsTo(ClientAccount::class, 'client_account_id');
    }
}
