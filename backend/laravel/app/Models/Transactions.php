<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $fillable = [
        'transaction_id',
        'bakong_account_id',
        'qr_string',
        'qr_md5',
        'amount',
        'status',
    ];
}
