<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberCardProduct extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;

    protected $fillable = [
        'member_id',
        'member_card_id',
        'product_id',
        'store_id',
        'number_type',
        'origin_number',
        'used_number',
        'current_number',
        'valid_time',
        'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function memberCard(): BelongsTo
    {
        return $this->belongsTo(MemberCard::class);
    }
}
