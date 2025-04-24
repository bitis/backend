<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    const TYPE_PRODUCT = 1;
    const TYPE_SERVICE = 2;

    const MAP_TYPE = [
        self::TYPE_PRODUCT => '产品',
        self::TYPE_SERVICE => '服务'
    ];

    protected $fillable = [
        'store_id',
        'category_id',
        'type',
        'name',
        'unit',
        'subtitle',
        'images',
        'content',
        'bar_code',
        'price',
        'original_price',
        'member_price',
        'stock',
        'stock_warn',
        'online',
        'spec_type',
        'commission_config'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'images' => 'array',
    ];

    protected $appends = ['type_text'];

    public function getTypeTextAttribute(): string
    {
        return self::MAP_TYPE[$this->type];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductItem::class, 'product_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function specs()
    {
        return $this->hasMany(ProductSpec::class);
    }

    public function getImagesAttribute($val): array
    {
        return json_decode($val, true) ?: ["https://static1.yuguaikeji.com/images/p1.png"];
    }
}
