<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'name',
        'mobile',
        'member_id',
        'product_id',
        'product_name',
        'datetime',
        'number',
        'remark',
        'status',
        'confirm_at',
        'cancel_at',
        'checkin_at'
    ];

    const status_submit = 1;
    const status_confirm = 2;
    const status_checkin = 3;
    const status_timeout = 4;
    const status_cancel = 5;

    const statusMap = [
        self::status_submit => '待确认',
        self::status_confirm => '已确认',
        self::status_checkin => '已到店',
        self::status_timeout => '已超时',
        self::status_cancel => '已取消'
    ];

    protected $appends = ['status_name'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function getStatusNameAttribute(): string
    {
        return self::statusMap[$this->attributes['status']] ?? '未知';
    }

    public function service(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
