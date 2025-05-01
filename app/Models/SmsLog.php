<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'title',
        'signature',
        'content',
        'file',
        'content_length',
        'mobile_count',
        'failed_count',
        'status',
    ];
}
