<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductContent extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'content'];

    protected $hidden = ['id', 'product_id', 'created_at', 'updated_at', 'deleted_at'];
}
