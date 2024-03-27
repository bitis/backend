<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, DefaultDatetimeFormat, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
