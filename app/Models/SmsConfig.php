<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'is_new',
        'balance',
        'consume_switch'
    ];
}
