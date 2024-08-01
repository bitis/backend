<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spec extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = ['store_id', 'name'];

    protected $hidden = ['store_id', 'deleted_at', 'created_at', 'updated_at'];

    public function values()
    {
        return $this->hasMany(SpecValue::class);
    }
}
