<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StockWarningConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getStockWarning(): JsonResponse
    {
        return success(StockWarningConfig::firstOrCreate(
            ['store_id' => $this->store_id],
            ['min_number' => 10, 'allow' => 0]
        ));
    }

    public function setStockWarning(Request $request): JsonResponse
    {
        return success(StockWarningConfig::updateOrCreate(
            ['store_id' => $this->store_id],
            $request->only(['min_number', 'allow'])
        ));
    }
}
