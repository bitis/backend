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
        'store_id',
        'job_id',
        'configurable_type',
        'configurable_id',
        'type',
        'share_out',
        'deduct_cost',
        'rate',
        'fixed_amount',
    ];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    protected $casts = [
        'deduct_cost' => 'boolean',
    ];

    const TYPE_FIXED = 1;
    const TYPE_RATE = 2;

    public function job(): BelongsTo
    {
        return $this->belongsTo(StoreJob::class);
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'configurable_id');
    }

    public static function getCardConfig($jobId, $cardId)
    {
        return static::getConfig(CommissionConfigurableType::OpenCard->value, $jobId, $cardId);
    }

    public static function getProductConfig($jobId, $productId)
    {
        return static::getConfig(CommissionConfigurableType::Product->value, $jobId, $productId);
    }

    public static function getFastConsumeConfig($jobId)
    {
        return static::getConfig(CommissionConfigurableType::FastConsume->value, $jobId);
    }

    public static function getFastStoredConfig($jobId)
    {
        return static::getConfig(CommissionConfigurableType::FastStored->value, $jobId);
    }

    public static function getConfig($configurable_type, $jobId, $configurable_id = null)
    {
        if (empty($jobId)) return null;

        $config = static::where('configurable_id', $configurable_id)
            ->where('job_id', $jobId)
            ->where('configurable_type', $configurable_type)
            ->first();

        if ($config) return $config->toArray();

        return null;
    }

}
