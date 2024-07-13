<?php

namespace App\Models;

use App\Models\Enumerations\ProductType;
use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = ['store_id', 'name', 'discount', 'item_limit', 'item_count', 'remark'];

    protected $hidden = ['deleted_at'];

    public function linkProductIds(): HasMany
    {
        return $this->hasMany(LevelProduct::class)->where('type', ProductType::Product->value);
    }

    public function linkServiceIds(): HasMany
    {
        return $this->hasMany(LevelProduct::class)->where('type', ProductType::Product->value);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
