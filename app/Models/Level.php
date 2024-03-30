<?php

namespace App\Models;

use App\Models\Enumerations\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'flag', 'discount', 'item_limit', 'item_count'];

    protected $hidden = ['deleted_at'];

    public function linkProductIds(): HasMany
    {
        return $this->hasMany(LevelProduct::class)->where('type', ProductType::Product->value);
    }

    public function linkServiceIds(): HasMany
    {
        return $this->hasMany(LevelProduct::class)->where('type', ProductType::Product->value);
    }
}
