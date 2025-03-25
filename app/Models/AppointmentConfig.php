<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public static function getByStoreId(int $storeId)
    {
        return static::firstOrCreate(['store_id' => $storeId], [
            'earliest' => '09:00',
            'latest' => '21:00',
            'interval' => 30,
            'max_number' => 5,
            'before_time' => 30,
            'status' => 0
        ]);
    }
}
