<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_item_id',
        'name',
        'value',
    ];
}
