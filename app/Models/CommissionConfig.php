<?php

namespace App\Models;

use App\Models\Enumerations\CommissionConfigurableType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    const TYPE_FIXED = 1;
    const TYPE_RATE = 2;

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'configurable_id');
    }

    public static function getCardConfig($cardId, $jobId)
    {
        return static::where('configurable_id', $cardId)
            ->where('job_id', $jobId)
            ->where('configurable_type', CommissionConfigurableType::Card->value)
            ->first();
    }


}
