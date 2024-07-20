<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSignature extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'name',
    ];

    protected $appends = [
        'final_name'
    ];

    public function getFinalNameAttribute(): string
    {
        return  '【' . $this->attributes['name'] . '】';
    }
}
