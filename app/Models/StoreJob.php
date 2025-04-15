<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreJob extends Model
{
    protected $table = 'store_jobs';

    use HasFactory;

    protected $fillable = ['name', 'store_id'];

    protected $hidden = ['store_id', 'deleted_at', 'created_at', 'updated_at'];
}
