<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'category_id',
        'type',
        'name',
        'unit',
        'subtitle',
        'first_image',
        'bar_code',
        'price',
        'original_price',
        'member_price',
        'stock',
        'stock_warn',
        'flag',
        'spec_type',
        'commission_config'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
