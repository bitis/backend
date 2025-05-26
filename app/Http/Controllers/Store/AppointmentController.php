<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * 列表 7217
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $appointments = Appointment::where('store_id', $this->store_id)
            ->when($request->input('member_id'), fn($query, $member_id) => $query->where('member_id', $member_id))
            ->when($request->input('status'), fn($query, $status) => $query->where('status', $status))
            ->when($request->input('start_time'), fn($query, $start_time) => $query->where('time', '>=', $start_time))
            ->when($request->input('end_time'), fn($query, $end_time) => $query->where('time', '<', $end_time))
            ->when($request->input('search'), fn($query, $search) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%");
            }))
            ->orderBy('time')
            ->paginate(getPerPage());

        return success($appointments);
    }

    /**
     * 提交预约
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $appointment = Appointment::where('store_id', $this->store_id)
            ->findOr($request->input('id'), fn() => new Appointment(['store_id' => $this->store_id]));

        $appointment->fill($request->only([
            'member_id',
            'member_name',
            'mobile',
            'product_id',
            'product_name',
            'time',
            'time_text',
            'number',
            'remark',
        ]));

        $appointment->save();

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
        $appointment = Appointment::where('store_id', $this->store_id)->find($request->input('id'));

        return success($appointment);
    }

    /**
     * 改变状态
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setStatus(Request $request): JsonResponse
    {
        Appointment::where('store_id', $this->store_id)->where('id', $request->input('id'))->update(['status' => $request->input('status')]);

        return success();
    }

    /**
     * 获取预约配置
     *
     * @return JsonResponse
     */
    public function config(): JsonResponse
    {
        return success(AppointmentConfig::getByStoreId($this->store_id));
    }

    /**
     * 更新配置
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function configForm(Request $request): JsonResponse
    {
        AppointmentConfig::updateOrCreate(['store_id' => $this->store_id], [
            $request->only([
                'earliest',
                'latest',
                'interval',
                'max_number',
                'before_time',
                'status',
            ])
        ]);

        return success();
    }

}
