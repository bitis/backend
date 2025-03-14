<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'pid',
        'store_id',
        'name',
        'avatar',
        'gender',
        'number',
        'mobile',
        'birthday',
        'grade_id',
        'balance',
        'integral',
        'total_consume_amount',
        'total_consume_times',
        'first_consume_at',
        'last_consume_at',
        'openid',
        'unionid',
        'remark'
    ];

    const simpleFields = ['id', 'name', 'avatar', 'mobile', 'grade_id', 'balance', 'integral'];

    protected $hidden = [
        'deleted_at'
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class)->select(['id', 'name']);
    }

    protected static function booted()
    {
        parent::booted();

        static::created(function (Member $member) {
            StoreStat::updateOrCreate([
                'store_id' => $member->store_id,
                'date' => date('Y-m-d')
            ])->update([
                'new_users' => DB::raw("new_member + 1"),
                'month' => date('Ym'),
                'year' => date('Y')
            ]);
        });
    }
}
