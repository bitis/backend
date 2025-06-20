<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'auto_print',
        'name',
        'endnote',
        'phone',
        'address',
        'operator',
        'member_name',
        'printer_id',
        'print_ready',
    ];

    protected $casts = [
        'auto_print' => 'boolean',
        'phone' => 'boolean',
        'address' => 'boolean',
        'operator' => 'boolean',
        'member_name' => 'boolean',
        'print_ready' => 'boolean',
    ];
}
