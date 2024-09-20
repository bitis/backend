<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'store_id',
        'price',
        'original_price',
        'forever',
        'month',
        'name',
        'payment_channel',
        'payment_no',
        'paid_at',
        'handled'
    ];

    public function afterPayment(): bool
    {
        $this->update(['handled' => 1, 'paid_at' => now()]);

        $store = Store::find($this->store_id);

        if ($this->forever) {
            $store->update([
                'forever' => 1
            ]);
        } else {
            $store->update([
                'expiration_date' => $this->expiration_date->addMonths($this->month)
            ]);
        }
        return true;
    }
}
