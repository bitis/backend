<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'flag', 'discount', 'item_limit', 'item_count'];

    protected $hidden = ['deleted_at'];
}
