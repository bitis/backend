<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['image', 'product_id', 'sort'];

    protected $hidden = ['id', 'product_id', 'created_at', 'updated_at', 'deleted_at'];
}
