<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'sort',
        'name',
        'icon',
        'uri',
        'type',
        'permission',
        'visible'
    ];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
}
