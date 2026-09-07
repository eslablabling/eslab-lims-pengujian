<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientMessage extends Model
{
    protected $table = 'client_messages';

    protected $fillable = [
        'company_name', 'sender_name', 'subject', 'message',
        'reply', 'replied_by', 'replied_at', 'status'
    ];

    protected $casts = ['replied_at' => 'datetime'];
}
