<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, DefaultDatetimeFormat;


    /**
     * 生成22位订单号
     *
     * @param int $store_id
     * @return string
     */
    public function generateNumber(int $store_id): string
    {
        $date = date('ymdHis');
        return sprintf("%s%06d%s", $date, $store_id, rand(1000, 9999));
    }
}
