<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisaProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subtitle',
        'entranceImg',
        'seckillImg',
        'sellPrice',
        'purchasePrice',
        'stockStatus',
        'v_id',
        'activityId',
        'channelId',
        'stock',
        'goodsIntroduction',
        'purchaseNotes',
        'goodsTagOne',
        'goodsTagTwo',
    ];
}
