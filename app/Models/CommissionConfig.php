<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'configurable_type',
        'configurable_id',
        'type',
        'deduct_cost',
        'rate',
        'fixed_amount',
    ];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'configurable_id');
    }
}
