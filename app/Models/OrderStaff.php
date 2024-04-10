<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStaff extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'order_id',
        'staff_id',
        'order_id',
        'product_id',
        'product_name',
        'product_type',
        'number',
        'intro',
        'performance',
        'commission',
        'remark'
    ];
}
