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

    const TYPE_SEND = 1;
    const TYPE_ORDER = 2;

    const TYPE = [
        self::TYPE_SEND => '发送',
        self::TYPE_ORDER => '购买短信包',
    ];

}
