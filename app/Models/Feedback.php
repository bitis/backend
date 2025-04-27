<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'contents',
        'image',
        'reply',
        'status',
    ];

    protected $casts = [
        'image' => 'array',
    ];
}
