<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CommissionConfig;
use App\Models\Enumerations\CommissionConfigurableType;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller
{
    /**
     * 配置提成
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $configurable_ids = $request->input('configurable_ids');
        $configurable_type = $request->input('configurable_type');

        $configs = $request->input('configs'); // [job_id, type, deduct_cost, rate, fixed_amount]

        try {
            DB::beginTransaction();
            foreach ($configurable_ids as $configurable_id) {
                foreach ($configs as $config) {
                    CommissionConfig::where('configurable_id', $configurable_id)
                        ->where('configurable_type', $configurable_type)
                        ->where('job_id', $config['job_id'])
                        ->delete($configurable_id);

                    CommissionConfig::create(array_merge($config, [
                        'configurable_id' => $configurable_id,
                        'configurable_type' => $configurable_type,
                    ]));
                }

                if ($configurable_type == CommissionConfigurableType::Product->value) {
                    Product::where('id', $configurable_id)->update(['commission_config' => true]);
                }

                if ($configurable_type == CommissionConfigurableType::Card->value) {
                    Card::where('id', $configurable_id)->update(['commission_config' => true]);
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return fail($exception->getMessage());
        }

        return success();
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $configurable_id = $request->input('configurable_id');
        $configurable_type = $request->input('configurable_type');

        return success(CommissionConfig::with('job')
            ->where('configurable_id', $configurable_id)
            ->where('configurable_type', $configurable_type)
            ->get());
    }
}
