<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeBankStock extends Model
{
    use SoftDeletes, DefaultDatetimeFormat;

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
        'daily_increase_money',
        'daily_increase_change',
        'month_increase_money',
        'pre_month_increase_money',
        'value_date'
    ];
}
