<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentChannel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'icon', 'status'];

    protected $hidden = ['status', 'deleted_at', 'created_at', 'updated_at'];
}
