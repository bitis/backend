<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'sn',
        'type',
        'version',
        'cutter',
        'voice_type',
        'volume_level',
    ];
}
