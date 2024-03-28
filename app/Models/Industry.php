<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industry extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'name',
        'pid',
    ];
    protected $hidden = [
        'pid', 'deleted_at', 'created_at', 'updated_at'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Industry::class, 'pid', 'id');
    }
}
