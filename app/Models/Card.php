<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, DefaultDatetimeFormat, SoftDeletes;

    protected $fillable = ['store_id', 'name', 'type', 'price', 'valid_type', 'valid_time', 'remark'];

//    public function products(): BelongsToMany
//    {
//        return $this->belongsToMany(Product::class, CardProduct::class,
//            'card_id',
//            'product_id'
//        )->withPivot('number_type', 'number', 'type')->as('card_product');
//    }

    public function products(): HasMany
    {
        return $this->hasMany(CardProduct::class);
    }
}
