<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CardProduct extends Model
{
    use HasFactory;


    const TYPE_SERVICE = 1;
    const TYPE_GIFT = 2;

    const NUMBER_TYPE_LIMIT = 1;
    const NUMBER_TYPE_UNLIMIT = 2;

    protected $fillable = ['card_id', 'product_id', 'number_type', 'number', 'type'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
