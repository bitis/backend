<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CommissionConfig;
use App\Models\Enumerations\CommissionConfigurableType;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller
{
    public function types(): JsonResponse
    {
        return success(CommissionConfigurableType::toArray());
    }

    /**
     * 获取可以配置的列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function configurable(Request $request): JsonResponse
    {
        $type = $request->input('type');

        $list = [];

        switch ($type) {
            case CommissionConfigurableType::Product->value:
            case CommissionConfigurableType::Service->value:
                $list = Product::where('store_id', $this->store_id)->where('type', $type)->paginate(getPerPage());
                break;
            case CommissionConfigurableType::OpenCard->value:
                $list = Card::where('store_id', $this->store_id)->paginate(getPerPage());
                break;
        }

        return success($list);
    }

    /**
     * 已配置的id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function configured(Request $request): JsonResponse
    {
        $configured = CommissionConfig::where('store_id', $this->store_id)
            ->where('configurable_type', $request->input('type'))
            ->pluck('configurable_id');

        return success($configured);
    }

    /**
     * 配置提成
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $configurable_id = $request->input('configurable_id');
        $configurable_type = $request->input('configurable_type');

        $configs = $request->input('configs'); // [job_id, type, deduct_cost, rate, fixed_amount]

        try {
            DB::beginTransaction();
            foreach ($configs as $config) {
                CommissionConfig::updateOrCreate([
                    'store_id' => $this->store_id,
                    'configurable_id' => $configurable_id,
                    'configurable_type' => $configurable_type,
                ], Arr::except($config, ['store_id', 'configurable_id, configurable_type']));
            }
            switch ($configurable_type) {
                case CommissionConfigurableType::Product->value:
                case CommissionConfigurableType::Service->value:
                    Product::whereIn('id', $configurable_id)->update(['commission_config' => true]);
                    break;
                case CommissionConfigurableType::OpenCard->value:
                    Card::whereIn('id', $configurable_id)->update(['commission_config' => true]);
                    break;
            }
            DB::commit();
        } catch (Exception $exception) {
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

        $configurable = match (intval($configurable_type)) {
            CommissionConfigurableType::Product->value,
            CommissionConfigurableType::Service->value => Product::where('store_id', $this->store_id)->first(),
            CommissionConfigurableType::OpenCard->value => Card::where('store_id', $this->store_id)->first(),
        };

        $configurable->configs = CommissionConfig::with('job')
            ->where('configurable_id', $configurable_id)
            ->where('configurable_type', $configurable_type)
            ->get();

        return success($configurable);
    }

    /**
     * 计算提成
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calc(Request $request): JsonResponse
    {

        $type = $request->input('type');

        // 办卡
        switch ($type) {
            case "open-card":
                $_card = $request->input('card');
                $_staffs = $request->input('staffs');

                $staffs = [];
                foreach ($_staffs as $_staff) {
                    $staff = User::select('id', 'name', 'avatar', 'job_id')->find($_staff['id']);
                    if (empty($staff)) continue;
                    $staff = $staff->toArray();
                    // 初始化
                    $staff['performance'] = $_card['price'];
                    $staff['commission'] = 0;

                    $commissionConfig = CommissionConfig::getCardConfig($staff['job_id'], $_card['id']);

                    if ($commissionConfig) {
                        $share_percent = $commissionConfig['share_out'] ? 1 / count($_staffs) : 1;

                        $staff['commission'] = $commissionConfig['type'] == CommissionConfig::TYPE_FIXED
                            ? round($commissionConfig['fixed_amount'] * $share_percent)
                            : round($_card['price'] * $commissionConfig['rate'] / 100 * $share_percent);
                    }

                    $staffs[] = $staff;
                }

                return success(compact('staffs'));
            case "consume":
                $_memberId = $request->input('member_id');
                $_products = $request->input('products');

                foreach ($_products as &$_product) {
                    $product = Product::find($_product['id']);
                    if (empty($product)) continue;
                    $staffs = [];
                    foreach ($_product['staffs'] as $_staff) {
                        $staff = User::select('id', 'name', 'avatar', 'job_id')->find($_staff['id']);
                        if (empty($staff)) continue;
                        $staff = $staff->toArray();

                        // 初始化
                        $staff['performance'] = $_product['price'];
                        $staff['commission'] = 0;

                        $commissionConfig = CommissionConfig::getProductConfig($staff['job_id'], $_product['id']);

                        if ($commissionConfig) {
                            $share_percent = $commissionConfig['share_out'] ? 1 / count($_product['staffs']) : 1;

                            $staff['commission'] = $commissionConfig['type'] == CommissionConfig::TYPE_FIXED
                                ? round($commissionConfig['fixed_amount'] * $share_percent)
                                : round($_product['price'] * $commissionConfig['rate'] / 100 * $share_percent);
                        }

                        $staffs[] = $staff;
                    }

                    $_product['staffs'] = $staffs;
                }
                return success($_products);
            case "fast-consume":
                $amount = $request->input('amount');
                $_staffs = $request->input('staffs');

                $staffs = [];
                foreach ($_staffs as $_staff) {
                    $staff = User::select('id', 'name', 'avatar', 'job_id')->find($_staff['id']);
                    if (empty($staff)) continue;
                    $staff = $staff->toArray();
                    // 初始化
                    $staff['performance'] = $amount;
                    $staff['commission'] = 0;

                    $commissionConfig = CommissionConfig::getFastConsumeConfig($staff['job_id']);

                    if ($commissionConfig) {
                        $share_percent = $commissionConfig['share_out'] ? 1 / count($_staffs) : 1;
                        $staff['commission'] = round($amount * $commissionConfig['rate'] / 100 * $share_percent);
                    }

                    $staffs[] = $staff;
                }

                return success(compact('staffs'));
            case "fast-stored":
                $amount = $request->input('amount');
                $_staffs = $request->input('staffs');
                $staffs = [];
                foreach ($_staffs as $_staff) {
                    $staff = User::select('id', 'name', 'avatar', 'job_id')->find($_staff['id']);
                    if (empty($staff)) continue;
                    $staff = $staff->toArray();
                    // 初始化
                    $staff['performance'] = $amount;
                    $staff['commission'] = 0;

                    $commissionConfig = CommissionConfig::getFastStoredConfig($staff['job_id']);

                    if ($commissionConfig) {
                        $share_percent = $commissionConfig['share_out'] ? 1 / count($_staffs) : 1;
                        $staff['commission'] = round($amount * $commissionConfig['rate'] / 100 * $share_percent);
                    }

                    $staffs[] = $staff;
                }

                return success(compact('staffs'));
            case "fast-times":
                $amount = $request->input('amount');
                $_staffs = $request->input('staffs');
                $staffs = [];
                foreach ($_staffs as $_staff) {
                    $staff = User::select('id', 'name', 'avatar', 'job_id')->find($_staff['id']);
                    if (empty($staff)) continue;
                    $staff = $staff->toArray();
                    // 初始化
                    $staff['performance'] = $amount;
                    $staff['commission'] = 0;

                    $commissionConfig = CommissionConfig::getFastTimesConfig($staff['job_id']);

                    if ($commissionConfig) {
                        $share_percent = $commissionConfig['share_out'] ? 1 / count($_staffs) : 1;
                        $staff['commission'] = round($amount * $commissionConfig['rate'] / 100 * $share_percent);
                    }

                    $staffs[] = $staff;
                }

                return success(compact('staffs'));
        }

        return success();
    }
}
