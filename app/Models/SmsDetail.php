<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsDetail extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'sms_record_id',
        'mobile',
        'source',
        'content',
        'result',
        'status',
        'send_at',
        'response_at',
    ];
}
