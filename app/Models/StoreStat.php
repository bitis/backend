<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreStat extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'new_member',
        'new_order',
        'sale_card_amount',
        'use_card_amount',
        'use_card_times',
        'use_money_amount',
        'cost_amount',
        'staff_sale_amount',
        'staff_bonus_amount',
        'profit_amount',
        'date',
        'month',
        'year'
    ];
}
