<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StoreStat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    /**
     * 收入统计
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function incomeData(Request $request): JsonResponse
    {
        $start = $request->input('start') ?? today()->subMonth()->format('Y-m-d');
        $end = $request->input('end') ?? today()->format('Y-m-d');

        $data = StoreStat::where('store_id', $this->store_id)
            ->whereBetween('date', [$start, $end])
            ->paginate(getPerPage());

        return success($data);
    }

}
