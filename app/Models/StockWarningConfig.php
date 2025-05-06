<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

class StockWarningConfig extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = ['min_number', 'status'];
}
