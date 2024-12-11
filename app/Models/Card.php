<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, DefaultDatetimeFormat, SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'type',
        'price',
        'valid_type',
        'valid_time',
        'remark',
        'commission_config',
        'gift_money',
        'level_id',
        'sales_count',
        'product_count'
    ];

    const VALID_FOREVER = 1;
    const VALID_DAYS = 2;

    const TYPE_TIMES = 1;
    const TYPE_DURATION = 2;
    const TYPE_RECHARGE = 3;

    public function services(): HasMany
    {
        return $this->hasMany(CardProduct::class, 'card_id', 'id');
    }
}
