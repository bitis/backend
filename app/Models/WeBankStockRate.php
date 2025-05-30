<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeBankStockRate extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'prod_code',
        'earnings_rate_date',
        'accu_net_value',
        'unit_net_value',
        'daily_increase_change',
        'fund_begin_yield',
        'month_yield',
        'season_yield',
    ];
}
