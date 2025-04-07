<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeBankStock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'days_of_product_period',
        'product_period',
        'bank_short_name',
        'bank_name',
        'rate_value',
        'unit_net_value',
        'adjust_unit_net_value',
        'fund_begin_yield',
        'month_yield',
        'month',
        'season_yield',
        'threemonth',
        'halfyearyield',
        'sixmonth',
        'twelvemonthyield',
        'start_buy_time',
        'earnings_rate_date',
    ];
}
