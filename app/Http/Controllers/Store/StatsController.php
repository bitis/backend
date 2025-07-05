<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
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

}
