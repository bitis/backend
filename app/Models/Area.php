<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'pid'];

    protected $hidden = ['created_at', 'updated_at', 'pid'];

    protected $with = ['children'];

    public function children(): HasMany
    {
        return $this->hasMany(Area::class, 'pid', 'id');
    }
}
