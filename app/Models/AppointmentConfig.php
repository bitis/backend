<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentConfig extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'earliest',
        'latest',
        'interval',
        'max_number',
        'before_time',
        'slots',
        'status'
    ];

    protected $casts = [
        'slots' => 'json'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public static function getByStoreId(int $storeId)
    {
        return static::first(['store_id' => $storeId]);
    }
}
