<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockWarningConfig;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function consumeData(): JsonResponse
    {
        return success([
            ['x' => '2024-01-01', 'y' => rand(1, 100)],
            ['x' => '2024-01-02', 'y' => rand(1, 100)],
            ['x' => '2024-01-03', 'y' => rand(1, 100)],
            ['x' => '2024-01-04', 'y' => rand(1, 100)],
            ['x' => '2024-01-05', 'y' => rand(1, 100)],
            ['x' => '2024-01-06', 'y' => rand(1, 100)],
            ['x' => '2024-01-07', 'y' => rand(1, 100)],
        ]);
    }

    /**
     * App首页数据
     *
     * @return JsonResponse
     */
    public function appIndex(): JsonResponse
    {
        $stat = [
            'new_member' => Member::where('store_id', $this->store_id)->where('created_at', '>', today())->count(),
            'new_order' => Order::where('store_id', $this->store_id)->where('created_at', '>', today())->count(),
        ];

        $warning_num = StockWarningConfig::where('store_id', $this->store_id)->value('min_number') ?? 0;
        $stock_warning = Product::where('store_id', $this->store_id)->where('stock', '<=', $warning_num)->count();
        $today_appointment = Appointment::where('store_id', $this->store_id)
            ->whereDate('datetime', now()->toDateString())
            ->where('status', Appointment::status_confirm)->count();

        return success([
            'stat' => $stat,
            'todos' => [
                'stock_warning' => $stock_warning,
                'today_appointment' => $today_appointment
            ],
        ]);
    }
}
